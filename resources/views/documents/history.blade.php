<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Document History</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $totalCommits }} {{ Str::plural('revision', $totalCommits) }} tracked</p>
            </div>
            <a href="{{ route('documents.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to Documents</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(empty($commits))
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500">No document history yet.</p>
                </div>
            @else
                {{-- Timeline --}}
                <div class="relative">
                    {{-- Timeline line --}}
                    <div class="absolute left-6 top-0 bottom-0 w-px bg-gray-200"></div>

                    @php $lastDate = null; @endphp
                    @foreach($commits as $commit)
                        {{-- Date separator --}}
                        @if($lastDate !== $commit['date']->format('Y-m-d'))
                            @php $lastDate = $commit['date']->format('Y-m-d'); @endphp
                            <div class="relative flex items-center mb-4 {{ !$loop->first ? 'mt-8' : '' }}">
                                <div class="w-12 flex justify-center">
                                    <div class="w-3 h-3 bg-gray-300 rounded-full ring-4 ring-white z-10"></div>
                                </div>
                                <span class="ml-4 text-sm font-semibold text-gray-500">{{ $commit['date']->format('F j, Y') }}</span>
                            </div>
                        @endif

                        {{-- Commit card --}}
                        <div x-data="{ open: false }" class="relative flex mb-3">
                            {{-- Timeline dot --}}
                            <div class="w-12 flex justify-center pt-4 shrink-0">
                                <div class="w-2 h-2 bg-blue-400 rounded-full ring-4 ring-white z-10"></div>
                            </div>

                            {{-- Card --}}
                            <div class="flex-1 ml-2 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                <div class="px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors" @click="open = !open">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-sm text-gray-800 font-medium leading-snug">{{ Str::before($commit['message'], "\n") }}</p>
                                            <div class="flex items-center gap-2 mt-1.5">
                                                <div class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                                                    <span class="text-[10px] font-semibold text-blue-600">{{ strtoupper(substr($commit['author'], 0, 1)) }}</span>
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $commit['author'] }}</span>
                                                <span class="text-xs text-gray-400">{{ $commit['date']->format('H:i') }}</span>
                                                <span class="text-xs font-mono text-gray-400">{{ $commit['short_hash'] }}</span>
                                                @if(count($commit['files']) > 0)
                                                    <span class="text-xs text-gray-400">· {{ count($commit['files']) }} {{ Str::plural('file', count($commit['files'])) }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-400 shrink-0 mt-1 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </div>
                                </div>

                                {{-- Expanded details --}}
                                <div x-show="open" x-cloak class="border-t border-gray-100">
                                    {{-- Full commit message if multiline --}}
                                    @if(str_contains($commit['message'], "\n"))
                                        <div class="px-4 py-3 bg-gray-50 text-xs text-gray-600 whitespace-pre-line border-b border-gray-100">{{ $commit['message'] }}</div>
                                    @endif

                                    {{-- Changed files --}}
                                    @if(count($commit['files']) > 0)
                                        <div class="divide-y divide-gray-50">
                                            @foreach($commit['files'] as $file)
                                                <div class="px-4 py-2 flex items-center gap-2 text-sm">
                                                    @if($file['status'] === 'added')
                                                        <span class="w-5 h-5 rounded flex items-center justify-center bg-green-100 text-green-600 text-xs font-bold shrink-0">+</span>
                                                    @elseif($file['status'] === 'modified')
                                                        <span class="w-5 h-5 rounded flex items-center justify-center bg-amber-100 text-amber-600 text-xs font-bold shrink-0">~</span>
                                                    @elseif($file['status'] === 'deleted')
                                                        <span class="w-5 h-5 rounded flex items-center justify-center bg-red-100 text-red-600 text-xs font-bold shrink-0">−</span>
                                                    @else
                                                        <span class="w-5 h-5 rounded flex items-center justify-center bg-gray-100 text-gray-500 text-xs font-bold shrink-0">?</span>
                                                    @endif
                                                    @if($file['status'] !== 'deleted')
                                                        <a href="{{ route('documents.index', ['path' => str_replace('.md', '', $file['path'])]) }}"
                                                           class="font-mono text-xs text-blue-600 hover:text-blue-800 hover:underline truncate">
                                                            {{ $file['path'] }}
                                                        </a>
                                                    @else
                                                        <span class="font-mono text-xs text-gray-500 line-through truncate">{{ $file['path'] }}</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

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
