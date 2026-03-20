<x-app-layout>
    @push('styles')
        <style>
            body, .min-h-screen { overflow: hidden; height: 100vh; }
            .sortable-ghost { opacity: 0.4; }
            .sortable-drag { opacity: 0.9; }
        </style>
    @endpush

    <div x-data="documentManager()" @click="closeMenus()" class="flex h-[calc(100vh-64px)] relative overflow-hidden">

        {{-- Context Menu: File --}}
        <div x-show="fileMenu.show" x-cloak
             :style="`top:${fileMenu.y}px;left:${fileMenu.x}px`"
             @click.stop
             class="fixed z-50 bg-white rounded-lg shadow-lg border border-gray-200 py-1 w-44">
            <button @click="editFile()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
            </button>
            <button @click="showRename()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Rename
            </button>
            <button @click="showMove()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                Move to...
            </button>
            <div class="border-t border-gray-100 my-1"></div>
            <button @click="showDelete()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete
            </button>
        </div>

        {{-- Context Menu: Directory --}}
        <div x-show="dirMenu.show" x-cloak
             :style="`top:${dirMenu.y}px;left:${dirMenu.x}px`"
             @click.stop
             class="fixed z-50 bg-white rounded-lg shadow-lg border border-gray-200 py-1 w-48">
            <button @click="showQuickCreate(dirMenu.path)" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New file here
            </button>
            <button @click="showNewSubdir(dirMenu.path)" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                New subdirectory
            </button>
            <div class="border-t border-gray-100 my-1"></div>
            <button @click="showRenameDir()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                Rename directory
            </button>
            <button @click="showDeleteDir()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete directory
            </button>
        </div>

        {{-- Context Menu: Sidebar empty space --}}
        <div x-show="bgMenu.show" x-cloak
             :style="`top:${bgMenu.y}px;left:${bgMenu.x}px`"
             @click.stop
             class="fixed z-50 bg-white rounded-lg shadow-lg border border-gray-200 py-1 w-44">
            <button @click="showQuickCreate('')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New file
            </button>
            <button @click="showNewSubdir('')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                New directory
            </button>
        </div>

        {{-- Modal: Rename file --}}
        <div x-show="modal.rename" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="modal.rename = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">Rename file</h3>
                <form method="POST" action="{{ route('documents.rename') }}">
                    @csrf
                    <input type="hidden" name="path" :value="ctx.path">
                    <input type="text" name="new_name" x-model="ctx.name" x-ref="renameInput" @keydown.escape="modal.rename = false"
                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="button" @click="modal.rename = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Rename</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Move file --}}
        <div x-show="modal.move" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="modal.move = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">Move to</h3>
                <form method="POST" action="{{ route('documents.move') }}">
                    @csrf
                    <input type="hidden" name="path" :value="ctx.path">
                    <select name="destination" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        @foreach($directories as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="button" @click="modal.move = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Move</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Delete file --}}
        <div x-show="modal.delete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="modal.delete = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-2">Delete file?</h3>
                <p class="text-sm text-gray-600 mb-4">This is tracked and can be reverted from git.</p>
                <form method="POST" action="{{ route('documents.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="path" :value="ctx.path">
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="modal.delete = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Rename directory --}}
        <div x-show="modal.renameDir" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="modal.renameDir = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">Rename directory</h3>
                <form method="POST" action="{{ route('documents.directory.rename') }}">
                    @csrf
                    <input type="hidden" name="path" :value="ctx.dirPath">
                    <input type="text" name="new_name" x-model="ctx.dirName" x-ref="renameDirInput" @keydown.escape="modal.renameDir = false"
                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="button" @click="modal.renameDir = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Rename</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Delete directory --}}
        <div x-show="modal.deleteDir" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="modal.deleteDir = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-2">Delete directory?</h3>
                <p class="text-sm text-gray-600 mb-4">The directory must be empty.</p>
                <form method="POST" action="{{ route('documents.directory.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="path" :value="ctx.dirPath">
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="modal.deleteDir = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: Quick create file --}}
        <div x-show="modal.quickCreate" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="modal.quickCreate = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">New file</h3>
                <form method="POST" action="{{ route('documents.quick-create') }}">
                    @csrf
                    <input type="hidden" name="directory" :value="ctx.targetDir">
                    <input type="text" name="filename" placeholder="Document name" x-ref="quickCreateInput" @keydown.escape="modal.quickCreate = false"
                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-400 mt-1" x-show="ctx.targetDir">
                        In: <span x-text="'/' + ctx.targetDir"></span>
                    </p>
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="button" @click="modal.quickCreate = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Create & Edit</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal: New subdirectory --}}
        <div x-show="modal.newDir" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="modal.newDir = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">New directory</h3>
                <form method="POST" action="{{ route('documents.directory.store') }}">
                    @csrf
                    <input type="hidden" name="parent" :value="ctx.targetDir">
                    <input type="text" name="name" placeholder="Directory name" x-ref="newDirInput" @keydown.escape="modal.newDir = false"
                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-400 mt-1" x-show="ctx.targetDir">
                        In: <span x-text="'/' + ctx.targetDir"></span>
                    </p>
                    <div class="flex justify-end gap-2 mt-3">
                        <button type="button" @click="modal.newDir = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Create</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:leave="transition-opacity ease-in duration-150"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 top-16 w-72 bg-white border-r border-gray-200 overflow-y-auto z-30
                      transform transition-transform duration-200 ease-in-out
                      lg:relative lg:top-0 lg:translate-x-0 lg:shrink-0 flex flex-col">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 text-lg">Documents</h2>
                <div class="flex items-center gap-1">
                    @if($canEdit)
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="p-1.5 rounded hover:bg-gray-100 text-gray-500" title="New...">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak @click.outside="open = false"
                                 class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <button @click="open = false; showQuickCreate('')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    New file
                                </button>
                                <button @click="open = false; showNewSubdir('')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                    New directory
                                </button>
                            </div>
                        </div>
                    @endif
                    <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded hover:bg-gray-100 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <nav class="p-3 flex-1 flex flex-col">
                <div>
                    @include('documents.partials.tree', ['items' => $tree, 'currentPath' => $currentPath, 'canEdit' => $canEdit, 'changedFiles' => $changedFiles])
                </div>
                @if($canEdit)
                    <div class="flex-1 min-h-[150px]" @contextmenu.prevent="openBgMenu($event)"></div>
                @endif
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

                    // Context menus
                    fileMenu: { show: false, x: 0, y: 0 },
                    dirMenu: { show: false, x: 0, y: 0 },
                    bgMenu: { show: false, x: 0, y: 0 },

                    // Modals
                    modal: { rename: false, move: false, delete: false, renameDir: false, deleteDir: false, quickCreate: false, newDir: false },

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
                        window.location = '{{ route("documents.edit") }}?path=' + encodeURIComponent(this.ctx.path);
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
