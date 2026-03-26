<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
        <style>
            /* Editor sizing — must override EasyMDE defaults */
            .EasyMDEContainer .CodeMirror {
                font-size: 12.5px !important;
                line-height: 1.55 !important;
                font-family: Ubuntu, sans-serif !important;
                border-color: #e5e7eb !important;
            }
            .EasyMDEContainer .CodeMirror-focused { border-color: #3b82f6 !important; }
            .EasyMDEContainer .cm-header-1 { font-size: 1.15em !important; font-weight: 700 !important; color: #111827 !important; }
            .EasyMDEContainer .cm-header-2 { font-size: 1.08em !important; font-weight: 600 !important; color: #1f2937 !important; }
            .EasyMDEContainer .cm-header-3 { font-size: 1em !important; font-weight: 600 !important; color: #374151 !important; }
            .EasyMDEContainer .cm-strong { font-weight: 700 !important; }
            .EasyMDEContainer .cm-comment { font-family: ui-monospace, SFMono-Regular, Menlo, monospace !important; font-size: 0.9em !important; background: #f3f4f6 !important; border-radius: 3px; padding: 0 3px; }

            /* Preview styling */
            .EasyMDEContainer .editor-preview,
            .EasyMDEContainer .editor-preview-side {
                font-family: Ubuntu, sans-serif !important;
                font-size: 13px !important;
                line-height: 1.7 !important;
                padding: 1.5rem !important;
                background: #fff !important;
            }
            .EasyMDEContainer .editor-preview h1, .EasyMDEContainer .editor-preview-side h1 { font-size: 1.35em !important; font-weight: 700 !important; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.4rem; margin: 0 0 0.8rem !important; }
            .EasyMDEContainer .editor-preview h2, .EasyMDEContainer .editor-preview-side h2 { font-size: 1.15em !important; font-weight: 600 !important; margin: 1.2rem 0 0.4rem !important; }
            .EasyMDEContainer .editor-preview h3, .EasyMDEContainer .editor-preview-side h3 { font-size: 1.05em !important; font-weight: 600 !important; margin: 1rem 0 0.3rem !important; }
            .EasyMDEContainer .editor-preview table, .EasyMDEContainer .editor-preview-side table { border-collapse: collapse; width: 100%; margin: 0.75rem 0; font-size: 0.9em; }
            .EasyMDEContainer .editor-preview th, .EasyMDEContainer .editor-preview-side th { background: #f9fafb; padding: 0.4rem 0.6rem; border: 1px solid #e5e7eb; text-align: left; font-weight: 600; }
            .EasyMDEContainer .editor-preview td, .EasyMDEContainer .editor-preview-side td { padding: 0.4rem 0.6rem; border: 1px solid #e5e7eb; }
            .EasyMDEContainer .editor-preview ul, .EasyMDEContainer .editor-preview-side ul { list-style: disc !important; padding-left: 1.5rem !important; }
            .EasyMDEContainer .editor-preview ol, .EasyMDEContainer .editor-preview-side ol { list-style: decimal !important; padding-left: 1.5rem !important; }
            .EasyMDEContainer .editor-preview a, .EasyMDEContainer .editor-preview-side a { color: #2563eb; text-decoration: underline; }
            .EasyMDEContainer .editor-preview blockquote, .EasyMDEContainer .editor-preview-side blockquote { border-left: 3px solid #d1d5db; padding-left: 1rem; color: #6b7280; margin: 0.75rem 0; }
            .EasyMDEContainer .editor-preview code, .EasyMDEContainer .editor-preview-side code { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: 0.9em; background: #f3f4f6; padding: 0.15rem 0.35rem; border-radius: 3px; }
            .EasyMDEContainer .editor-preview pre, .EasyMDEContainer .editor-preview-side pre { background: #1f2937; color: #e5e7eb; padding: 1rem; border-radius: 6px; overflow-x: auto; margin: 0.75rem 0; }
            .EasyMDEContainer .editor-preview pre code, .EasyMDEContainer .editor-preview-side pre code { background: none; padding: 0; color: inherit; }

            /* Toolbar */
            .EasyMDEContainer .editor-toolbar { border-color: #e5e7eb !important; background: #fafafa !important; padding: 4px 8px !important; }
            .EasyMDEContainer .editor-toolbar button { color: #374151 !important; padding: 4px 6px !important; border-radius: 4px !important; }
            .EasyMDEContainer .editor-toolbar button:hover { background: #e5e7eb !important; }
            .EasyMDEContainer .editor-toolbar button.active { background: #dbeafe !important; color: #1d4ed8 !important; }
            .EasyMDEContainer .editor-toolbar i.separator { border-color: #e5e7eb !important; }

            /* Document link modal */
            .doc-link-dropdown { max-height: 300px; overflow-y: auto; }
            .doc-link-item:hover { background: #f3f4f6; }
            .doc-link-item.selected { background: #dbeafe; }
            .doc-link-item + .doc-link-item { border-top: 1px solid #f3f4f6; }

            .sortable-ghost { opacity: 0.4; }
            .sortable-drag { opacity: 0.9; }

            /* Fullscreen override — EasyMDE puts fullscreen class on children, not container */
            .EasyMDEContainer:has(.editor-toolbar.fullscreen) {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0 !important;
                z-index: 9999 !important;
                background: #fff !important;
            }
            .editor-toolbar.fullscreen {
                z-index: 10000 !important;
            }
            .CodeMirror-fullscreen {
                z-index: 10000 !important;
            }
            .editor-preview-side {
                z-index: 10000 !important;
            }
        </style>
    @endpush

    <div x-data="documentEditor()" @click="closeMenus()" class="flex h-full overflow-hidden">
        {{-- Document link modal --}}
        <div x-show="linkModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="linkModal = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">Link to document</h3>
                <input type="text" x-model="linkSearch" x-ref="linkSearchInput" @keydown.escape="linkModal = false"
                       placeholder="Search by ID or name..."
                       class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 mb-2">
                <div class="doc-link-dropdown border border-gray-200 rounded-md overflow-hidden">
                    @foreach($docList as $doc)
                        <button @click="insertDocLink('{{ $doc['id'] }}')" type="button"
                                x-show="!linkSearch || '{{ strtolower($doc['id'] . ' ' . ($doc['title'] ?? '') . ' ' . ($doc['type'] ?? '')) }}'.includes(linkSearch.toLowerCase())"
                                class="doc-link-item flex items-center gap-3 w-full px-3 py-2.5 text-left text-sm">
                            <span class="font-mono text-xs text-blue-600 shrink-0">{{ $doc['id'] }}</span>
                            <span class="truncate text-gray-800">{{ $doc['title'] ?: $doc['id'] }}</span>
                            <span class="text-xs text-gray-400 shrink-0 ml-auto bg-gray-100 px-1.5 py-0.5 rounded">{{ $doc['type'] }}</span>
                        </button>
                    @endforeach
                    @if(empty($docList))
                        <div class="px-3 py-4 text-sm text-gray-400 text-center">No documents available</div>
                    @endif
                </div>
                <div class="flex justify-between items-center mt-3">
                    <p class="text-xs text-gray-400">Inserts <code class="bg-gray-100 px-1 rounded">[[DOC-ID]]</code> at cursor</p>
                    <button type="button" @click="linkModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                </div>
            </div>
        </div>

        @include('documents.partials.sidebar-actions')
        @include('documents.partials.sidebar', ['sidebarCanEdit' => $canEdit])

        {{-- Main Content --}}
        <main class="flex-1 bg-gray-100 min-w-0 flex flex-col overflow-hidden editor-main">
            {{-- Top bar --}}
            <div x-data="{ barZ: false }" x-effect="if (sidebarOpen) { barZ = true } else { setTimeout(() => barZ = false, 200) }"
                 class="bg-white border-b border-gray-200 shadow-sm shrink-0 relative px-4 h-16 flex items-center editor-topbar" :class="barZ ? 'z-0' : 'z-40'">
                <div class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-3 min-w-0">
                        <button @click="sidebarOpen = true" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400 lg:hidden shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <span class="text-sm font-semibold text-gray-800">QMS</span>
                        <span class="text-xs text-gray-400 font-mono truncate">/{{ $currentPath }}</span>
                    </div>
                    <a href="{{ route('documents.index', ['path' => preg_replace('/\.md$/', '', $currentPath)]) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200 shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back to document
                    </a>
                </div>
            </div>

            <div class="flex-1 overflow-y-scroll">
                <div class="max-w-5xl mx-auto py-4 px-3 sm:py-6 sm:px-6 lg:px-8">
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-4">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @include('documents.partials.meta-header', ['isEditPage' => true])

                    <form method="POST" action="{{ route('documents.update') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="path" value="{{ $currentPath }}">

                        {{-- Editor --}}
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-5 {{ $meta['id'] ? 'mt-4' : '' }}">
                            <div id="editor-loader" class="flex items-center justify-center" style="min-height:400px">
                                <svg class="animate-spin h-6 w-6 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                            </div>
                            <textarea id="editor" name="content" style="min-height:400px">{{ $content }}</textarea>
                            <div class="flex justify-end gap-2 mt-4">
                                <a href="{{ route('documents.index', ['path' => preg_replace('/\.md$/', '', $currentPath)]) }}"
                                   class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md border border-gray-300">Cancel</a>
                                <button type="submit"
                                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
        <script>
            // Standalone fullscreen sync — hides/shows surrounding UI
            window._editorSyncFullscreen = function() {
                // EasyMDE puts 'fullscreen' on .editor-toolbar, NOT on .EasyMDEContainer
                var toolbar = document.querySelector('.editor-toolbar.fullscreen');
                var isFs = !!toolbar;
                var container = document.querySelector('.EasyMDEContainer');

                // Force the container to cover the screen
                if (container) {
                    if (isFs) {
                        container.style.position = 'fixed';
                        container.style.top = '0';
                        container.style.left = '0';
                        container.style.right = '0';
                        container.style.bottom = '0';
                        container.style.zIndex = '9999';
                        container.style.background = '#fff';
                    } else {
                        container.style.position = '';
                        container.style.top = '';
                        container.style.left = '';
                        container.style.right = '';
                        container.style.bottom = '';
                        container.style.zIndex = '';
                        container.style.background = '';
                    }
                }

                // Hide/show all surrounding elements
                var nav = document.querySelector('nav');
                if (nav) nav.style.display = isFs ? 'none' : '';

                var asides = document.querySelectorAll('aside');
                for (var i = 0; i < asides.length; i++) {
                    asides[i].style.display = isFs ? 'none' : '';
                }

                var topbars = document.querySelectorAll('.editor-topbar');
                for (var i = 0; i < topbars.length; i++) {
                    topbars[i].style.display = isFs ? 'none' : '';
                }

                // The app layout wrapper needs to not constrain the fixed editor
                var appWrapper = document.querySelector('body > div');
                if (appWrapper) {
                    appWrapper.style.overflow = isFs ? 'visible' : '';
                    appWrapper.style.height = isFs ? 'auto' : '';
                }
            };

            function documentEditor() {
                return {
                    sidebarOpen: false,
                    linkModal: false,
                    linkSearch: '',
                    docs: @json($docList),
                    editor: null,
                    sidebarSearch: '',
                    sidebarTypeFilter: '',
                    sidebarStatusFilter: '',
                    sidebarCommentFilter: '',
                    sidebarDocs: @json($sidebarDocs),
                    commentSummary: @json($commentSummary ?? []),

                    canEdit: @json($canEdit),
                    dragOver: false,
                    droppedFile: null,

                    // Context menus
                    fileMenu: { show: false, x: 0, y: 0 },
                    dirMenu: { show: false, x: 0, y: 0 },
                    bgMenu: { show: false, x: 0, y: 0 },

                    // Modals
                    modal: { rename: false, move: false, delete: false, renameDir: false, deleteDir: false, quickCreate: false, newDir: false, upload: false },

                    // Context data
                    ctx: { path: '', name: '', dirPath: '', dirName: '', targetDir: '' },

                    closeMenus() {
                        this.fileMenu.show = false;
                        this.dirMenu.show = false;
                        this.bgMenu.show = false;
                    },

                    openFileMenu(e, path, name) {
                        if (!this.canEdit) return;
                        e.preventDefault();
                        e.stopPropagation();
                        this.closeMenus();
                        this.ctx.path = path;
                        this.ctx.name = name;
                        this.fileMenu = { show: true, x: e.clientX, y: e.clientY };
                    },

                    openDirMenu(e, path, name) {
                        if (!this.canEdit) return;
                        e.preventDefault();
                        e.stopPropagation();
                        this.closeMenus();
                        this.ctx.dirPath = path;
                        this.ctx.dirName = name;
                        this.dirMenu = { show: true, x: e.clientX, y: e.clientY };
                    },

                    openBgMenu(e) {
                        if (!this.canEdit) return;
                        e.preventDefault();
                        this.closeMenus();
                        this.bgMenu = { show: true, x: e.clientX, y: e.clientY };
                    },

                    editFile() {
                        this.closeMenus();
                        window.location = '/documents/edit/' + this.ctx.path.replace('.md', '');
                    },

                    showRename() {
                        this.closeMenus();
                        this.modal.rename = true;
                        this.$nextTick(() => { this.$refs.renameInput.focus(); this.$refs.renameInput.select(); });
                    },

                    showMove() {
                        this.closeMenus();
                        this.modal.move = true;
                    },

                    showDelete() {
                        this.closeMenus();
                        this.modal.delete = true;
                    },

                    showRenameDir() {
                        this.closeMenus();
                        this.modal.renameDir = true;
                        this.$nextTick(() => { this.$refs.renameDirInput.focus(); this.$refs.renameDirInput.select(); });
                    },

                    showDeleteDir() {
                        this.closeMenus();
                        this.modal.deleteDir = true;
                    },

                    showQuickCreate(dir) {
                        this.closeMenus();
                        this.ctx.targetDir = dir;
                        this.modal.quickCreate = true;
                        this.$nextTick(() => this.$refs.quickCreateInput.focus());
                    },

                    showNewSubdir(dir) {
                        this.closeMenus();
                        this.ctx.targetDir = dir;
                        this.modal.newDir = true;
                        this.$nextTick(() => this.$refs.newDirInput.focus());
                    },

                    handleDrop(e) {
                        this.dragOver = false;
                        this._openUploadWithFile(e.dataTransfer.files, '');
                    },

                    handleDropToDir(e, dir) {
                        this.dragOver = false;
                        this._openUploadWithFile(e.dataTransfer.files, dir);
                    },

                    _openUploadWithFile(files, directory) {
                        if (!this.canEdit || !files || files.length === 0) return;
                        this.droppedFile = files[0];
                        this.modal.upload = true;
                        this.$nextTick(() => {
                            const fileInput = document.querySelector('#upload-file-input');
                            if (fileInput) { const dt = new DataTransfer(); dt.items.add(this.droppedFile); fileInput.files = dt.files; }
                            const titleInput = document.querySelector('#upload-title-input');
                            if (titleInput && !titleInput.value) { const name = this.droppedFile.name.replace(/\.[^.]+$/, '').replace(/[-_]/g, ' '); titleInput.value = name.charAt(0).toUpperCase() + name.slice(1); }
                            const dirSelect = document.querySelector('#upload-dir-select');
                            if (dirSelect && directory) { dirSelect.value = directory; }
                        });
                    },

                    initSortable(el, directory) {
                        if (!this.canEdit) return;
                        if (typeof Sortable === 'undefined') return;
                        Sortable.create(el, {
                            group: 'documents',
                            animation: 150,
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            ghostClass: 'sortable-ghost',
                            dragClass: 'sortable-drag',
                            onEnd: (evt) => {
                                const filePath = evt.item.dataset.path;
                                const newDir = evt.to.dataset.directory || '';
                                const oldDir = evt.from.dataset.directory || '';
                                if (newDir === oldDir) return;
                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '{{ route("documents.move") }}';
                                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="path" value="' + filePath + '"><input type="hidden" name="destination" value="' + newDir + '">';
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                    },

                    get sidebarFilteredDocs() {
                        var cs = this.commentSummary;
                        return this.sidebarDocs.filter(d => {
                            if (this.sidebarTypeFilter && d.type !== this.sidebarTypeFilter) return false;
                            if (this.sidebarStatusFilter && d.status !== this.sidebarStatusFilter) return false;
                            if (this.sidebarCommentFilter === 'with' && !(d.doc_id && cs[d.doc_id] && cs[d.doc_id].unresolved > 0)) return false;
                            if (this.sidebarCommentFilter === 'without' && d.doc_id && cs[d.doc_id] && cs[d.doc_id].unresolved > 0) return false;
                            if (this.sidebarSearch) {
                                const q = this.sidebarSearch.toLowerCase();
                                return (d.doc_id && d.doc_id.toLowerCase().includes(q)) ||
                                       (d.title && d.title.toLowerCase().includes(q)) ||
                                       (d.type && d.type.toLowerCase().includes(q)) ||
                                       (d.directory && d.directory.toLowerCase().includes(q));
                            }
                            return true;
                        });
                    },

                    getFilteredDocs() {
                        if (!this.linkSearch || this.linkSearch.trim() === '') return this.docs;
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
                        // Clear any stale autosave from localStorage
                        localStorage.removeItem('smde_{{ $currentPath }}');

                        this.editor = new EasyMDE({
                            element: document.getElementById('editor'),
                            spellChecker: false,
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
                                    name: 'code',
                                    action: EasyMDE.toggleCodeBlock,
                                    className: 'fa fa-code',
                                    title: 'Code block',
                                },
                                {
                                    name: 'diagram',
                                    action: (editor) => {
                                        const cm = editor.codemirror;
                                        const pos = cm.getCursor();
                                        cm.replaceRange('\n```mermaid\nflowchart TD\n    A[Start] --> B[End]\n```\n', pos);
                                        cm.focus();
                                    },
                                    className: 'fa fa-sitemap',
                                    title: 'Insert diagram',
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
                                    action: function(editor) {
                                        EasyMDE.toggleSideBySide(editor);
                                        window._editorSyncFullscreen();
                                    },
                                    className: 'fa fa-columns no-disable',
                                    title: 'Side by side',
                                },
                                {
                                    name: 'fullscreen',
                                    action: function(editor) {
                                        EasyMDE.toggleFullScreen(editor);
                                        window._editorSyncFullscreen();
                                    },
                                    className: 'fa fa-arrows-alt no-disable',
                                    title: 'Fullscreen',
                                },
                            ],
                            minHeight: '400px',
                            placeholder: 'Start writing...',
                            status: ['lines', 'words'],
                            previewRender: (plainText, preview) => {
                                // Default markdown rendering
                                let html = self.editor.markdown(plainText);

                                // Convert mermaid code blocks
                                html = html.replace(
                                    /<pre><code class="language-mermaid">([\s\S]*?)<\/code><\/pre>/g,
                                    (match, code) => {
                                        const decoded = code.replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"');
                                        return `<div class="mermaid">${decoded}</div>`;
                                    }
                                );

                                // Re-render mermaid after a tick
                                setTimeout(() => {
                                    if (window.mermaid) {
                                        window.mermaid.run({ nodes: preview.querySelectorAll('.mermaid') });
                                    }
                                }, 100);

                                return html;
                            },
                        });

                        // Hide loader, show editor
                        var loader = document.getElementById('editor-loader');
                        if (loader) loader.style.display = 'none';

                        // Catch Escape key — EasyMDE exits fullscreen without our wrapper
                        document.addEventListener('keydown', function(e) {
                            if (e.key === 'Escape') {
                                setTimeout(window._editorSyncFullscreen, 100);
                            }
                        });
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
