<x-app-layout>
    @section('page-title', ($meta['id'] ? $meta['id'] . ' — ' : '') . ($meta['title'] ?? 'Documents'))
    @push('styles')
        <style>
            .sortable-ghost { opacity: 0.4; }
            .sortable-drag { opacity: 0.9; }
        </style>
    @endpush

    <div x-data="documentManager()" @click="closeMenus()"
         x-init="document.addEventListener('dragleave', (e) => { if (!e.relatedTarget && e.clientX === 0 && e.clientY === 0) dragOver = false; }); document.addEventListener('drop', () => dragOver = false);"
         class="flex h-full relative overflow-hidden">

        @include('documents.partials.sidebar-actions')
        @include('documents.partials.sidebar', ['sidebarCanEdit' => $canEdit])

        {{-- Main Content --}}
        <main class="flex-1 bg-gray-100 min-w-0 flex flex-col overflow-hidden">
            {{-- Top bar: path + edit --}}
            <div x-data="{ barZ: false }" x-effect="if (sidebarOpen) { barZ = true } else { setTimeout(() => barZ = false, 200) }"
                 class="bg-white border-b border-gray-200 shadow-sm shrink-0 relative px-4 h-16 flex items-center" :class="barZ ? 'z-0' : 'z-40'">
                <div class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-3 min-w-0">
                        <button @click="sidebarOpen = true" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400 lg:hidden shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        @if($meta['id'])
                            <span data-doc-id="{{ $meta['id'] }}" data-doc-title="{{ $meta['title'] ?? '' }}" class="text-xs font-mono font-semibold px-1.5 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor($meta['type'] ?? '') }}">{{ $meta['id'] }}</span>
                        @endif
                        <span class="text-xs text-gray-400 font-mono truncate">documents/{{ $currentPath }}</span>
                    </div>
                    @if($canEdit && $isMarkdown)
                        <a href="{{ route('documents.edit', ['path' => $currentPath]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    @elseif($canEdit && $isForm)
                        <a href="{{ route('forms.edit', $currentPath) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs rounded-md hover:bg-blue-700 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    @elseif(!$isMarkdown && !$isForm)
                        <a href="{{ route('documents.download', $currentPath) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200 shrink-0">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>
                    @endif
                </div>
            </div>

            <div class="flex-1 overflow-y-scroll">

            @if($errors->any())
                <div class="max-w-5xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="max-w-5xl mx-auto py-4 px-3 sm:py-6 sm:px-6 lg:px-8">
                @include('documents.partials.meta-header', ['isEditPage' => false])

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 {{ $meta['id'] ? 'mt-4' : '' }}">
                    <div class="p-3 sm:p-8">
                        @if($isForm && $formSchema)
                            {{-- Form template view --}}
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $formSchema['title'] ?? $meta['title'] }}</h2>

                                {{-- Form fields preview --}}
                                <div class="space-y-4 mb-6">
                                    @foreach($formSchema['fields'] ?? [] as $field)
                                        <div class="border border-gray-200 rounded-md p-3">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                {{ $field['label'] }}
                                                @if($field['required'] ?? false)
                                                    <span class="text-red-400">*</span>
                                                @endif
                                            </label>
                                            <div class="text-xs text-gray-400">
                                                {{ ucfirst($field['type']) }}
                                                @if($field['type'] === 'select' && !empty($field['options']))
                                                    — {{ implode(', ', $field['options']) }}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <a href="{{ route('forms.fill', $currentPath) }}"
                                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Fill in this form
                                </a>

                                {{-- Recent submissions --}}
                                @if($formSubmissions && $formSubmissions->isNotEmpty())
                                    <div class="mt-8 pt-6 border-t border-gray-100">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-semibold text-gray-700">Submissions <span class="font-normal text-gray-400">({{ $formSubmissions->count() }})</span></h3>
                                            <a href="{{ route('records.form', $meta['id']) }}" class="text-xs text-blue-600 hover:text-blue-800">View all</a>
                                        </div>
                                        <div class="space-y-2">
                                            @foreach($formSubmissions as $sub)
                                                <a href="{{ route('records.show', $sub['filename']) }}"
                                                   class="flex items-center gap-3 p-3 rounded-md border border-gray-100 hover:bg-gray-50 transition-colors">
                                                    <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                                                        <span class="text-[10px] font-semibold text-gray-500">{{ strtoupper(substr($sub['author'], 0, 1)) }}</span>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-[10px] font-mono font-semibold px-1 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor('REC') }}">{{ $sub['id'] }}</span>
                                                            <span class="text-sm text-gray-800 truncate">{{ $sub['title'] }}</span>
                                                        </div>
                                                        <div class="text-[11px] text-gray-400 font-mono mt-0.5">records/{{ $sub['filename'] }}</div>
                                                        <span class="text-xs text-gray-400">{{ $sub['author'] }} · {{ $sub['submitted_at'] ? \Carbon\Carbon::parse($sub['submitted_at'])->diffForHumans() : '' }}</span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @elseif($isMarkdown)
                            <div class="prose prose-sm sm:prose-base max-w-none
                                        text-gray-700 prose-headings:text-gray-800
                                        prose-h1:text-xl sm:prose-h1:text-2xl prose-h1:border-b prose-h1:border-gray-200 prose-h1:pb-3 prose-h1:mb-6
                                        prose-h2:text-lg sm:prose-h2:text-xl prose-h2:mt-8
                                        prose-strong:text-gray-800
                                        prose-table:text-sm prose-th:bg-gray-50 prose-th:px-3 prose-th:py-2 prose-td:px-3 prose-td:py-2
                                        prose-a:text-blue-600">
                                {!! $content !!}
                            </div>
                        @elseif($fileInfo)
                            {{-- Non-markdown file view --}}
                            <div>
                                {{-- Preview for CSV/TSV --}}
                                @if($csvData)
                                    <div class="mb-4 border border-gray-200 rounded-lg overflow-auto" style="max-height: 70vh;">
                                        <table class="min-w-full text-xs">
                                            @foreach($csvData as $i => $row)
                                                <tr class="{{ $i === 0 ? 'bg-gray-50 font-semibold text-gray-700' : ($i % 2 === 0 ? 'bg-white' : 'bg-gray-50/50') }} border-b border-gray-100">
                                                    @foreach($row as $cell)
                                                        <{{ $i === 0 ? 'th' : 'td' }} class="px-3 py-2 text-left whitespace-nowrap">{{ $cell }}</{{ $i === 0 ? 'th' : 'td' }}>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                {{-- Preview for PDFs --}}
                                @elseif($fileInfo['mime'] === 'application/pdf')
                                    <div class="border border-gray-200 rounded-lg overflow-hidden mb-4" style="height: 75vh;">
                                        <iframe src="{{ route('documents.download', $currentPath) }}?inline=1" class="w-full h-full"></iframe>
                                    </div>
                                {{-- Preview for images --}}
                                @elseif(str_starts_with($fileInfo['mime'], 'image/'))
                                    <div class="mb-4 border border-gray-200 rounded-lg overflow-hidden inline-block max-w-full">
                                        <img src="{{ route('documents.download', $currentPath) }}?inline=1" alt="{{ $meta['title'] ?? $fileInfo['filename'] }}" class="max-h-[70vh]">
                                    </div>
                                @else
                                    <div class="text-center py-12">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-gray-100 mb-4">
                                            <span class="text-lg font-bold text-gray-500 uppercase">{{ $fileInfo['extension'] }}</span>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-800 mb-1">{{ $fileInfo['filename'] }}</h3>
                                    </div>
                                @endif

                                <div class="flex items-center justify-between">
                                    <p class="text-xs text-gray-400">
                                        {{ strtoupper($fileInfo['extension']) }} · {{ number_format($fileInfo['size'] / 1024, 1) }} KB
                                    </p>
                                    <a href="{{ route('documents.download', $currentPath) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Comments --}}
                @include('documents.partials.comments')

                {{-- File change history --}}
                @if(!empty($fileHistory))
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">
                        <div class="px-5 py-3 border-b border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-700">Document History</h3>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @foreach($fileHistory as $commit)
                                <a href="{{ route('documents.revision', $commit['hash']) }}"
                                   class="flex items-center gap-3 px-5 py-2.5 hover:bg-gray-50 transition-colors">
                                    <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                                        <span class="text-[9px] font-semibold text-gray-500">{{ strtoupper(substr($commit['author'], 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-sm text-gray-700">{{ $commit['message'] }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400 shrink-0">{{ $commit['author'] }}</span>
                                    <span class="text-xs text-gray-400 shrink-0">{{ $commit['date']->diffForHumans() }}</span>
                                    <svg class="w-3.5 h-3.5 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            </div>{{-- close inner scroll div --}}

        </main>
    </div>

    @push('scripts')
        <script>
            function documentManager() {
                return {
                    sidebarOpen: false,
                    canEdit: @json($canEdit),
                    sidebarSearch: '',
                    dragOver: false,
                    droppedFile: null,
                    sidebarTypeFilter: '',
                    sidebarStatusFilter: '',
                    sidebarCommentFilter: '',
                    sidebarDocs: @json($sidebarDocs),
                    commentSummary: @json($commentSummary ?? []),

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

                    // Context menus
                    fileMenu: { show: false, x: 0, y: 0 },
                    dirMenu: { show: false, x: 0, y: 0 },
                    bgMenu: { show: false, x: 0, y: 0 },

                    // Modals
                    modal: { rename: false, move: false, delete: false, renameDir: false, deleteDir: false, quickCreate: false, newDir: false, upload: false, confirmMove: false },
                    pendingMove: null,

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
                        if (this.ctx.path.endsWith('.form.json')) {
                            window.location = '/forms/edit/' + this.ctx.path;
                        } else {
                            window.location = '/documents/edit/' + this.ctx.path;
                        }
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
                        if (!this.canEdit) return;
                        if (!files || files.length === 0) return;

                        this.droppedFile = files[0];
                        this.modal.upload = true;

                        this.$nextTick(() => {
                            const fileInput = document.querySelector('#upload-file-input');
                            if (fileInput) {
                                const dt = new DataTransfer();
                                dt.items.add(this.droppedFile);
                                fileInput.files = dt.files;
                            }
                            const titleInput = document.querySelector('#upload-title-input');
                            if (titleInput && !titleInput.value) {
                                const name = this.droppedFile.name.replace(/\.[^.]+$/, '').replace(/[-_]/g, ' ');
                                titleInput.value = name.charAt(0).toUpperCase() + name.slice(1);
                            }
                            const dirSelect = document.querySelector('#upload-dir-select');
                            if (dirSelect && directory) {
                                dirSelect.value = directory;
                            }
                        });
                    },

                    initSortable(el, directory) {
                        if (!this.canEdit) return;
                        Sortable.create(el, {
                            group: 'documents',
                            animation: 150,
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            ghostClass: 'sortable-ghost',
                            dragClass: 'sortable-drag',
                            onEnd: (evt) => {
                                const filePath = evt.item.dataset.path;
                                const fileName = filePath.split('/').pop().replace(/(\.\w+)+$/, '').replace(/[-_]/g, ' ');
                                const newDir = evt.to.dataset.directory || '';
                                const oldDir = evt.from.dataset.directory || '';
                                if (newDir === oldDir) return;

                                // Put it back immediately, let the modal handle the move
                                evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);

                                const ucDir = (d) => d ? d.replace(/[-_]/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Root';
                                this.pendingMove = { path: filePath, destination: newDir, fileName, fromLabel: ucDir(oldDir), toLabel: ucDir(newDir) };
                                this.modal.confirmMove = true;
                            }
                        });
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
