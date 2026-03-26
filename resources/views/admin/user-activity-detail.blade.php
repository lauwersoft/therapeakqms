<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('activity.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $user->name }}</h2>
            <span class="text-xs font-medium px-1.5 py-0.5 rounded
                {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-600' : '' }}
                {{ $user->role === 'editor' ? 'bg-blue-100 text-blue-600' : '' }}
                {{ $user->role === 'auditor' ? 'bg-gray-100 text-gray-500' : '' }}">{{ ucfirst($user->role) }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Time range selector --}}
            <div class="flex items-center gap-2 mb-6">
                @foreach([7 => '7 days', 30 => '30 days', 90 => '90 days'] as $d => $label)
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
                                                @if($page->doc_id)
                                                    <span class="text-[10px] font-mono font-semibold px-1 py-0.5 rounded {{ \App\Services\DocumentMetadata::typeColor(explode('-', $page->doc_id)[0] ?? '') }}">{{ $page->doc_id }}</span>
                                                @endif
                                                <span class="text-sm text-gray-700 truncate">{{ $page->doc_title ?: $page->path }}</span>
                                            </div>
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
                            <div class="px-5 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="text-xs font-mono text-gray-500">{{ $loc->ip }}</span>
                                    @if($loc->country_code)
                                        <span class="text-xs px-1.5 py-0.5 rounded bg-blue-50 text-blue-600">{{ $loc->country_code }}</span>
                                    @endif
                                    @if($loc->asn)
                                        <span class="text-[10px] text-gray-400">{{ Str::limit($loc->asn, 40) }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 shrink-0 text-xs text-gray-400">
                                    <span>{{ $loc->count }}x</span>
                                    <span>{{ \Carbon\Carbon::parse($loc->last_seen)->diffForHumans() }}</span>
                                </div>
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
                            <div class="px-5 py-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->started)->format('M j H:i') }}</span>
                                        <span class="text-[10px] text-gray-300">-</span>
                                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($session->ended)->format('H:i') }}</span>
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
                            </div>
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
                                <span class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($day->date)->format('M j, Y') }}</span>
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
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Recent Activity</h3>
                </div>
                @if($activities->isEmpty())
                    <div class="px-5 py-6 text-center text-sm text-gray-400">No activity in this period</div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($activities as $activity)
                            <div class="px-5 py-2.5 flex items-center gap-3">
                                <span class="text-[10px] text-gray-300 shrink-0 w-16 text-right">{{ $activity->created_at->format('M j H:i') }}</span>
                                @if($activity->doc_id)
                                    <span class="text-[10px] font-mono font-semibold px-1 py-0.5 rounded shrink-0 {{ \App\Services\DocumentMetadata::typeColor(explode('-', $activity->doc_id)[0] ?? '') }}">{{ $activity->doc_id }}</span>
                                @endif
                                <span class="text-xs text-gray-600 truncate flex-1">{{ $activity->doc_title ?: $activity->path }}</span>
                                <span class="text-[10px] text-gray-400 shrink-0">{{ $activity->time_spent }}s</span>
                                @if($activity->scroll_depth !== null)
                                    <span class="text-[10px] shrink-0 {{ $activity->scroll_depth >= 90 ? 'text-green-600' : ($activity->scroll_depth >= 50 ? 'text-amber-600' : 'text-red-500') }}">{{ $activity->scroll_depth }}%</span>
                                @endif
                                <span class="text-[10px] text-gray-300 shrink-0">{{ $activity->device }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
