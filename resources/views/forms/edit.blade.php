<x-app-layout>
    @section('page-title', 'Edit: ' . ($meta['id'] ?? '') . ' — ' . ($meta['title'] ?? 'Form'))
    @push('styles')
        <style>
            .sortable-ghost { opacity: 0.4; }
            .sortable-drag { opacity: 0.9; }
        </style>
    @endpush

    <div x-data="formEditor()" @click="closeMenus()" class="flex lg:h-full lg:overflow-hidden">

        @include('documents.partials.sidebar-actions')
        @include('documents.partials.sidebar', ['sidebarCanEdit' => $canEdit ?? false])

        {{-- Main Content --}}
        <main class="flex-1 bg-gray-100 min-w-0 flex flex-col lg:overflow-hidden">
            {{-- Top bar: same as document show page --}}
            <div x-data="{ barZ: false }" x-effect="if (sidebarOpen) { barZ = true } else { setTimeout(() => barZ = false, 200) }"
                 class="bg-white border-b border-gray-200 shadow-sm shrink-0 sticky top-[65px] lg:relative lg:top-0 px-4 h-16 flex items-center" :class="barZ ? 'z-0' : 'z-40'">
                <div class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-3 min-w-0">
                        <button @click="sidebarOpen = true" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400 lg:hidden shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        @if($meta['id'] ?? null)
                            <span class="text-xs font-mono font-semibold px-1.5 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor(explode('-', $meta['id'])[0] ?? '') }}">{{ $meta['id'] }}</span>
                        @endif
                        <span class="text-xs text-gray-400 font-mono truncate">documents/{{ $currentPath }}</span>
                    </div>
                    <a href="{{ route('documents.index', ['path' => $currentPath]) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200 shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span class="hidden sm:inline">Back to form</span><span class="sm:hidden">Back</span>
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

                    {{-- Meta header: same as document show page --}}
                    @include('documents.partials.meta-header', ['isEditPage' => true, 'docComments' => []])

                    {{-- Form editor card --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-4">
                        <form method="POST" action="{{ route('forms.update') }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="path" value="{{ $currentPath }}">

                            {{-- Title --}}
                            <div class="p-4 sm:p-6 border-b border-gray-100">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Form title</label>
                                <input type="text" name="title" required x-model="title"
                                       class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Fields --}}
                            <div class="p-4 sm:p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-sm font-semibold text-gray-700">Fields</h3>
                                    <span class="text-xs text-gray-400" x-text="fields.length + ' ' + (fields.length === 1 ? 'field' : 'fields')"></span>
                                </div>

                                <div class="space-y-3 mb-4">
                                    <template x-for="(field, index) in fields" :key="index">
                                        <div class="p-3 bg-gray-50 rounded-md border border-gray-200">
                                            <div class="flex items-start gap-3">
                                                <div class="pt-2 text-gray-300">
                                                    <span class="text-[10px] font-bold text-gray-400" x-text="index + 1"></span>
                                                </div>
                                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                                    <div>
                                                        <label class="block text-[10px] text-gray-400 mb-0.5">Label</label>
                                                        <input type="text" :name="'fields[' + index + '][label]'" x-model="field.label" placeholder="Field label" required
                                                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-[10px] text-gray-400 mb-0.5">Type</label>
                                                        <select :name="'fields[' + index + '][type]'" x-model="field.type"
                                                                class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                                            <option value="text">Text</option>
                                                            <option value="textarea">Text area</option>
                                                            <option value="date">Date</option>
                                                            <option value="number">Number</option>
                                                            <option value="email">Email</option>
                                                            <option value="select">Dropdown</option>
                                                            <option value="checkbox">Checkbox</option>
                                                        </select>
                                                    </div>
                                                    <div class="flex items-end gap-3">
                                                        <label class="flex items-center gap-1.5 text-xs text-gray-600 pb-2">
                                                            <input type="hidden" :name="'fields[' + index + '][required]'" value="0">
                                                            <input type="checkbox" :name="'fields[' + index + '][required]'" value="1" x-model="field.required"
                                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                            Required
                                                        </label>
                                                        <div class="flex items-center gap-1 ml-auto pb-1.5">
                                                            <button type="button" @click="moveField(index, -1)" x-show="index > 0"
                                                                    class="p-1 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-200">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                                            </button>
                                                            <button type="button" @click="moveField(index, 1)" x-show="index < fields.length - 1"
                                                                    class="p-1 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-200">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                            </button>
                                                            <button type="button" @click="removeField(index)"
                                                                    class="p-1 text-gray-400 hover:text-red-500 rounded hover:bg-red-50">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div x-show="field.type === 'select'" x-cloak class="mt-2 ml-8">
                                                <label class="text-[10px] text-gray-400 mb-0.5 block">Options (comma separated)</label>
                                                <input type="text" x-model="field.options_text"
                                                       @input="field.options = field.options_text.split(',').map(s => s.trim()).filter(s => s)"
                                                       placeholder="e.g. Low, Medium, High, Critical"
                                                       class="w-full border-gray-300 rounded-md text-xs focus:ring-blue-500 focus:border-blue-500">
                                                <template x-for="(opt, oi) in (field.options || [])" :key="'o-' + oi">
                                                    <input type="hidden" :name="'fields[' + index + '][options][' + oi + ']'" :value="opt">
                                                </template>
                                            </div>
                                            <div class="mt-2 ml-8">
                                                <label class="text-[10px] text-gray-400 mb-0.5 block">Help text (optional)</label>
                                                <input type="text" :name="'fields[' + index + '][description]'" x-model="field.description"
                                                       placeholder="Help text shown below the field"
                                                       class="w-full border-gray-300 rounded-md text-xs focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <button type="button" @click="addField()"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 rounded-md border border-blue-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add field
                                </button>
                            </div>

                            {{-- Save/Cancel --}}
                            <div class="flex justify-end gap-2 px-4 sm:px-6 py-4 border-t border-gray-100">
                                <a href="{{ route('documents.index', ['path' => $currentPath]) }}"
                                   class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md border border-gray-300">Cancel</a>
                                <button type="submit"
                                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
        <script>
            function formEditor() {
                var existingFields = @json($schema['fields'] ?? []);
                return {
                    sidebarOpen: false,
                    title: @json($schema['title'] ?? ''),
                    fields: existingFields.map(function(f) {
                        return {
                            label: f.label || '',
                            type: f.type || 'text',
                            required: f.required || false,
                            options: f.options || [],
                            options_text: (f.options || []).join(', '),
                            description: f.description || '',
                        };
                    }),

                    sidebarSearch: '',
                    sidebarCategoryFilter: '',
                    sidebarTypeFilter: '',
                    sidebarStatusFilter: '',
                    sidebarCommentFilter: '',
                    sidebarDocs: @json($sidebarDocs),
                    commentSummary: @json($commentSummary ?? []),

                    canEdit: true,
                    dragOver: false,
                    droppedFile: null,
                    fileMenu: { show: false, x: 0, y: 0 },
                    dirMenu: { show: false, x: 0, y: 0 },
                    bgMenu: { show: false, x: 0, y: 0 },
                    modal: { rename: false, move: false, delete: false, renameDir: false, deleteDir: false, quickCreate: false, newDir: false, upload: false, confirmMove: false },
                    pendingMove: null,
                    ctx: { path: '', name: '', dirPath: '', dirName: '', targetDir: '' },

                    closeMenus() { this.fileMenu.show = false; this.dirMenu.show = false; this.bgMenu.show = false; },
                    openFileMenu(e, path, name) { if (!this.canEdit) return; e.preventDefault(); e.stopPropagation(); this.closeMenus(); this.ctx.path = path; this.ctx.name = name; this.fileMenu = { show: true, x: e.clientX, y: e.clientY }; },
                    openDirMenu(e, path, name) { if (!this.canEdit) return; e.preventDefault(); e.stopPropagation(); this.closeMenus(); this.ctx.dirPath = path; this.ctx.dirName = name; this.dirMenu = { show: true, x: e.clientX, y: e.clientY }; },
                    openBgMenu(e) { if (!this.canEdit) return; e.preventDefault(); this.closeMenus(); this.bgMenu = { show: true, x: e.clientX, y: e.clientY }; },
                    editFile() {
                        this.closeMenus();
                        sessionStorage.setItem('sidebarScroll', document.getElementById('sidebar-nav')?.scrollTop);
                        sessionStorage.setItem('sidebarClickNav', '1');
                        if (this.ctx.path.endsWith('.form.json')) {
                            window.location = '/forms/edit/' + this.ctx.path;
                        } else {
                            window.location = '/documents/edit/' + this.ctx.path;
                        }
                    },
                    showRename() { this.closeMenus(); this.modal.rename = true; this.$nextTick(() => { this.$refs.renameInput?.focus(); this.$refs.renameInput?.select(); }); },
                    showMove() { this.closeMenus(); this.modal.move = true; },
                    showDelete() { this.closeMenus(); this.modal.delete = true; },
                    showRenameDir() { this.closeMenus(); this.modal.renameDir = true; this.$nextTick(() => { this.$refs.renameDirInput?.focus(); this.$refs.renameDirInput?.select(); }); },
                    showDeleteDir() { this.closeMenus(); this.modal.deleteDir = true; },
                    showQuickCreate(dir) { this.closeMenus(); this.ctx.targetDir = dir; this.modal.quickCreate = true; this.$nextTick(() => this.$refs.quickCreateInput?.focus()); },
                    showNewSubdir(dir) { this.closeMenus(); this.ctx.targetDir = dir; this.modal.newDir = true; this.$nextTick(() => this.$refs.newDirInput?.focus()); },
                    handleDrop(e) { this.dragOver = false; this._openUploadWithFile(e.dataTransfer.files, ''); },
                    handleDropToDir(e, dir) { this.dragOver = false; this._openUploadWithFile(e.dataTransfer.files, dir); },
                    _openUploadWithFile(files, directory) {
                        if (!files || files.length === 0) return;
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
                        if (window.innerWidth < 1024) return;
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
                                const fileName = filePath.split('/').pop().replace(/(\.\w+)+$/, '').replace(/[-_]/g, ' ');
                                const newDir = evt.to.dataset.directory || '';
                                const oldDir = evt.from.dataset.directory || '';
                                if (newDir === oldDir) return;
                                evt.from.insertBefore(evt.item, evt.from.children[evt.oldIndex]);
                                const ucDir = (d) => d ? d.replace(/[-_]/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : 'Root';
                                this.pendingMove = { path: filePath, destination: newDir, fileName, fromLabel: ucDir(oldDir), toLabel: ucDir(newDir) };
                                this.modal.confirmMove = true;
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

                    addField() {
                        this.fields.push({ label: '', type: 'text', required: false, options: [], options_text: '', description: '' });
                    },
                    removeField(index) {
                        if (this.fields.length > 1) this.fields.splice(index, 1);
                    },
                    moveField(index, direction) {
                        var newIndex = index + direction;
                        if (newIndex < 0 || newIndex >= this.fields.length) return;
                        var temp = this.fields[index];
                        this.fields[index] = this.fields[newIndex];
                        this.fields[newIndex] = temp;
                        this.fields = [...this.fields];
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
