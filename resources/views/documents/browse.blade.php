<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Browse Documents</h2>
                <span class="text-sm text-gray-400">{{ $totalDocs }} documents</span>
            </div>
        </div>
    </x-slot>

    <div x-data="documentBrowser()" class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Search --}}
            <div class="mb-6">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search" x-ref="searchInput"
                           placeholder="Search by ID, title, type, or author..."
                           class="w-full pl-10 pr-4 py-2.5 border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                    <button x-show="search" @click="search = ''; $refs.searchInput.focus()" x-cloak
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center gap-2 mt-2" x-show="search" x-cloak>
                    <span class="text-xs text-gray-400" x-text="filteredCount + ' result' + (filteredCount !== 1 ? 's' : '')"></span>
                </div>
            </div>

            {{-- Type filter pills --}}
            <div class="flex flex-wrap gap-2 mb-6">
                <button @click="typeFilter = ''" class="px-3 py-1 text-xs rounded-full transition-colors"
                        :class="typeFilter === '' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                    All
                </button>
                @foreach(\App\Services\DocumentMetadata::TYPES as $key => $label)
                    @if(collect($documents)->where('type', $key)->count() > 0)
                        <button @click="typeFilter = typeFilter === '{{ $key }}' ? '' : '{{ $key }}'"
                                class="px-3 py-1 text-xs rounded-full transition-colors"
                                :class="typeFilter === '{{ $key }}' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                            {{ $key }} <span class="opacity-60">({{ collect($documents)->where('type', $key)->count() }})</span>
                        </button>
                    @endif
                @endforeach
            </div>

            {{-- Documents grouped by directory --}}
            @php
                $grouped = collect($documents)->groupBy('raw_directory');
            @endphp

            @foreach($grouped as $dir => $docs)
                <div class="mb-6" x-show="hasVisibleDocs('{{ addslashes($dir) }}')" x-cloak>
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-500">{{ $dir ? ucwords(str_replace(['-', '_', '/'], [' ', ' ', ' / '], $dir)) : 'Root' }}</span>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        @foreach($docs as $doc)
                            <a href="{{ route('documents.index', ['path' => $doc['url_path']]) }}"
                               x-show="isVisible(@js($doc))"
                               class="flex items-center gap-4 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                {{-- Doc ID --}}
                                <div class="w-20 shrink-0">
                                    @if($doc['doc_id'])
                                        <span class="font-mono text-xs text-gray-500">{{ $doc['doc_id'] }}</span>
                                    @endif
                                </div>

                                {{-- Title --}}
                                <div class="flex-1 min-w-0">
                                    <span class="text-sm text-gray-800">{{ $doc['title'] }}</span>
                                </div>

                                {{-- Type badge --}}
                                <div class="shrink-0">
                                    @if($doc['type'])
                                        <span class="text-[11px] text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded">{{ $doc['type'] }}</span>
                                    @endif
                                </div>

                                {{-- Status --}}
                                <div class="w-20 shrink-0">
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-medium
                                        {{ $doc['status'] === 'draft' ? 'bg-gray-100 text-gray-500' : '' }}
                                        {{ $doc['status'] === 'in_review' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $doc['status'] === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $doc['status'] === 'obsolete' ? 'bg-red-100 text-red-600' : '' }}">
                                        {{ $doc['status_label'] }}
                                    </span>
                                </div>

                                {{-- Version --}}
                                <div class="w-10 shrink-0 text-xs text-gray-400 text-right">
                                    @if($doc['version'])
                                        v{{ $doc['version'] }}
                                    @endif
                                </div>

                                {{-- Arrow --}}
                                <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- No results --}}
            <div x-show="filteredCount === 0" x-cloak class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                <p class="text-gray-400">No documents match your search.</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function documentBrowser() {
                const docs = @json($documents);
                return {
                    search: '',
                    typeFilter: '',
                    docs: docs,

                    get filteredCount() {
                        return this.docs.filter(d => this.isVisible(d)).length;
                    },

                    isVisible(doc) {
                        if (this.typeFilter && doc.type !== this.typeFilter) return false;
                        if (!this.search) return true;
                        const q = this.search.toLowerCase();
                        return (doc.doc_id && doc.doc_id.toLowerCase().includes(q)) ||
                               (doc.title && doc.title.toLowerCase().includes(q)) ||
                               (doc.type && doc.type.toLowerCase().includes(q)) ||
                               (doc.type_label && doc.type_label.toLowerCase().includes(q)) ||
                               (doc.author && doc.author.toLowerCase().includes(q)) ||
                               (doc.directory && doc.directory.toLowerCase().includes(q));
                    },

                    hasVisibleDocs(dir) {
                        return this.docs.some(d => d.raw_directory === dir && this.isVisible(d));
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
