<x-app-layout>
    @push('styles')
        <style>
            body, .min-h-screen { overflow: hidden; height: 100vh; }

            /* Editor sizing */
            .EasyMDEContainer .CodeMirror {
                font-size: 13px;
                line-height: 1.6;
                font-family: Ubuntu, sans-serif;
                border-color: #e5e7eb;
            }
            .EasyMDEContainer .CodeMirror-focused { border-color: #3b82f6; }
            .EasyMDEContainer .cm-header-1 { font-size: 1.4em; font-weight: 700; }
            .EasyMDEContainer .cm-header-2 { font-size: 1.2em; font-weight: 600; }
            .EasyMDEContainer .cm-header-3 { font-size: 1.05em; font-weight: 600; }
            .EasyMDEContainer .cm-strong { font-weight: 700; }

            /* Preview styling */
            .EasyMDEContainer .editor-preview,
            .EasyMDEContainer .editor-preview-side {
                font-family: Ubuntu, sans-serif;
                font-size: 13px;
                line-height: 1.7;
                padding: 1.5rem;
                background: #fff;
            }
            .EasyMDEContainer .editor-preview h1, .EasyMDEContainer .editor-preview-side h1 { font-size: 1.6em; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.4rem; margin: 0 0 1rem; }
            .EasyMDEContainer .editor-preview h2, .EasyMDEContainer .editor-preview-side h2 { font-size: 1.3em; font-weight: 600; margin: 1.5rem 0 0.5rem; }
            .EasyMDEContainer .editor-preview h3, .EasyMDEContainer .editor-preview-side h3 { font-size: 1.1em; font-weight: 600; margin: 1.2rem 0 0.4rem; }
            .EasyMDEContainer .editor-preview table, .EasyMDEContainer .editor-preview-side table { border-collapse: collapse; width: 100%; margin: 0.75rem 0; font-size: 0.9em; }
            .EasyMDEContainer .editor-preview th, .EasyMDEContainer .editor-preview-side th { background: #f9fafb; padding: 0.4rem 0.6rem; border: 1px solid #e5e7eb; text-align: left; font-weight: 600; }
            .EasyMDEContainer .editor-preview td, .EasyMDEContainer .editor-preview-side td { padding: 0.4rem 0.6rem; border: 1px solid #e5e7eb; }
            .EasyMDEContainer .editor-preview ul, .EasyMDEContainer .editor-preview-side ul { list-style: disc; padding-left: 1.5rem; }
            .EasyMDEContainer .editor-preview ol, .EasyMDEContainer .editor-preview-side ol { list-style: decimal; padding-left: 1.5rem; }
            .EasyMDEContainer .editor-preview a, .EasyMDEContainer .editor-preview-side a { color: #2563eb; text-decoration: underline; }
            .EasyMDEContainer .editor-preview blockquote, .EasyMDEContainer .editor-preview-side blockquote { border-left: 3px solid #d1d5db; padding-left: 1rem; color: #6b7280; margin: 0.75rem 0; }

            /* Toolbar */
            .EasyMDEContainer .editor-toolbar { border-color: #e5e7eb; background: #fafafa; padding: 4px 8px; }
            .EasyMDEContainer .editor-toolbar button { color: #374151 !important; padding: 4px 6px; border-radius: 4px; }
            .EasyMDEContainer .editor-toolbar button:hover { background: #e5e7eb; }
            .EasyMDEContainer .editor-toolbar button.active { background: #dbeafe; color: #1d4ed8 !important; }
            .EasyMDEContainer .editor-toolbar i.separator { border-color: #e5e7eb; }

            /* Document link modal */
            .doc-link-dropdown { max-height: 250px; overflow-y: auto; }
            .doc-link-item:hover { background: #f3f4f6; }
            .doc-link-item.selected { background: #dbeafe; }
        </style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    @endpush

    <div x-data="documentEditor()" class="flex h-[calc(100vh-64px)] overflow-hidden">
        {{-- Document link modal --}}
        <div x-show="linkModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="linkModal = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">Link to document</h3>
                <input type="text" x-model="linkSearch" x-ref="linkSearchInput" @keydown.escape="linkModal = false"
                       placeholder="Search by ID or name..."
                       class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 mb-2">
                <div class="doc-link-dropdown border border-gray-200 rounded-md">
                    <template x-for="doc in filteredDocs" :key="doc.id">
                        <button @click="insertDocLink(doc.id)" type="button"
                                class="doc-link-item flex items-center gap-3 w-full px-3 py-2 text-left text-sm">
                            <span class="font-mono text-xs text-gray-500 shrink-0" x-text="doc.id"></span>
                            <span class="truncate" x-text="doc.title || doc.id"></span>
                            <span class="text-xs text-gray-400 shrink-0 ml-auto" x-text="doc.type"></span>
                        </button>
                    </template>
                    <div x-show="filteredDocs.length === 0" class="px-3 py-4 text-sm text-gray-400 text-center">
                        No documents found
                    </div>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <p class="text-xs text-gray-400">Inserts <code class="bg-gray-100 px-1 rounded">[[DOC-ID]]</code> at cursor</p>
                    <button type="button" @click="linkModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                </div>
            </div>
        </div>

        <main class="flex-1 overflow-y-auto bg-gray-50 min-w-0">
            <div class="max-w-5xl mx-auto py-6 px-4 sm:py-8 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('documents.index', ['path' => str_replace('.md', '', $currentPath)]) }}"
                           class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back
                        </a>
                        <span class="text-sm text-gray-400">{{ str_replace('/', ' / ', $currentPath) }}</span>
                        @if($meta['id'])
                            <span class="text-sm font-mono font-semibold text-gray-700">{{ $meta['id'] }}</span>
                        @endif
                    </div>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-4">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('documents.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="path" value="{{ $currentPath }}">

                    {{-- Metadata panel --}}
                    @if($meta['id'])
                        <div x-data="{ showMeta: false }" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 text-sm">
                                    <span class="font-mono font-semibold text-gray-800">{{ $meta['id'] }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $meta['status'] === 'draft' ? 'bg-gray-100 text-gray-600' : '' }}
                                        {{ $meta['status'] === 'in_review' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $meta['status'] === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $meta['status'] === 'obsolete' ? 'bg-red-100 text-red-600' : '' }}">
                                        {{ $statuses[$meta['status']] ?? ucfirst($meta['status']) }}
                                    </span>
                                    <span class="text-gray-400">v{{ $meta['version'] }}</span>
                                    @if($meta['author'])
                                        <span class="text-gray-400">{{ $meta['author'] }}</span>
                                    @endif
                                </div>
                                <button type="button" @click="showMeta = !showMeta" class="text-xs text-blue-600 hover:text-blue-800">
                                    <span x-text="showMeta ? 'Hide properties' : 'Edit properties'"></span>
                                </button>
                            </div>

                            <div x-show="showMeta" x-cloak class="mt-4 pt-4 border-t border-gray-100">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                        <select name="meta_status" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                            @foreach($statuses as $key => $label)
                                                <option value="{{ $key }}" {{ $meta['status'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Version</label>
                                        <input type="text" name="meta_version" value="{{ $meta['version'] }}"
                                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Effective date</label>
                                        <input type="date" name="meta_effective_date" value="{{ $meta['effective_date'] }}"
                                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Author</label>
                                        <input type="text" name="meta_author" value="{{ $meta['author'] }}"
                                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Editor --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5">
                        <textarea id="editor" name="content">{{ $content }}</textarea>
                        <div class="flex justify-end gap-2 mt-4">
                            <a href="{{ route('documents.index', ['path' => str_replace('.md', '', $currentPath)]) }}"
                               class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md border border-gray-300">Cancel</a>
                            <button type="submit"
                                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
        <script>
            function documentEditor() {
                return {
                    linkModal: false,
                    linkSearch: '',
                    docs: @json($docList),
                    editor: null,

                    get filteredDocs() {
                        if (!this.linkSearch) return this.docs;
                        const q = this.linkSearch.toLowerCase();
                        return this.docs.filter(d =>
                            d.id.toLowerCase().includes(q) ||
                            (d.title && d.title.toLowerCase().includes(q)) ||
                            (d.type && d.type.toLowerCase().includes(q))
                        );
                    },

                    insertDocLink(docId) {
                        this.linkModal = false;
                        this.linkSearch = '';
                        if (this.editor) {
                            const cm = this.editor.codemirror;
                            const pos = cm.getCursor();
                            cm.replaceRange('[[' + docId + ']]', pos);
                            cm.focus();
                        }
                    },

                    init() {
                        const self = this;
                        this.editor = new EasyMDE({
                            element: document.getElementById('editor'),
                            spellChecker: false,
                            autosave: {
                                enabled: true,
                                uniqueId: '{{ $currentPath }}',
                                delay: 5000,
                            },
                            toolbar: [
                                {
                                    name: 'heading-1',
                                    action: EasyMDE.toggleHeading1,
                                    className: 'fa fa-header',
                                    title: 'Heading 1',
                                    text: 'H1',
                                },
                                {
                                    name: 'heading-2',
                                    action: EasyMDE.toggleHeading2,
                                    className: 'fa fa-header',
                                    title: 'Heading 2',
                                    text: 'H2',
                                },
                                {
                                    name: 'heading-3',
                                    action: EasyMDE.toggleHeading3,
                                    className: 'fa fa-header',
                                    title: 'Heading 3',
                                    text: 'H3',
                                },
                                '|',
                                {
                                    name: 'bold',
                                    action: EasyMDE.toggleBold,
                                    className: 'fa fa-bold',
                                    title: 'Bold',
                                },
                                {
                                    name: 'italic',
                                    action: EasyMDE.toggleItalic,
                                    className: 'fa fa-italic',
                                    title: 'Italic',
                                },
                                {
                                    name: 'strikethrough',
                                    action: EasyMDE.toggleStrikethrough,
                                    className: 'fa fa-strikethrough',
                                    title: 'Strikethrough',
                                },
                                '|',
                                {
                                    name: 'unordered-list',
                                    action: EasyMDE.toggleUnorderedList,
                                    className: 'fa fa-list-ul',
                                    title: 'Bullet list',
                                },
                                {
                                    name: 'ordered-list',
                                    action: EasyMDE.toggleOrderedList,
                                    className: 'fa fa-list-ol',
                                    title: 'Numbered list',
                                },
                                {
                                    name: 'table',
                                    action: EasyMDE.drawTable,
                                    className: 'fa fa-table',
                                    title: 'Insert table',
                                },
                                '|',
                                {
                                    name: 'link',
                                    action: EasyMDE.drawLink,
                                    className: 'fa fa-link',
                                    title: 'Insert link',
                                },
                                {
                                    name: 'doc-link',
                                    action: () => {
                                        self.linkModal = true;
                                        self.linkSearch = '';
                                        self.$nextTick(() => self.$refs.linkSearchInput.focus());
                                    },
                                    className: 'fa fa-file-text-o',
                                    title: 'Link to QMS document',
                                    text: '📄',
                                },
                                '|',
                                {
                                    name: 'quote',
                                    action: EasyMDE.toggleBlockquote,
                                    className: 'fa fa-quote-left',
                                    title: 'Quote',
                                },
                                {
                                    name: 'horizontal-rule',
                                    action: EasyMDE.drawHorizontalRule,
                                    className: 'fa fa-minus',
                                    title: 'Horizontal line',
                                },
                                '|',
                                {
                                    name: 'preview',
                                    action: EasyMDE.togglePreview,
                                    className: 'fa fa-eye no-disable',
                                    title: 'Preview',
                                },
                                {
                                    name: 'side-by-side',
                                    action: EasyMDE.toggleSideBySide,
                                    className: 'fa fa-columns no-disable',
                                    title: 'Side by side',
                                },
                                {
                                    name: 'fullscreen',
                                    action: EasyMDE.toggleFullScreen,
                                    className: 'fa fa-arrows-alt no-disable',
                                    title: 'Fullscreen',
                                },
                            ],
                            minHeight: '400px',
                            placeholder: 'Start writing...',
                            status: ['lines', 'words'],
                        });
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
