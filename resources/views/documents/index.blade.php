<x-app-layout>
    @push('styles')
        <style>
            .sortable-ghost { opacity: 0.4; }
            .sortable-drag { opacity: 0.9; }
        </style>
    @endpush

    <div x-data="documentManager()" @click="closeMenus()"
         x-init="document.addEventListener('dragleave', (e) => { if (!e.relatedTarget && e.clientX === 0 && e.clientY === 0) dragOver = false; }); document.addEventListener('drop', () => dragOver = false);"
         class="flex h-full relative overflow-hidden">

        {{-- Context Menu: File --}}
        <div x-show="fileMenu.show" x-cloak
             :style="`top:${fileMenu.y}px;left:${fileMenu.x}px`"
             @click.stop
             class="fixed z-50 bg-white rounded-lg shadow-lg border border-gray-200 py-1 w-52">
            <div class="px-3 py-1.5 text-[10px] font-medium text-gray-400 uppercase tracking-wider">This file</div>
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
            <button @click="showDelete()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                Delete
            </button>
            <div class="border-t border-gray-100 my-1"></div>
            <div class="px-3 py-1.5 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Create new</div>
            <button @click="closeMenus(); showQuickCreate(ctx.path ? ctx.path.substring(0, ctx.path.lastIndexOf('/')) || '' : '')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                New document here
            </button>
            <a @click="closeMenus()" href="{{ route('forms.create') }}" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                New form
            </a>
            <button @click="closeMenus(); modal.upload = true" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Upload file
            </button>
        </div>

        {{-- Context Menu: Directory --}}
        <div x-show="dirMenu.show" x-cloak
             :style="`top:${dirMenu.y}px;left:${dirMenu.x}px`"
             @click.stop
             class="fixed z-50 bg-white rounded-lg shadow-lg border border-gray-200 py-1 w-52">
            <div class="px-3 py-1.5 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Create in this folder</div>
            <button @click="showQuickCreate(dirMenu.path)" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                New document
            </button>
            <a @click="closeMenus()" href="{{ route('forms.create') }}" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                New form
            </a>
            <button @click="showNewSubdir(dirMenu.path)" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                New subdirectory
            </button>
            <button @click="closeMenus(); modal.upload = true" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Upload file
            </button>
            <div class="border-t border-gray-100 my-1"></div>
            <div class="px-3 py-1.5 text-[10px] font-medium text-gray-400 uppercase tracking-wider">This folder</div>
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
             class="fixed z-50 bg-white rounded-lg shadow-lg border border-gray-200 py-1 w-52">
            <div class="px-3 py-1.5 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Create new</div>
            <button @click="showQuickCreate('')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                New document
            </button>
            <a @click="closeMenus()" href="{{ route('forms.create') }}" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                New form
            </a>
            <button @click="showNewSubdir('')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                New directory
            </button>
            <button @click="closeMenus(); modal.upload = true" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                Upload file
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
                <h3 class="text-base font-semibold mb-3">New document</h3>
                <form method="POST" action="{{ route('documents.quick-create') }}">
                    @csrf
                    <input type="hidden" name="directory" :value="ctx.targetDir">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Document type</label>
                    <select name="doc_type" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 mb-3">
                        @foreach(\App\Services\DocumentMetadata::TYPES as $key => $label)
                            <option value="{{ $key }}" {{ $key === 'SOP' ? 'selected' : '' }}>{{ $key }} — {{ $label }}</option>
                        @endforeach
                    </select>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Document name</label>
                    <input type="text" name="filename" placeholder="e.g. CAPA Procedure" x-ref="quickCreateInput" @keydown.escape="modal.quickCreate = false"
                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-400 mt-1" x-show="ctx.targetDir">
                        In: <span x-text="'/' + ctx.targetDir"></span>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">ID will be assigned automatically.</p>
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

        {{-- Modal: Upload file --}}
        <div x-show="modal.upload" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="modal.upload = false">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-5" @click.stop>
                <h3 class="text-base font-semibold mb-3">Upload file</h3>
                <form method="POST" action="{{ route('documents.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">File</label>
                            <input type="file" name="file" id="upload-file-input" required
                                   class="w-full text-sm border border-gray-300 rounded-md file:mr-3 file:py-1.5 file:px-3 file:border-0 file:text-sm file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            <p class="text-xs text-gray-400 mt-1">Max 50MB. PDF, images, spreadsheets, or any other file type.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Document type</label>
                            <select name="doc_type" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach(\App\Services\DocumentMetadata::TYPES as $key => $label)
                                    <option value="{{ $key }}">{{ $key }} — {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Title</label>
                            <input type="text" name="title" id="upload-title-input" placeholder="e.g. ISO 13485 Certificate" required
                                   class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Directory</label>
                            <select name="directory" id="upload-dir-select" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach($directories as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Document ID will be assigned automatically.</p>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" @click="modal.upload = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                        <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Upload</button>
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
               @dragover.prevent="dragOver = true"
               @drop.prevent="handleDrop($event)"
               class="fixed inset-y-0 left-0 top-16 w-80 bg-white border-r border-gray-200 overflow-y-auto z-30
                      transform transition-transform duration-200 ease-in-out
                      lg:relative lg:top-0 lg:translate-x-0 lg:shrink-0 flex flex-col">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="font-semibold text-gray-800 text-lg">Documents</h2>
                    <span class="text-xs text-gray-400">{{ count($sidebarDocs) }}</span>
                </div>
                <div class="flex items-center gap-1">
                    @if($canEdit)
                        <div x-data="{ addOpen: false }" class="relative">
                            <button @click="addOpen = !addOpen" class="p-1.5 rounded hover:bg-gray-100 text-gray-500" title="New...">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                            <div x-show="addOpen" x-cloak @click.outside="addOpen = false"
                                 class="absolute right-0 mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <button @click="addOpen = false; showQuickCreate('')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    New document
                                </button>
                                <a href="{{ route('forms.create') }}" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    New form
                                </a>
                                <button @click="addOpen = false; showNewSubdir('')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                    New directory
                                </button>
                                <div class="border-t border-gray-100 my-1"></div>
                                <button @click="addOpen = false; modal.upload = true" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    Upload file
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
            {{-- Sidebar search + filters --}}
            <div class="px-3 pt-3 pb-1 space-y-2">
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" x-model="sidebarSearch" placeholder="Search..."
                           class="w-full pl-8 pr-3 py-1.5 text-xs border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 bg-gray-50">
                </div>
                @php
                    $existingTypes = collect($sidebarDocs)->pluck('type')->filter()->unique()->sort()->values();
                    $existingStatuses = collect($sidebarDocs)->pluck('status')->filter()->unique()->sort()->values();
                @endphp
                <div class="flex gap-1.5">
                    <select x-model="sidebarTypeFilter" class="flex-1 text-[11px] border-gray-200 rounded-md py-1 pl-2 pr-6 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All types</option>
                        @foreach($existingTypes as $type)
                            <option value="{{ $type }}">{{ $type }} ({{ collect($sidebarDocs)->where('type', $type)->count() }})</option>
                        @endforeach
                    </select>
                    <select x-model="sidebarStatusFilter" class="flex-1 text-[11px] border-gray-200 rounded-md py-1 pl-2 pr-6 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All statuses</option>
                        @foreach($existingStatuses as $status)
                            <option value="{{ $status }}">{{ \App\Services\DocumentMetadata::STATUSES[$status] ?? ucfirst($status) }} ({{ collect($sidebarDocs)->where('status', $status)->count() }})</option>
                        @endforeach
                    </select>
                </div>
                <button x-show="sidebarSearch || sidebarTypeFilter || sidebarStatusFilter" x-cloak
                        @click="sidebarSearch = ''; sidebarTypeFilter = ''; sidebarStatusFilter = ''"
                        class="text-[11px] text-blue-500 hover:text-blue-700">Clear filters</button>
            </div>
            <nav class="p-3 flex-1 flex flex-col overflow-y-auto">
                <div>
                    @include('documents.partials.tree', ['items' => $tree, 'currentPath' => $currentPath, 'canEdit' => $canEdit, 'changedFiles' => $changedFiles])
                </div>
                @if($canEdit)
                    <div class="flex-1 min-h-[100px]"
                         @contextmenu.prevent="openBgMenu($event)"
                         x-data="{ rootDragOver: false }"
                         @dragover.prevent="rootDragOver = true; dragOver = true"
                         @dragleave.prevent="rootDragOver = false"
                         @drop.prevent="rootDragOver = false; handleDrop($event)">
                        <div x-show="dragOver" x-cloak
                             class="mt-2 mx-1 p-4 rounded-lg border-2 border-dashed transition-colors flex items-center justify-center gap-2"
                             :class="rootDragOver ? 'border-blue-500 bg-blue-100' : 'border-gray-300 bg-gray-50'">
                            <svg class="w-5 h-5" :class="rootDragOver ? 'text-blue-500' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span class="text-sm font-medium" :class="rootDragOver ? 'text-blue-600' : 'text-gray-400'">Drop here for root</span>
                        </div>
                    </div>
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
        <main class="flex-1 overflow-y-auto bg-gray-50 min-w-0 flex flex-col">
            {{-- Top bar: path + mobile hamburger --}}
            <div class="flex items-center gap-3 px-4 py-2 bg-white border-b border-gray-200 shrink-0">
                <button @click="sidebarOpen = true" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400 lg:hidden">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="text-xs text-gray-400 font-mono truncate">/{{ $currentPath }}</span>
            </div>

            <div class="flex-1 overflow-y-auto">

            @if(session('success'))
                <div class="max-w-5xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="max-w-5xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="max-w-5xl mx-auto py-4 px-3 sm:py-8 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    @if($meta['id'])
                        <div class="px-3 sm:px-8 pt-3 sm:pt-5 pb-0">
                            <div class="flex items-start justify-between gap-4 pb-3 border-b border-gray-100">
                                <div class="text-xs space-y-1.5">
                                    {{-- Row 1: ID, type, status, version --}}
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-semibold {{ \App\Services\DocumentMetadata::typeColor($meta['type'] ?? '') }}  px-1.5 py-0.5 rounded">{{ $meta['id'] }}</span>
                                        @if($meta['type'] && isset(\App\Services\DocumentMetadata::TYPES[$meta['type']]))
                                            <span class="text-gray-400">·</span>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-medium {{ \App\Services\DocumentMetadata::typeColor($meta['type']) }}">{{ $meta['type'] }}</span>
                                        @endif
                                        <span class="text-gray-400">·</span>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[11px] font-medium
                                            {{ $meta['status'] === 'draft' ? 'bg-gray-100 text-gray-500' : '' }}
                                            {{ $meta['status'] === 'in_review' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $meta['status'] === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                            {{ $meta['status'] === 'obsolete' ? 'bg-red-100 text-red-600' : '' }}">
                                            {{ \App\Services\DocumentMetadata::STATUSES[$meta['status']] ?? ucfirst($meta['status']) }}
                                        </span>
                                        @if($meta['version'])
                                            <span class="text-gray-400">v{{ $meta['version'] }}</span>
                                        @endif
                                        @if($meta['effective_date'])
                                            <span class="text-gray-400">· Effective {{ $meta['effective_date'] }}</span>
                                        @endif
                                    </div>
                                    {{-- Row 2: References + last edit (single line) --}}
                                    <div class="flex items-center gap-2 text-gray-400">
                                        @if($meta['author'])
                                            <span>{{ $meta['author'] }}</span>
                                        @endif
                                        @if(!empty($meta['iso_refs']))
                                            <span>·</span>
                                            <span class="text-blue-400">ISO {{ implode(', ', $meta['iso_refs']) }}</span>
                                        @endif
                                        @if(!empty($meta['mdr_refs']))
                                            <span>·</span>
                                            <span class="text-blue-400">MDR {{ implode(', ', $meta['mdr_refs']) }}</span>
                                        @endif
                                        @if($lastEdit)
                                            <span>·</span>
                                            <a href="{{ route('documents.revision', $lastEdit['hash']) }}" class="hover:text-blue-500">Last edited by {{ $lastEdit['name'] }} {{ $lastEdit['date']->diffForHumans() }}</a>
                                        @endif
                                    </div>
                                </div>
                                @if($canEdit && $isMarkdown)
                                    <a href="{{ route('documents.edit', ['path' => preg_replace('/\.md$/', '', $currentPath)]) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 rounded-md text-sm text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-colors shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                @elseif(!$isMarkdown)
                                    <a href="{{ route('documents.download', $currentPath) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 rounded-md text-sm text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-colors shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                @endif
                            </div>
                        </div>
                    @elseif($canEdit && $isMarkdown)
                        <div class="px-3 sm:px-8 pt-3 sm:pt-5 pb-0">
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                                @if($lastEdit)
                                    <a href="{{ route('documents.revision', $lastEdit['hash']) }}" class="text-xs text-gray-400 hover:text-blue-500">Last edited by {{ $lastEdit['name'] }} {{ $lastEdit['date']->diffForHumans() }}</a>
                                @else
                                    <span></span>
                                @endif
                                <a href="{{ route('documents.edit', ['path' => preg_replace('/\.md$/', '', $currentPath)]) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 rounded-md text-sm text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endif
                    <div class="p-3 sm:p-8 {{ $meta['id'] ? 'pt-3 sm:pt-4' : '' }}">
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
                                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Recent submissions</h3>
                                        <div class="space-y-2">
                                            @foreach($formSubmissions as $sub)
                                                <a href="{{ route('forms.submission', $sub) }}"
                                                   class="flex items-center gap-3 p-3 rounded-md border border-gray-100 hover:bg-gray-50 transition-colors">
                                                    <div class="w-7 h-7 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                                                        <span class="text-[10px] font-semibold text-gray-500">{{ strtoupper(substr($sub->user->name, 0, 1)) }}</span>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <span class="text-sm text-gray-800 block">{{ $sub->title }}</span>
                                                        <span class="text-xs text-gray-400">{{ $sub->user->name }} · {{ $sub->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <span class="text-xs font-medium px-1.5 py-0.5 rounded
                                                        {{ $sub->status === 'draft' ? 'bg-gray-100 text-gray-500' : '' }}
                                                        {{ $sub->status === 'submitted' ? 'bg-blue-100 text-blue-700' : '' }}
                                                        {{ $sub->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}">
                                                        {{ ucfirst($sub->status) }}
                                                    </span>
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
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-gray-100 mb-4">
                                    <span class="text-lg font-bold text-gray-500 uppercase">{{ $fileInfo['extension'] }}</span>
                                </div>
                                <h3 class="text-lg font-medium text-gray-800 mb-1">{{ $fileInfo['filename'] }}</h3>
                                <p class="text-sm text-gray-500 mb-6">
                                    {{ strtoupper($fileInfo['extension']) }} file · {{ number_format($fileInfo['size'] / 1024, 1) }} KB
                                </p>

                                {{-- Preview for images --}}
                                @if(str_starts_with($fileInfo['mime'], 'image/'))
                                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden inline-block max-w-full">
                                        <img src="{{ route('documents.download', $currentPath) }}" alt="{{ $meta['title'] ?? $fileInfo['filename'] }}" class="max-h-96">
                                    </div>
                                @endif

                                {{-- Preview for PDFs --}}
                                @if($fileInfo['mime'] === 'application/pdf')
                                    <div class="mb-6 border border-gray-200 rounded-lg overflow-hidden" style="height: 600px;">
                                        <iframe src="{{ route('documents.download', $currentPath) }}" class="w-full h-full"></iframe>
                                    </div>
                                @endif

                                <div>
                                    <a href="{{ route('documents.download', $currentPath) }}"
                                       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

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

            {{-- Bottom bar --}}
            <div class="shrink-0 bg-white border-t border-gray-200 px-4 py-2">
                <div class="flex items-center justify-between gap-4 max-w-5xl mx-auto">
                    <div class="flex items-center gap-2 text-xs text-gray-400 min-w-0 overflow-hidden">
                        @if($meta['id'])
                            <span class="font-mono font-medium shrink-0 px-1 py-0.5 rounded text-[10px] {{ \App\Services\DocumentMetadata::typeColor($meta['type'] ?? '') }}">{{ $meta['id'] }}</span>
                            <span class="shrink-0">·</span>
                        @endif
                        @if($meta['title'])
                            <span class="truncate">{{ $meta['title'] }}</span>
                            <span class="shrink-0">·</span>
                        @endif
                        <span class="shrink-0 px-1.5 py-0.5 rounded text-[10px] font-medium
                            {{ $meta['status'] === 'draft' ? 'bg-gray-100 text-gray-500' : '' }}
                            {{ $meta['status'] === 'in_review' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $meta['status'] === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $meta['status'] === 'obsolete' ? 'bg-red-100 text-red-600' : '' }}">
                            {{ \App\Services\DocumentMetadata::STATUSES[$meta['status']] ?? ucfirst($meta['status']) }}
                        </span>
                        @if($meta['version'])
                            <span class="shrink-0">v{{ $meta['version'] }}</span>
                        @endif
                        @if($lastEdit)
                            <span class="shrink-0 hidden sm:inline">·</span>
                            <a href="{{ route('documents.revision', $lastEdit['hash']) }}" class="shrink-0 hidden sm:inline hover:text-blue-500">{{ $lastEdit['name'] }} {{ $lastEdit['date']->diffForHumans() }}</a>
                        @endif
                    </div>
                    @if($canEdit && $isMarkdown)
                        <a href="{{ route('documents.edit', ['path' => preg_replace('/\.md$/', '', $currentPath)]) }}"
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
                    sidebarDocs: @json($sidebarDocs),

                    get sidebarFilteredDocs() {
                        return this.sidebarDocs.filter(d => {
                            if (this.sidebarTypeFilter && d.type !== this.sidebarTypeFilter) return false;
                            if (this.sidebarStatusFilter && d.status !== this.sidebarStatusFilter) return false;
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
                        window.location = '/qms/edit/' + this.ctx.path.replace('.md', '');
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
