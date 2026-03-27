<?php

namespace App\Http\Controllers;

use App\Jobs\TrackUserActivityJob;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function track(Request $request)
    {
        if (!$request->user() || !$request->user()->track_activity) {
            return response()->json(['ok' => true]);
        }

        $data = $request->validate([
            'path' => 'required|string|max:500',
            'doc_id' => 'nullable|string|max:20',
            'doc_title' => 'nullable|string|max:255',
            'time_spent' => 'required|integer|min:1|max:7200',
            'device' => 'nullable|string|max:20',
            'viewport_w' => 'nullable|integer|max:9999',
            'viewport_h' => 'nullable|integer|max:9999',
            'browser' => 'nullable|string|max:50',
            'os' => 'nullable|string|max:50',
            'session_uid' => 'nullable|string|max:36',
            'browser_uid' => 'nullable|string|max:36',
            'timezone' => 'nullable|string|max:50',
            'referrer' => 'nullable|string|max:500',
            'user_agent' => 'nullable|string|max:500',
            'scroll_depth' => 'nullable|integer|min:0|max:100',
            'page_title' => 'nullable|string|max:255',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['ip'] = $request->ip();

        TrackUserActivityJob::dispatch($data);

        return response()->json(['ok' => true]);
    }

    public function index(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        $users = User::whereNotNull('last_active_at')
            ->orderByDesc('last_active_at')
            ->get();

        return view('admin.user-activity', ['users' => $users]);
    }

    public function show(Request $request, User $user)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        $days = (int) $request->query('days', 30);

        $base = fn() => UserActivity::where('user_id', $user->id)
            ->when($days > 0, fn($q) => $q->where('created_at', '>=', now()->subDays($days)));

        $pageViews = fn() => $base()->where('type', 'page_view');

        $activities = $base()
            ->orderByDesc('created_at')
            ->limit(500)
            ->get();

        // Most viewed pages (page views only)
        $topPages = $pageViews()
            ->selectRaw('path, doc_id, doc_title, count(*) as views, sum(time_spent) as total_time, max(scroll_depth) as max_scroll, round(avg(scroll_depth)) as avg_scroll')
            ->groupBy('path', 'doc_id', 'doc_title')
            ->orderByDesc('total_time')
            ->limit(20)
            ->get();

        // Activity by day
        $dailyActivity = $base()
            ->selectRaw('DATE(created_at) as date, count(*) as views, sum(time_spent) as total_time')
            ->groupByRaw('DATE(created_at)')
            ->orderByDesc('date')
            ->get();

        // Device breakdown (page views only — server-side actions have no device data)
        $devices = $pageViews()
            ->selectRaw('device, browser, os, count(*) as count')
            ->groupBy('device', 'browser', 'os')
            ->orderByDesc('count')
            ->get();

        // IP / Location breakdown
        $locations = $base()
            ->selectRaw('ip, country_code, city, asn_number, asn_org, count(*) as count, max(created_at) as last_seen')
            ->groupBy('ip', 'country_code', 'city', 'asn_number', 'asn_org')
            ->orderByDesc('last_seen')
            ->get();

        // Session breakdown
        $sessions = $base()
            ->whereNotNull('session_uid')
            ->selectRaw('session_uid, min(created_at) as started, max(created_at) as ended, count(*) as pages, sum(time_spent) as total_time, device, browser, os, ip, country_code')
            ->groupBy('session_uid', 'device', 'browser', 'os', 'ip', 'country_code')
            ->orderByDesc('started')
            ->limit(50)
            ->get();

        return view('admin.user-activity-detail', [
            'user' => $user,
            'activities' => $activities,
            'topPages' => $topPages,
            'dailyActivity' => $dailyActivity,
            'devices' => $devices,
            'locations' => $locations,
            'sessions' => $sessions,
            'days' => $days,
        ]);
    }

    public function log(Request $request, User $user)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        $page = max(1, (int) $request->query('page', 1));
        $perPage = 50;
        $typeFilter = $request->query('type', '');
        $dateFilter = $request->query('date', '');

        $query = UserActivity::where('user_id', $user->id)
            ->when($typeFilter, fn($q) => $q->where('type', $typeFilter))
            ->when($dateFilter, fn($q) => $q->whereDate('created_at', $dateFilter))
            ->orderByDesc('created_at');

        $total = $query->count();
        $activities = $query->skip(($page - 1) * $perPage)->take($perPage)->get();
        $totalPages = max(1, ceil($total / $perPage));

        // Available dates for navigation
        $activeDates = UserActivity::where('user_id', $user->id)
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupByRaw('DATE(created_at)')
            ->orderByDesc('date')
            ->limit(90)
            ->get();

        return view('admin.user-activity-log', [
            'user' => $user,
            'activities' => $activities,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'typeFilter' => $typeFilter,
            'dateFilter' => $dateFilter,
            'activeDates' => $activeDates,
        ]);
    }

    public function session(Request $request, User $user, string $sessionUid)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        $activities = UserActivity::where('user_id', $user->id)
            ->where('session_uid', $sessionUid)
            ->orderBy('created_at')
            ->get();

        if ($activities->isEmpty()) {
            abort(404);
        }

        $first = $activities->first();
        $last = $activities->last();

        return view('admin.user-activity-session', [
            'user' => $user,
            'activities' => $activities,
            'sessionUid' => $sessionUid,
            'started' => $first->created_at,
            'ended' => $last->created_at,
            'totalPages' => $activities->where('type', 'page_view')->count(),
            'totalActions' => $activities->where('type', '!=', 'page_view')->count(),
            'totalTime' => $activities->sum('time_spent'),
            'device' => $first->device,
            'browser' => $first->browser,
            'os' => $first->os,
            'ip' => $first->ip,
            'country_code' => $first->country_code,
            'asn_org' => $first->asn_org,
        ]);
    }

    public function clear(Request $request, User $user)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        UserActivity::where('user_id', $user->id)->delete();

        return redirect()->route('activity.show', $user)
            ->with('success', "Activity logs cleared for {$user->name}.");
    }
}
