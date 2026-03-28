<x-app-layout>
    @section('page-title', $user->name . ' — Session')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Session: {{ $user->name }}</h2>
            <a href="{{ route('activity.show', $user) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span class="hidden sm:inline">Back to overview</span><span class="sm:hidden">Back</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Session info card --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-8">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                    <div>
                        <div class="text-xs text-gray-400 mb-0.5">Started</div>
                        <div class="text-sm font-medium text-gray-800">{{ usertime($started, 'M j, Y H:i') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-0.5">Duration</div>
                        <div class="text-sm font-medium text-gray-800">{{ round($totalTime / 60) }} min</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-0.5">Pages viewed</div>
                        <div class="text-sm font-medium text-gray-800">{{ $totalPages }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-0.5">Actions taken</div>
                        <div class="text-sm font-medium text-gray-800">{{ $totalActions }}</div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-500 border-t border-gray-100 pt-3">
                    @if($device)<span><span class="text-gray-400">Device:</span> {{ ucfirst($device) }}</span>@endif
                    @if($browser)<span><span class="text-gray-400">Browser:</span> {{ $browser }} / {{ $os }}</span>@endif
                    @if($ip)<span><span class="text-gray-400">IP:</span> <span class="font-mono">{{ $ip }}</span></span>@endif
                    @if($country_code)<span><span class="text-gray-400">Location:</span> {{ $country_code }}@if($activities->first()->city), {{ $activities->first()->city }}@endif</span>@endif
                    @if($asn_org)<span><span class="text-gray-400">ISP:</span> {{ $asn_org }}</span>@endif
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Session Timeline</h3>
                </div>
                <div class="relative">
                    {{-- Vertical line --}}
                    <div class="absolute left-[39px] top-0 bottom-0 w-px bg-gray-100"></div>

                    @foreach($activities as $activity)
                        <div class="px-5 py-3 relative flex items-start gap-4">
                            {{-- Time --}}
                            <span class="text-[10px] text-gray-400 shrink-0 w-10 text-right mt-1">{{ usertime($activity->created_at, 'H:i') }}</span>

                            {{-- Dot --}}
                            @php
                                $dotColor = match($activity->type) {
                                    'comment' => 'bg-amber-500',
                                    'reply' => 'bg-blue-500',
                                    'resolve_comment' => 'bg-green-500',
                                    'unresolve_comment', 'delete_comment' => 'bg-red-500',
                                    'publish' => 'bg-teal-500',
                                    'download' => 'bg-gray-400',
                                    'login' => 'bg-gray-400',
                                    default => 'bg-blue-300',
                                };
                            @endphp
                            <div class="w-3 h-3 rounded-full {{ $dotColor }} shrink-0 mt-1 relative z-10 ring-2 ring-white"></div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    @php
                                        $typeConfig = match($activity->type) {
                                            'comment' => ['bg-amber-100 text-amber-700', 'Commented'],
                                            'reply' => ['bg-blue-100 text-blue-700', 'Replied'],
                                            'resolve_comment' => ['bg-green-100 text-green-700', 'Resolved comment'],
                                            'unresolve_comment' => ['bg-red-100 text-red-600', 'Reopened comment'],
                                            'publish' => ['bg-teal-100 text-teal-700', 'Published changes'],
                                            'download' => ['bg-gray-100 text-gray-600', 'Downloaded'],
                                            'login' => ['bg-gray-100 text-gray-600', 'Logged in'],
                                            default => ['bg-blue-50 text-blue-600', 'Viewed'],
                                        };
                                    @endphp
                                    <span class="text-xs font-medium {{ $typeConfig[0] }} px-1.5 py-0.5 rounded">{{ $typeConfig[1] }}</span>
                                    @if($activity->doc_id && $activity->doc_id !== 'null')
                                        <span class="text-[10px] font-mono font-semibold px-1 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor(explode('-', $activity->doc_id)[0] ?? '') }}">{{ $activity->doc_id }}</span>
                                    @endif
                                    <span class="text-sm text-gray-700 truncate">{{ ($activity->doc_title && $activity->doc_title !== 'null') ? $activity->doc_title : ($activity->page_title ?: $activity->path) }}</span>
                                </div>

                                @if($activity->detail)
                                    <p class="text-xs text-gray-500 mt-1 bg-gray-50 rounded p-2 border-l-2 border-gray-200">{{ $activity->detail }}</p>
                                @endif

                                @if($activity->type === 'page_view')
                                    <div class="flex items-center gap-3 mt-1 text-[10px] text-gray-400">
                                        <span>{{ $activity->time_spent }}s on page</span>
                                        @if($activity->scroll_depth !== null)
                                            <span class="{{ $activity->scroll_depth >= 90 ? 'text-green-600' : ($activity->scroll_depth >= 50 ? 'text-amber-600' : 'text-red-500') }}">Scrolled {{ $activity->scroll_depth }}%</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
