<x-app-layout>
    @section('page-title', $user->name . ' — Full Log')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Activity Log: {{ $user->name }}</h2>
            <a href="{{ route('activity.show', $user) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span class="hidden sm:inline">Back to overview</span><span class="sm:hidden">Back</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex gap-6">
                {{-- Sidebar: date navigation --}}
                <div class="shrink-0 w-44 hidden lg:block">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden sticky top-8">
                        <div class="px-3 py-2.5 border-b border-gray-100">
                            <h4 class="text-xs font-semibold text-gray-500">Dates</h4>
                        </div>
                        <div class="max-h-[60vh] overflow-y-auto">
                            <a href="{{ route('activity.log', [$user, 'type' => $typeFilter]) }}"
                               class="block px-3 py-1.5 text-xs {{ !$dateFilter ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                All dates
                            </a>
                            @foreach($activeDates as $d)
                                <a href="{{ route('activity.log', [$user, 'date' => $d->date, 'type' => $typeFilter]) }}"
                                   class="flex items-center justify-between px-3 py-1.5 text-xs {{ $dateFilter === $d->date ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                    <span>{{ usertime($d->date, 'M j, Y') }}</span>
                                    <span class="text-gray-300">{{ $d->count }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Main content --}}
                <div class="flex-1 min-w-0">
                    {{-- Mobile date picker --}}
                    <div class="lg:hidden mb-4">
                        <select onchange="if(this.value){window.location='{{ route('activity.log', [$user, 'type' => $typeFilter]) }}&date='+this.value}else{window.location='{{ route('activity.log', [$user, 'type' => $typeFilter]) }}'}"
                                class="w-full text-xs border-gray-200 rounded-md py-1.5 bg-white">
                            <option value="">All dates</option>
                            @foreach($activeDates as $d)
                                <option value="{{ $d->date }}" {{ $dateFilter === $d->date ? 'selected' : '' }}>{{ usertime($d->date, 'M j, Y') }} ({{ $d->count }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Type filters --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            @foreach(['' => 'All', 'page_view' => 'Views', 'comment' => 'Comments', 'reply' => 'Replies', 'resolve_comment' => 'Resolved', 'login' => 'Logins', 'download' => 'Downloads', 'publish' => 'Published'] as $key => $label)
                                <a href="{{ route('activity.log', [$user, 'type' => $key, 'date' => $dateFilter]) }}"
                                   class="px-2.5 py-1 text-[11px] rounded-full {{ $typeFilter === $key ? 'bg-gray-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </div>
                        <span class="text-xs text-gray-400 shrink-0 ml-3">{{ number_format($total) }} {{ Str::plural('entry', $total) }}</span>
                    </div>

                    @if($dateFilter)
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xs text-gray-500">Showing: {{ usertime($dateFilter, 'l, M j, Y') }}</span>
                            <a href="{{ route('activity.log', [$user, 'type' => $typeFilter]) }}" class="text-xs text-blue-600 hover:text-blue-800">Clear date</a>
                        </div>
                    @endif

                    {{-- Activity list --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        @if($activities->isEmpty())
                            <div class="px-5 py-12 text-center text-sm text-gray-400">No activity found</div>
                        @else
                            <div class="divide-y divide-gray-50">
                                @foreach($activities as $activity)
                                    <a href="{{ $activity->session_uid ? route('activity.session', [$user, $activity->session_uid]) : '#' }}"
                                       class="block px-4 py-2.5 hover:bg-blue-50/50 transition-colors">
                                        <div class="flex items-start gap-2">
                                            <span class="text-[11px] text-gray-500 shrink-0 w-20 text-right mt-0.5 font-mono">{{ usertime($activity->created_at, 'M j H:i') }}</span>
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
                                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded shrink-0 w-16 text-center {{ $typeConfig[0] }}">{{ $typeConfig[1] }}</span>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2">
                                                    @if($activity->doc_id && $activity->doc_id !== 'null')
                                                        <span class="text-[10px] font-mono font-semibold px-1 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor(explode('-', $activity->doc_id)[0] ?? '') }}">{{ $activity->doc_id }}</span>
                                                    @endif
                                                    <span class="text-sm text-gray-800 truncate">{{ ($activity->doc_title && $activity->doc_title !== 'null') ? $activity->doc_title : ($activity->page_title ?: $activity->path) }}</span>
                                                </div>
                                                <div class="text-[11px] text-gray-400 font-mono mt-0.5">{{ $activity->path }}</div>
                                                @if($activity->detail)
                                                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $activity->detail }}</p>
                                                @endif
                                                <div class="flex items-center gap-3 mt-1 text-[11px] text-gray-400">
                                                    @if($activity->type === 'page_view')
                                                        <span>{{ $activity->time_spent }}s</span>
                                                        @if($activity->scroll_depth !== null)
                                                            <span class="{{ $activity->scroll_depth >= 90 ? 'text-green-600' : ($activity->scroll_depth >= 50 ? 'text-amber-600' : 'text-red-500') }}">{{ $activity->scroll_depth }}%</span>
                                                        @endif
                                                    @endif
                                                    @if($activity->device)<span>{{ $activity->device }}</span>@endif
                                                    @if($activity->ip)<span class="font-mono">{{ $activity->ip }}</span>@endif
                                                    @if($activity->country_code)
                                                        <span class="px-1 py-0.5 rounded bg-blue-50 text-blue-600 text-[10px]">{{ $activity->country_code }}</span>
                                                    @endif
                                                    @if($activity->city)
                                                        <span>{{ $activity->city }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Pagination --}}
                    @if($totalPages > 1)
                        <div class="flex items-center justify-center gap-2 mt-6">
                            @if($currentPage > 1)
                                <a href="{{ route('activity.log', [$user, 'page' => $currentPage - 1, 'type' => $typeFilter, 'date' => $dateFilter]) }}"
                                   class="px-3 py-1.5 text-xs bg-white border border-gray-200 rounded-md hover:bg-gray-50">Previous</a>
                            @endif
                            <span class="text-xs text-gray-400">Page {{ $currentPage }} of {{ $totalPages }}</span>
                            @if($currentPage < $totalPages)
                                <a href="{{ route('activity.log', [$user, 'page' => $currentPage + 1, 'type' => $typeFilter, 'date' => $dateFilter]) }}"
                                   class="px-3 py-1.5 text-xs bg-white border border-gray-200 rounded-md hover:bg-gray-50">Next</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
