<x-app-layout>
    @push('styles')
        <style>
            body, .min-h-screen { overflow: hidden; height: 100vh; }
            .sortable-ghost { opacity: 0.4; }
            .sortable-drag { opacity: 0.9; }
            .drop-target { background-color: rgb(219 234 254); border-radius: 0.25rem; }
        </style>
    @endpush

    <div x-data="documentManager()" class="flex h-[calc(100vh-64px)] relative overflow-hidden">
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:leave="transition-opacity ease-in duration-150"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden"></div>

        {{-- Context Menu --}}
        <div x-show="contextMenu.show" x-cloak
             :style="`top: ${contextMenu.y}px; left: ${contextMenu.x}px`"
             @click.outside="contextMenu.show = false"
             class="fixed z-50 bg-white rounded-lg shadow-lg border border-gray-200 py-1 w-48">
            <button @click="startRename()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Rename
            </button>
            <button @click="startMove()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                Move to...
            </button>
            <div class="border-t border-gray-100 my-1"></div>
            <button @click="startDelete()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete
            </button>
        </div>

        {{-- Rename Inline Input --}}
        <div x-show="renameModal.show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="renameModal.show = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">Rename</h3>
                <form :action="'{{ route('documents.rename') }}'" method="POST">
                    @csrf
                    <input type="hidden" name="path" :value="renameModal.path">
                    <input type="text" name="new_name" x-model="renameModal.name" x-ref="renameInput"
                           @keydown.escape="renameModal.show = false"
                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="button" @click="renameModal.show = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Rename</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Move Modal --}}
        <div x-show="moveModal.show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="moveModal.show = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">Move to</h3>
                <form :action="'{{ route('documents.move') }}'" method="POST">
                    @csrf
                    <input type="hidden" name="path" :value="moveModal.path">
                    <select name="destination" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach($directories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="button" @click="moveModal.show = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Move</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Delete Modal --}}
        <div x-show="deleteModal.show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="deleteModal.show = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-2">Delete document?</h3>
                <p class="text-sm text-gray-600 mb-4">This is tracked in git and can be reverted.</p>
                <form :action="'{{ route('documents.destroy') }}'" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="path" :value="deleteModal.path">
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="deleteModal.show = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- New Directory Modal --}}
        <div x-show="newDirModal.show" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="newDirModal.show = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">New Directory</h3>
                <form method="POST" action="{{ route('documents.directory.store') }}">
                    @csrf
                    <input type="text" name="name" placeholder="Directory name" x-ref="newDirInput"
                           @keydown.escape="newDirModal.show = false"
                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <select name="parent" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 mt-2">
                        @foreach($directories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="button" @click="newDirModal.show = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Create</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 top-16 w-72 bg-white border-r border-gray-200 overflow-y-auto z-30
                      transform transition-transform duration-200 ease-in-out
                      lg:relative lg:top-0 lg:translate-x-0 lg:shrink-0 flex flex-col">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 text-lg">Documents</h2>
                <div class="flex items-center gap-1">
                    @if($canEdit)
                        <a href="{{ route('documents.create') }}" class="p-1.5 rounded hover:bg-gray-100 text-gray-500" title="New document">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </a>
                        <button @click="newDirModal.show = true; $nextTick(() => $refs.newDirInput.focus())" class="p-1.5 rounded hover:bg-gray-100 text-gray-500" title="New directory">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                        </button>
                    @endif
                    <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded hover:bg-gray-100 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <nav class="p-3 flex-1">
                @include('documents.partials.tree', ['items' => $tree, 'currentPath' => $currentPath, 'canEdit' => $canEdit, 'changedFiles' => $changedFiles])
            </nav>
            @if($canEdit && $pendingCount > 0)
                <div class="p-3 border-t border-gray-200">
                    <a href="{{ route('documents.changes') }}"
                       class="flex items-center justify-between w-full px-3 py-2 text-sm bg-amber-50 text-amber-800 rounded-md hover:bg-amber-100 border border-amber-200">
                        <span class="font-medium">{{ $pendingCount }} unpublished {{ Str::plural('change', $pendingCount) }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            @endif
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto bg-gray-50 min-w-0">
            {{-- Mobile header --}}
            <div class="lg:hidden flex items-center gap-3 px-4 py-3 bg-white border-b border-gray-200">
                <button @click="sidebarOpen = true" class="p-2 rounded-md hover:bg-gray-100 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="text-sm font-medium text-gray-600 truncate">{{ str_replace('/', ' / ', $currentPath) }}</span>
            </div>

            @if(session('success'))
                <div class="max-w-4xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="max-w-4xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="max-w-4xl mx-auto py-6 px-4 sm:py-8 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 relative">
                    @if($canEdit)
                        <a href="{{ route('documents.edit', ['path' => $currentPath]) }}"
                           class="absolute top-4 right-4 sm:top-5 sm:right-5 inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 rounded-md text-sm text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-colors z-10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                    @endif
                    <div class="p-5 sm:p-8">
                        <div class="prose prose-gray prose-sm sm:prose-base max-w-none
                                    prose-headings:text-gray-900 prose-h1:text-2xl sm:prose-h1:text-3xl prose-h1:border-b prose-h1:border-gray-200 prose-h1:pb-3 prose-h1:mb-6
                                    prose-h2:text-xl sm:prose-h2:text-2xl prose-h2:mt-8
                                    prose-table:text-sm prose-th:bg-gray-50 prose-th:px-3 prose-th:py-2 prose-td:px-3 prose-td:py-2
                                    prose-a:text-blue-600">
                            {!! $content !!}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
        <script>
            function documentManager() {
                return {
                    sidebarOpen: false,
                    canEdit: @json($canEdit),
                    contextMenu: { show: false, x: 0, y: 0, path: '', name: '' },
                    renameModal: { show: false, path: '', name: '' },
                    moveModal: { show: false, path: '' },
                    deleteModal: { show: false, path: '' },
                    newDirModal: { show: false },

                    openContextMenu(e, path, name) {
                        if (!this.canEdit) return;
                        e.preventDefault();
                        this.contextMenu = { show: true, x: e.clientX, y: e.clientY, path, name };
                    },

                    startRename() {
                        this.contextMenu.show = false;
                        this.renameModal = { show: true, path: this.contextMenu.path, name: this.contextMenu.name };
                        this.$nextTick(() => {
                            this.$refs.renameInput.focus();
                            this.$refs.renameInput.select();
                        });
                    },

                    startMove() {
                        this.contextMenu.show = false;
                        this.moveModal = { show: true, path: this.contextMenu.path };
                    },

                    startDelete() {
                        this.contextMenu.show = false;
                        this.deleteModal = { show: true, path: this.contextMenu.path };
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
                                const newDir = evt.to.dataset.directory || '';
                                const oldDir = evt.from.dataset.directory || '';

                                if (newDir === oldDir) return;

                                const form = document.createElement('form');
                                form.method = 'POST';
                                form.action = '{{ route("documents.move") }}';
                                form.innerHTML = `
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="path" value="${filePath}">
                                    <input type="hidden" name="destination" value="${newDir}">
                                `;
                                document.body.appendChild(form);
                                form.submit();
                            }
                        });
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
