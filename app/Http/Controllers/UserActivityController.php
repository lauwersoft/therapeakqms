<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use GeoIp2\Database\Reader;
use Illuminate\Http\Request;

class UserActivityController extends Controller
{
    public function track(Request $request)
    {
        if (!$request->user()) {
            return response()->json(['ok' => false], 401);
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
            'referrer' => 'nullable|string|max:500',
            'user_agent' => 'nullable|string|max:500',
            'scroll_depth' => 'nullable|integer|min:0|max:100',
            'page_title' => 'nullable|string|max:255',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['ip'] = $request->ip();

        // Local GeoIP lookup — instant, no API call
        $geo = $this->geoLookup($data['ip']);
        $data['country_code'] = $geo['country_code'];
        $data['asn'] = $geo['asn'];

        UserActivity::create($data);

        return response()->json(['ok' => true]);
    }

    private function geoLookup(string $ip): array
    {
        $result = ['country_code' => null, 'asn' => null];

        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return $result;
        }

        try {
            $reader = new Reader(base_path('geoip/GeoLite2-Country.mmdb'));
            $result['country_code'] = $reader->country($ip)->country->isoCode;
        } catch (\Throwable $e) {}

        try {
            $reader = new Reader(base_path('geoip/GeoLite2-ASN.mmdb'));
            $asn = $reader->asn($ip);
            $result['asn'] = $asn->autonomousSystemOrganization;
        } catch (\Throwable $e) {}

        return $result;
    }

    public function index(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            abort(403);
        }

        $users = User::where('id', '!=', $request->user()->id)
            ->whereNotNull('last_active_at')
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

        $activities = UserActivity::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->orderByDesc('created_at')
            ->limit(500)
            ->get();

        // Most viewed pages
        $topPages = UserActivity::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('path, doc_id, doc_title, count(*) as views, sum(time_spent) as total_time, max(scroll_depth) as max_scroll, round(avg(scroll_depth)) as avg_scroll')
            ->groupBy('path', 'doc_id', 'doc_title')
            ->orderByDesc('total_time')
            ->limit(20)
            ->get();

        // Activity by day
        $dailyActivity = UserActivity::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, count(*) as views, sum(time_spent) as total_time')
            ->groupByRaw('DATE(created_at)')
            ->orderByDesc('date')
            ->get();

        // Device breakdown
        $devices = UserActivity::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('device, browser, os, count(*) as count')
            ->groupBy('device', 'browser', 'os')
            ->orderByDesc('count')
            ->get();

        // IP / Location breakdown
        $locations = UserActivity::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays($days))
            ->selectRaw('ip, country_code, asn, count(*) as count, max(created_at) as last_seen')
            ->groupBy('ip', 'country_code', 'asn')
            ->orderByDesc('last_seen')
            ->get();

        // Session breakdown
        $sessions = UserActivity::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays($days))
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
}
