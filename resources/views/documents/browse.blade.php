<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Browse Documents</h2>
                <span class="text-sm text-gray-400" x-data x-text="''" id="browse-count"></span>
            </div>
            @if(in_array(Auth::user()->role, ['admin', 'editor']))
                <div class="flex items-center gap-2">
                    <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm text-gray-600 hover:bg-gray-50">
                        <svg class="w-3.5 h-3.5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        New form
                    </a>
                    <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        New document
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div x-data="documentBrowser()" class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Search + filters --}}
            <div class="mb-5">
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="search" x-ref="searchInput"
                           placeholder="Search by ID, title, type, or author..."
                           @keydown.slash.window.prevent="$refs.searchInput.focus()"
                           class="w-full pl-11 pr-10 py-2.5 border-gray-200 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                    <button x-show="search" @click="search = ''; $refs.searchInput.focus()" x-cloak
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Filter bar --}}
            <div class="flex flex-wrap items-center gap-2 mb-5">
                {{-- Type filters --}}
                <div class="flex flex-wrap gap-1.5">
                    <button @click="typeFilter = ''" class="px-2.5 py-1 text-xs rounded-full transition-colors"
                            :class="typeFilter === '' ? 'bg-gray-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'">
                        All types
                    </button>
                    @foreach(\App\Services\DocumentMetadata::TYPES as $key => $label)
                        @if(collect($documents)->where('type', $key)->count() > 0)
                            <button @click="typeFilter = typeFilter === '{{ $key }}' ? '' : '{{ $key }}'"
                                    class="px-2.5 py-1 text-xs rounded-full transition-colors"
                                    :class="typeFilter === '{{ $key }}' ? 'bg-gray-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'">
                                {{ $key }} <span class="opacity-60">({{ collect($documents)->where('type', $key)->count() }})</span>
                            </button>
                        @endif
                    @endforeach
                </div>

                <div class="w-px h-5 bg-gray-200"></div>

                {{-- Status filters --}}
                <div class="flex flex-wrap gap-1.5">
                    <button @click="statusFilter = ''" class="px-2.5 py-1 text-xs rounded-full transition-colors"
                            :class="statusFilter === '' ? 'bg-gray-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'">
                        All statuses
                    </button>
                    @foreach(\App\Services\DocumentMetadata::STATUSES as $key => $label)
                        @if(collect($documents)->where('status', $key)->count() > 0)
                            <button @click="statusFilter = statusFilter === '{{ $key }}' ? '' : '{{ $key }}'"
                                    class="px-2.5 py-1 text-xs rounded-full transition-colors"
                                    :class="statusFilter === '{{ $key }}' ? 'bg-gray-800 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50'">
                                {{ $label }} <span class="opacity-60">({{ collect($documents)->where('status', $key)->count() }})</span>
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Results count --}}
            <div class="flex items-center justify-between mb-4">
                <span class="text-xs text-gray-400" x-text="filteredDocs.length + ' of ' + docs.length + ' documents'"></span>
                <button x-show="typeFilter || statusFilter || search" x-cloak
                        @click="typeFilter = ''; statusFilter = ''; search = ''"
                        class="text-xs text-blue-600 hover:text-blue-800">Clear all filters</button>
            </div>

            {{-- Document list --}}
            <template x-if="filteredDocs.length > 0">
                <div>
                    {{-- Group by directory --}}
                    <template x-for="dir in uniqueDirs" :key="dir">
                        <div x-show="filteredDocs.some(d => d.raw_directory === dir)" class="mb-5">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-500" x-text="dir ? dir.replace(/[-_]/g, ' ').replace(/\//g, ' / ').replace(/\b\w/g, l => l.toUpperCase()) : 'Root'"></span>
                            </div>
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <template x-for="doc in filteredDocs.filter(d => d.raw_directory === dir)" :key="doc.path">
                                    <a :href="'/qms/' + doc.url_path"
                                       class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                        <span class="font-mono text-xs text-gray-400 w-20 shrink-0" x-text="doc.doc_id"></span>
                                        <div class="flex-1 min-w-0">
                                            <span class="text-sm text-gray-800 block truncate" x-text="doc.title"></span>
                                        </div>
                                        <span x-show="doc.type" class="text-[11px] text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded shrink-0" x-text="doc.type"></span>
                                        <span class="shrink-0 text-[11px] font-medium px-1.5 py-0.5 rounded"
                                              :class="{
                                                  'bg-gray-100 text-gray-500': doc.status === 'draft',
                                                  'bg-yellow-100 text-yellow-700': doc.status === 'in_review',
                                                  'bg-green-100 text-green-700': doc.status === 'approved',
                                                  'bg-red-100 text-red-600': doc.status === 'obsolete',
                                              }" x-text="doc.status_label"></span>
                                        <span x-show="doc.version" class="text-xs text-gray-400 w-10 text-right shrink-0" x-text="'v' + doc.version"></span>
                                        <span x-show="doc.author" class="text-xs text-gray-400 shrink-0 hidden sm:inline" x-text="doc.author"></span>
                                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            {{-- No results --}}
            <div x-show="filteredDocs.length === 0" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <p class="text-gray-400">No documents match your filters.</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function documentBrowser() {
                const docs = @json($documents);
                const dirs = [...new Set(docs.map(d => d.raw_directory))].sort();

                return {
                    search: '',
                    typeFilter: '',
                    statusFilter: '',
                    docs: docs,
                    uniqueDirs: dirs,

                    get filteredDocs() {
                        return this.docs.filter(d => {
                            if (this.typeFilter && d.type !== this.typeFilter) return false;
                            if (this.statusFilter && d.status !== this.statusFilter) return false;
                            if (this.search) {
                                const q = this.search.toLowerCase();
                                return (d.doc_id && d.doc_id.toLowerCase().includes(q)) ||
                                       (d.title && d.title.toLowerCase().includes(q)) ||
                                       (d.type && d.type.toLowerCase().includes(q)) ||
                                       (d.type_label && d.type_label.toLowerCase().includes(q)) ||
                                       (d.author && d.author.toLowerCase().includes(q)) ||
                                       (d.directory && d.directory.toLowerCase().includes(q));
                            }
                            return true;
                        });
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
