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
            {{-- Search bar --}}
            <div class="relative mb-6">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" x-model="search" x-ref="searchInput"
                       placeholder="Search by ID, title, type, or author..."
                       @keydown.slash.window.prevent="$refs.searchInput.focus()"
                       class="w-full pl-11 pr-10 py-3 border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                <button x-show="search" @click="search = ''; $refs.searchInput.focus()" x-cloak
                        class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Search results --}}
            <div x-show="search" x-cloak>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-sm text-gray-500" x-text="filteredDocs.length + ' result' + (filteredDocs.length !== 1 ? 's' : '')"></span>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" x-show="filteredDocs.length > 0">
                    <template x-for="doc in filteredDocs" :key="doc.path">
                        <a :href="'/qms/' + doc.url_path" class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                            <span class="font-mono text-xs text-gray-400 w-20 shrink-0" x-text="doc.doc_id"></span>
                            <div class="flex-1 min-w-0">
                                <span class="text-sm text-gray-800 block" x-text="doc.title"></span>
                                <span class="text-[11px] text-gray-400" x-text="doc.directory"></span>
                            </div>
                            <span class="text-[11px] text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded shrink-0" x-text="doc.type"></span>
                            <span class="shrink-0 text-[11px] font-medium px-1.5 py-0.5 rounded"
                                  :class="{
                                      'bg-gray-100 text-gray-500': doc.status === 'draft',
                                      'bg-yellow-100 text-yellow-700': doc.status === 'in_review',
                                      'bg-green-100 text-green-700': doc.status === 'approved',
                                      'bg-red-100 text-red-600': doc.status === 'obsolete',
                                  }" x-text="doc.status_label"></span>
                            <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </template>
                </div>

                <div x-show="filteredDocs.length === 0" class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <p class="text-gray-400">No documents match your search.</p>
                </div>
            </div>

            {{-- Directory tree (shown when not searching) --}}
            <div x-show="!search">
                {{-- Root files --}}
                @php $rootDocs = $grouped[''] ?? collect(); @endphp
                @if($rootDocs->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                        @foreach($rootDocs as $doc)
                            <a href="{{ route('documents.index', ['path' => $doc['url_path']]) }}"
                               class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span class="font-mono text-xs text-gray-400 w-20 shrink-0">{{ $doc['doc_id'] }}</span>
                                <span class="text-sm text-gray-800 flex-1 min-w-0">{{ $doc['title'] }}</span>
                                @if($doc['type'])
                                    <span class="text-[11px] text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded shrink-0">{{ $doc['type'] }}</span>
                                @endif
                                <span class="shrink-0 text-[11px] font-medium px-1.5 py-0.5 rounded
                                    {{ $doc['status'] === 'draft' ? 'bg-gray-100 text-gray-500' : '' }}
                                    {{ $doc['status'] === 'in_review' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $doc['status'] === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $doc['status'] === 'obsolete' ? 'bg-red-100 text-red-600' : '' }}">
                                    {{ $doc['status_label'] }}
                                </span>
                                @if($doc['version'])
                                    <span class="text-xs text-gray-400 w-10 text-right shrink-0">v{{ $doc['version'] }}</span>
                                @endif
                                <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Directories --}}
                @foreach($grouped as $dir => $docs)
                    @if($dir !== '')
                        <div x-data="{ open: true }" class="mb-6">
                            {{-- Directory header --}}
                            <button @click="open = !open" class="flex items-center gap-2 mb-2 group w-full text-left">
                                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">
                                    {{ ucwords(str_replace(['-', '_', '/'], [' ', ' ', ' / '], $dir)) }}
                                </span>
                                <span class="text-xs text-gray-400">{{ count($docs) }}</span>
                            </button>

                            {{-- Files in directory --}}
                            <div x-show="open" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden ml-6">
                                @foreach($docs as $doc)
                                    <a href="{{ route('documents.index', ['path' => $doc['url_path']]) }}"
                                       class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span class="font-mono text-xs text-gray-400 w-20 shrink-0">{{ $doc['doc_id'] }}</span>
                                        <span class="text-sm text-gray-800 flex-1 min-w-0">{{ $doc['title'] }}</span>
                                        @if($doc['type'])
                                            <span class="text-[11px] text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded shrink-0">{{ $doc['type'] }}</span>
                                        @endif
                                        <span class="shrink-0 text-[11px] font-medium px-1.5 py-0.5 rounded
                                            {{ $doc['status'] === 'draft' ? 'bg-gray-100 text-gray-500' : '' }}
                                            {{ $doc['status'] === 'in_review' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $doc['status'] === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $doc['status'] === 'obsolete' ? 'bg-red-100 text-red-600' : '' }}">
                                            {{ $doc['status_label'] }}
                                        </span>
                                        @if($doc['version'])
                                            <span class="text-xs text-gray-400 w-10 text-right shrink-0">v{{ $doc['version'] }}</span>
                                        @endif
                                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function documentBrowser() {
                const docs = @json($documents);
                return {
                    search: '',
                    docs: docs,

                    get filteredDocs() {
                        if (!this.search) return this.docs;
                        const q = this.search.toLowerCase();
                        return this.docs.filter(d =>
                            (d.doc_id && d.doc_id.toLowerCase().includes(q)) ||
                            (d.title && d.title.toLowerCase().includes(q)) ||
                            (d.type && d.type.toLowerCase().includes(q)) ||
                            (d.type_label && d.type_label.toLowerCase().includes(q)) ||
                            (d.author && d.author.toLowerCase().includes(q)) ||
                            (d.directory && d.directory.toLowerCase().includes(q))
                        );
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
