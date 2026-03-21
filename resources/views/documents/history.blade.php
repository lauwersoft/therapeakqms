<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Change History</h2>
                <span class="text-sm text-gray-400">{{ $totalCommits }} {{ Str::plural('revision', $totalCommits) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(empty($commits))
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500">No document revisions yet.</p>
                </div>
            @else
                @php $lastDate = null; @endphp
                @foreach($commits as $commit)
                    {{-- Date separator --}}
                    @if($lastDate !== $commit['date']->format('Y-m-d'))
                        @php $lastDate = $commit['date']->format('Y-m-d'); @endphp
                        <div class="flex items-center gap-3 {{ !$loop->first ? 'mt-6' : '' }} mb-3">
                            <div class="h-px flex-1 bg-gray-200"></div>
                            <span class="text-xs font-medium text-gray-400 shrink-0">{{ $commit['date']->format('F j, Y') }}</span>
                            <div class="h-px flex-1 bg-gray-200"></div>
                        </div>
                    @endif

                    {{-- Revision card --}}
                    <div x-data="{ open: false }" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-3 overflow-hidden">
                        <div class="px-5 py-4 cursor-pointer hover:bg-gray-50/50 transition-colors" @click="open = !open">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    {{-- Revision description --}}
                                    <p class="text-sm text-gray-800 leading-snug">{{ Str::before($commit['message'], "\n") }}</p>

                                    {{-- Affected documents preview --}}
                                    <div class="space-y-1 mt-2">
                                        @foreach($commit['files'] as $file)
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full shrink-0
                                                    {{ $file['status'] === 'added' ? 'bg-green-50 text-green-700' : '' }}
                                                    {{ $file['status'] === 'modified' ? 'bg-blue-50 text-blue-700' : '' }}
                                                    {{ $file['status'] === 'deleted' ? 'bg-red-50 text-red-600' : '' }}">
                                                    @if($file['doc_id'])
                                                        <span class="font-mono font-medium">{{ $file['doc_id'] }}</span>
                                                    @else
                                                        <span>{{ $file['doc_title'] }}</span>
                                                    @endif
                                                    <span class="text-[10px] opacity-70">
                                                        {{ $file['status'] === 'added' ? 'created' : ($file['status'] === 'deleted' ? 'removed' : 'updated') }}
                                                    </span>
                                                </span>
                                                @if($file['doc_type'] ?? null)
                                                    <span class="text-[10px] px-1 py-0.5 rounded font-medium shrink-0 {{ \App\Services\DocumentMetadata::typeColor($file['doc_type']) }}">{{ $file['doc_type'] }}</span>
                                                @endif
                                                <span class="text-[10px] text-gray-400 font-mono truncate">/{{ $file['path'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 shrink-0 text-xs text-gray-400">
                                    <span>{{ $commit['date']->format('H:i') }}</span>
                                    <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>

                            {{-- Author + meta --}}
                            <div class="flex items-center gap-2 mt-2">
                                <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center shrink-0">
                                    <span class="text-[10px] font-semibold text-gray-500">{{ strtoupper(substr($commit['author'], 0, 1)) }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $commit['author'] }}</span>
                                <span class="text-xs text-gray-300">·</span>
                                <span class="text-xs text-gray-400">{{ $commit['date']->diffForHumans() }}</span>
                            </div>
                        </div>

                        {{-- Expanded details --}}
                        <div x-show="open" x-cloak class="border-t border-gray-100">
                            {{-- Full description if multiline --}}
                            @if(str_contains($commit['message'], "\n"))
                                @php
                                    $fullMessage = Str::after($commit['message'], "\n");
                                    $fullMessage = trim($fullMessage);
                                @endphp
                                @if($fullMessage)
                                    <div class="px-5 py-3 bg-gray-50 text-xs text-gray-600 whitespace-pre-line border-b border-gray-100">{{ $fullMessage }}</div>
                                @endif
                            @endif

                            {{-- Affected documents --}}
                            @if(count($commit['files']) > 0)
                                <div class="px-5 py-3">
                                    <div class="text-xs font-medium text-gray-400 mb-2">Affected documents</div>
                                    <div class="space-y-1.5">
                                        @foreach($commit['files'] as $file)
                                            @php
                                                $dir = dirname($file['path']);
                                                $dirLabel = ($dir !== '.' && $dir !== '') ? ucwords(str_replace(['-', '_', '/'], [' ', ' ', ' / '], $dir)) : null;
                                            @endphp
                                            <div class="flex items-center gap-3">
                                                @if($file['status'] === 'added')
                                                    <span class="w-16 text-xs font-medium text-green-600 shrink-0">Created</span>
                                                @elseif($file['status'] === 'modified')
                                                    <span class="w-16 text-xs font-medium text-blue-600 shrink-0">Updated</span>
                                                @elseif($file['status'] === 'deleted')
                                                    <span class="w-16 text-xs font-medium text-red-600 shrink-0">Removed</span>
                                                @else
                                                    <span class="w-16 text-xs font-medium text-gray-500 shrink-0">Changed</span>
                                                @endif

                                                <div class="min-w-0">
                                                    @if($file['status'] !== 'deleted')
                                                        <a href="{{ route('documents.index', ['path' => preg_replace('/\.md$/', '', $file['path'])]) }}"
                                                           class="text-sm text-gray-700 hover:text-blue-600 hover:underline">
                                                            @if($file['doc_id'])
                                                                <span class="font-mono text-xs text-gray-400 mr-1">{{ $file['doc_id'] }}</span>
                                                            @endif
                                                            @if($dirLabel)
                                                                <span class="text-gray-400">{{ $dirLabel }} /</span>
                                                            @endif
                                                            {{ $file['doc_title'] }}
                                                        </a>
                                                    @else
                                                        <span class="text-sm text-gray-400 inline-flex items-center gap-1.5">
                                                            @if($file['doc_id'])
                                                                <span class="text-xs">{{ $file['doc_id'] }}</span>
                                                            @endif
                                                            <span class="line-through">@if($dirLabel){{ $dirLabel }} / @endif{{ $file['doc_title'] }}</span>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Revision reference --}}
                            <div class="px-5 py-2 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                                <span class="text-[11px] text-gray-400">Revision {{ $commit['short_hash'] }}</span>
                                <a href="{{ route('documents.revision', $commit['hash']) }}"
                                   class="text-[11px] text-blue-500 hover:text-blue-700">View changes</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Pagination --}}
                @if($totalPages > 1)
                    <div class="flex items-center justify-center gap-2 mt-8">
                        @if($currentPage > 1)
                            <a href="{{ route('documents.history', ['page' => $currentPage - 1]) }}"
                               class="px-3 py-1.5 text-sm bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                                Previous
                            </a>
                        @endif
                        <span class="text-sm text-gray-500">Page {{ $currentPage }} of {{ $totalPages }}</span>
                        @if($currentPage < $totalPages)
                            <a href="{{ route('documents.history', ['page' => $currentPage + 1]) }}"
                               class="px-3 py-1.5 text-sm bg-white border border-gray-300 rounded-md text-gray-600 hover:bg-gray-50">
                                Next
                            </a>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
