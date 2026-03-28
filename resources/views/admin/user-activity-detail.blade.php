<x-app-layout>
    @section('page-title', $user->name . ' — Activity')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Activity: {{ $user->name }}</h2>
            <a href="{{ route('activity.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span class="hidden sm:inline">Back to users</span><span class="sm:hidden">Back</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- User profile card --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 mb-8">
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="relative shrink-0">
                        <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=112&d=mp" alt="{{ $user->name }}" class="w-10 sm:w-14 h-10 sm:h-14 rounded-full">
                        @if($user->last_active_at?->gt(now()->subMinutes(5)))
                            <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-800">{{ $user->name }}</h3>
                            <span class="text-xs font-medium px-1.5 py-0.5 rounded
                                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-600' : '' }}
                                {{ $user->role === 'editor' ? 'bg-blue-100 text-blue-600' : '' }}
                                {{ $user->role === 'auditor' ? 'bg-gray-100 text-gray-500' : '' }}">{{ ucfirst($user->role) }}</span>
                            @if($user->approved)
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-green-100 text-green-600">Active</span>
                            @else
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-red-100 text-red-500">Inactive</span>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-1 text-xs text-gray-500">
                            <div><span class="text-gray-400">Email:</span> {{ $user->email }}</div>
                            @if($user->organisation)
                                <div><span class="text-gray-400">Organisation:</span> {{ $user->organisation }}</div>
                            @endif
                            <div><span class="text-gray-400">Account created:</span> {{ usertime($user->created_at, 'M j, Y') }}</div>
                            <div>
                                <span class="text-gray-400">Status:</span>
                                @if($user->last_active_at?->gt(now()->subMinutes(5)))
                                    <span class="text-green-600 font-medium">Online now</span>
                                @elseif($user->last_active_at)
                                    Last active {{ usertime($user->last_active_at)->diffForHumans() }}
                                @else
                                    Never logged in
                                @endif
                            </div>
                            @if($locations->isNotEmpty())
                                @php $lastLoc = $locations->first(); @endphp
                                <div><span class="text-gray-400">Last IP:</span> <span class="font-mono">{{ $lastLoc->ip }}</span>@if($lastLoc->country_code) <span class="ml-1 px-1 py-0.5 rounded bg-blue-50 text-blue-600 text-[10px]">{{ $lastLoc->country_code }}</span>@endif @if($lastLoc->city) {{ $lastLoc->city }}@endif</div>
                                @if($lastLoc->asn_org)
                                    <div><span class="text-gray-400">ISP:</span> {{ $lastLoc->asn_org }}</div>
                                @endif
                            @endif
                            @if($activities->isNotEmpty())
                                @php $lastActivity = $activities->first(); @endphp
                                @if($lastActivity->browser)
                                    <div><span class="text-gray-400">Browser:</span> {{ $lastActivity->browser }} / {{ $lastActivity->os }}</div>
                                @endif
                                @if($lastActivity->device)
                                    <div><span class="text-gray-400">Device:</span> {{ ucfirst($lastActivity->device) }}@if($lastActivity->viewport_w) ({{ $lastActivity->viewport_w }}x{{ $lastActivity->viewport_h }})@endif</div>
                                @endif
                                @if($lastActivity->timezone)
                                    <div><span class="text-gray-400">Timezone:</span> {{ $lastActivity->timezone }}</div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2 shrink-0 hidden sm:flex">
                        <form method="POST" action="{{ route('activity.clear', $user) }}" onsubmit="return confirm('Delete all activity logs for {{ $user->name }}? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 text-xs text-red-500 border border-red-200 rounded-md hover:bg-red-50">Clear activity</button>
                        </form>
                        <a href="{{ route('users.edit', $user) }}" class="px-3 py-1.5 text-xs text-gray-500 border border-gray-200 rounded-md hover:bg-gray-50">Edit user</a>
                    </div>
                </div>
            </div>

            {{-- Time range selector --}}
            <div class="flex items-center gap-2 mb-6">
                @foreach([7 => '7 days', 30 => '30 days', 90 => '90 days', 0 => 'All time'] as $d => $label)
                    <a href="{{ route('activity.show', [$user, 'days' => $d]) }}"
                       class="px-3 py-1.5 text-xs rounded-full {{ $days == $d ? 'bg-gray-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            {{-- Summary stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-2xl font-bold text-gray-800">{{ $activities->count() }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">Page views</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $topPages->count() }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">Unique pages</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    @php $totalMinutes = round($activities->sum('time_spent') / 60); @endphp
                    <div class="text-2xl font-bold text-green-600">{{ $totalMinutes >= 60 ? round($totalMinutes / 60, 1) . 'h' : $totalMinutes . 'm' }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">Total time</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-2xl font-bold text-gray-500">{{ $dailyActivity->count() }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">Active days</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                {{-- Most viewed pages --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Most Viewed Pages</h3>
                    </div>
                    @if($topPages->isEmpty())
                        <div class="px-5 py-6 text-center text-sm text-gray-400">No activity</div>
                    @else
                        <div class="divide-y divide-gray-50">
                            @foreach($topPages as $page)
                                <div class="px-5 py-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                @if($page->doc_id && $page->doc_id !== 'null')
                                                    <span class="text-[10px] font-mono font-semibold px-1 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor(explode('-', $page->doc_id)[0] ?? '') }}">{{ $page->doc_id }}</span>
                                                @endif
                                                <span class="text-sm text-gray-800 truncate">{{ ($page->doc_title && $page->doc_title !== 'null') ? $page->doc_title : ($page->path ?: 'Unknown') }}</span>
                                            </div>
                                            <div class="text-[11px] text-gray-400 font-mono mt-0.5">{{ $page->path }}</div>
                                        </div>
                                        <div class="flex items-center gap-3 shrink-0 text-xs text-gray-400">
                                            <span>{{ $page->views }} {{ Str::plural('view', $page->views) }}</span>
                                            <span>{{ round($page->total_time / 60) }}m</span>
                                            @if($page->max_scroll !== null)
                                                <span class="{{ $page->max_scroll >= 90 ? 'text-green-600' : ($page->max_scroll >= 50 ? 'text-amber-600' : 'text-red-500') }}">{{ $page->max_scroll }}% read</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Device & Browser --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Devices & Browsers</h3>
                    </div>
                    @if($devices->isEmpty())
                        <div class="px-5 py-6 text-center text-sm text-gray-400">No data</div>
                    @else
                        <div class="divide-y divide-gray-50">
                            @foreach($devices as $device)
                                <div class="px-5 py-3 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 text-gray-600">{{ $device->device ?? 'Unknown' }}</span>
                                        <span class="text-sm text-gray-700">{{ $device->browser }} / {{ $device->os }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $device->count }}x</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Locations --}}
            @if($locations->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Locations & IPs</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($locations as $loc)
                            <div class="px-4 sm:px-5 py-3">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-mono text-gray-600">{{ $loc->ip }}</span>
                                        @if($loc->country_code)
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 shrink-0">{{ $loc->country_code }}</span>
                                        @endif
                                        @if($loc->city)
                                            <span class="text-xs text-gray-600">{{ $loc->city }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 shrink-0 text-xs text-gray-400">
                                        <span>{{ $loc->count }}x</span>
                                        <span>{{ usertime($loc->last_seen)->diffForHumans() }}</span>
                                    </div>
                                </div>
                                @if($loc->asn_org)
                                    <div class="text-[11px] text-gray-400">AS{{ $loc->asn_number }} · {{ $loc->asn_org }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Sessions --}}
            @if($sessions->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Sessions</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($sessions as $session)
                            <a href="{{ route('activity.session', [$user, $session->session_uid]) }}" class="block px-5 py-3 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500">{{ usertime($session->started, 'M j H:i') }}</span>
                                        <span class="text-[10px] text-gray-300">-</span>
                                        <span class="text-xs text-gray-500">{{ usertime($session->ended, 'H:i') }}</span>
                                        @if($session->country_code)
                                            <span class="text-[10px] px-1 py-0.5 rounded bg-blue-50 text-blue-600">{{ $session->country_code }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-gray-400">
                                        <span>{{ $session->pages }} {{ Str::plural('page', $session->pages) }}</span>
                                        <span>{{ round($session->total_time / 60) }}m</span>
                                        <span class="text-[10px]">{{ $session->device }} · {{ $session->browser }}</span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Daily activity --}}
            @if($dailyActivity->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">Daily Activity</h3>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($dailyActivity as $day)
                            <div class="px-5 py-3 flex items-center justify-between">
                                <span class="text-sm text-gray-700">{{ usertime($day->date, 'M j, Y') }}</span>
                                <div class="flex items-center gap-4 text-xs text-gray-400">
                                    <span>{{ $day->views }} {{ Str::plural('page', $day->views) }}</span>
                                    <span>{{ round($day->total_time / 60) }}m spent</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Recent page views --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="font-semibold text-gray-800">Recent Activity</h3>
                    <a href="{{ route('activity.log', $user) }}" class="text-xs text-blue-600 hover:text-blue-800">View full log</a>
                </div>
                @if($activities->isEmpty())
                    <div class="px-5 py-6 text-center text-sm text-gray-400">No activity in this period</div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($activities as $activity)
                            <div class="px-4 sm:px-5 py-2.5 flex items-start gap-2 sm:gap-3">
                                <span class="text-[10px] sm:text-[11px] text-gray-500 shrink-0 w-12 sm:w-16 text-right mt-0.5 font-mono">{{ usertime($activity->created_at, 'M j H:i') }}</span>
                                @php
                                    $typeConfig = match($activity->type) {
                                        'comment' => ['bg-amber-100 text-amber-700', 'Comment'],
                                        'reply' => ['bg-blue-100 text-blue-700', 'Reply'],
                                        'resolve_comment' => ['bg-green-100 text-green-700', 'Resolved'],
                                        'unresolve_comment' => ['bg-red-100 text-red-600', 'Reopened'],
                                        'delete_comment' => ['bg-red-100 text-red-600', 'Deleted'],
                                        'edit_document' => ['bg-purple-100 text-purple-700', 'Edited'],
                                        'publish' => ['bg-teal-100 text-teal-700', 'Published'],
                                        'download' => ['bg-gray-100 text-gray-600', 'Download'],
                                        'form_submit' => ['bg-indigo-100 text-indigo-700', 'Submitted'],
                                        'login' => ['bg-gray-100 text-gray-600', 'Login'],
                                        default => ['bg-blue-50 text-blue-600', 'Viewed'],
                                    };
                                @endphp
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded shrink-0 whitespace-nowrap mt-0.5 {{ $typeConfig[0] }}">{{ $typeConfig[1] }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        @if($activity->doc_id && $activity->doc_id !== 'null')
                                            <span class="text-[10px] font-mono font-semibold px-1 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor(explode('-', $activity->doc_id)[0] ?? '') }}">{{ $activity->doc_id }}</span>
                                        @endif
                                        <span class="text-xs sm:text-sm text-gray-800 truncate">{{ ($activity->doc_title && $activity->doc_title !== 'null') ? $activity->doc_title : ($activity->page_title ?: $activity->path) }}</span>
                                    </div>
                                    <div class="text-[10px] sm:text-[11px] text-gray-400 font-mono mt-0.5 truncate">{{ $activity->path }}</div>
                                    @if($activity->detail)
                                        <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $activity->detail }}</p>
                                    @endif
                                    <div class="flex items-center gap-3 mt-0.5 text-[11px] text-gray-400">
                                @if($activity->type === 'page_view')
                                    <span>{{ $activity->time_spent }}s</span>
                                    @if($activity->scroll_depth !== null)
                                        <span class="{{ $activity->scroll_depth >= 90 ? 'text-green-600' : ($activity->scroll_depth >= 50 ? 'text-amber-600' : 'text-red-500') }}">{{ $activity->scroll_depth }}%</span>
                                    @endif
                                @endif
                                @if($activity->device)<span>{{ $activity->device }}</span>@endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
