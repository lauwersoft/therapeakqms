<x-app-layout>
    @section('page-title', $user->name . ' — Full Log')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Activity Log: {{ $user->name }}</h2>
            <a href="{{ route('activity.show', $user) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to overview
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Filters --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    @foreach(['' => 'All', 'page_view' => 'Page views', 'comment' => 'Comments', 'reply' => 'Replies', 'resolve_comment' => 'Resolved', 'login' => 'Logins', 'download' => 'Downloads', 'publish' => 'Published'] as $key => $label)
                        <a href="{{ route('activity.log', [$user, 'type' => $key]) }}"
                           class="px-2.5 py-1 text-xs rounded-full {{ $typeFilter === $key ? 'bg-gray-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
                <span class="text-xs text-gray-400">{{ number_format($total) }} {{ Str::plural('entry', $total) }}</span>
            </div>

            {{-- Activity table --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                @if($activities->isEmpty())
                    <div class="px-5 py-12 text-center text-sm text-gray-400">No activity found</div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($activities as $activity)
                            <div class="px-5 py-3 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start gap-3">
                                    <span class="text-[10px] text-gray-300 shrink-0 w-24 text-right mt-0.5">{{ $activity->created_at->format('M j, H:i:s') }}</span>
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
                                            default => ['bg-gray-50 text-gray-400', 'Viewed'],
                                        };
                                    @endphp
                                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded shrink-0 w-16 text-center {{ $typeConfig[0] }}">{{ $typeConfig[1] }}</span>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            @if($activity->doc_id && $activity->doc_id !== 'null')
                                                <span class="text-[10px] font-mono font-semibold px-1 py-0.5 rounded shrink-0 {{ \App\Services\DocumentMetadata::typeColor(explode('-', $activity->doc_id)[0] ?? '') }}">{{ $activity->doc_id }}</span>
                                            @endif
                                            <span class="text-sm text-gray-700 truncate">{{ ($activity->doc_title && $activity->doc_title !== 'null') ? $activity->doc_title : ($activity->page_title ?: $activity->path) }}</span>
                                        </div>
                                        @if($activity->detail)
                                            <p class="text-xs text-gray-400 mt-0.5 line-clamp-2">{{ $activity->detail }}</p>
                                        @endif
                                        <div class="flex items-center gap-3 mt-1 text-[10px] text-gray-300">
                                            @if($activity->type === 'page_view')
                                                <span>{{ $activity->time_spent }}s</span>
                                                @if($activity->scroll_depth !== null)
                                                    <span class="{{ $activity->scroll_depth >= 90 ? 'text-green-600' : ($activity->scroll_depth >= 50 ? 'text-amber-600' : 'text-red-500') }}">{{ $activity->scroll_depth }}% read</span>
                                                @endif
                                            @endif
                                            @if($activity->device)<span>{{ $activity->device }}</span>@endif
                                            @if($activity->ip)<span class="font-mono">{{ $activity->ip }}</span>@endif
                                            @if($activity->country_code)<span class="px-1 py-0.5 rounded bg-blue-50 text-blue-600">{{ $activity->country_code }}</span>@endif
                                            @if($activity->session_uid)
                                                <a href="{{ route('activity.session', [$user, $activity->session_uid]) }}" class="text-blue-500 hover:text-blue-700">Session</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Pagination --}}
            @if($totalPages > 1)
                <div class="flex items-center justify-center gap-2 mt-6">
                    @if($currentPage > 1)
                        <a href="{{ route('activity.log', [$user, 'page' => $currentPage - 1, 'type' => $typeFilter]) }}"
                           class="px-3 py-1.5 text-xs bg-white border border-gray-200 rounded-md hover:bg-gray-50">Previous</a>
                    @endif
                    <span class="text-xs text-gray-400">Page {{ $currentPage }} of {{ $totalPages }}</span>
                    @if($currentPage < $totalPages)
                        <a href="{{ route('activity.log', [$user, 'page' => $currentPage + 1, 'type' => $typeFilter]) }}"
                           class="px-3 py-1.5 text-xs bg-white border border-gray-200 rounded-md hover:bg-gray-50">Next</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
