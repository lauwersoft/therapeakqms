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

{{-- Confirm Move Modal --}}
<div x-show="modal.confirmMove && pendingMove" x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="modal.confirmMove = false; pendingMove = null"
     x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm mx-4 overflow-hidden" @click.stop>
        <div class="p-5">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                </svg>
            </div>
            <h3 class="text-sm font-semibold text-gray-800 text-center mb-1">Move document</h3>
            <p class="text-xs text-gray-500 text-center mb-4">
                Move <span class="font-medium text-gray-700" x-text="pendingMove?.fileName"></span>
            </p>
            <div class="flex items-center justify-center gap-3 text-xs mb-1">
                <span class="px-2.5 py-1.5 rounded-lg bg-gray-100 text-gray-600 font-medium">
                    <svg class="w-3 h-3 inline mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                    <span x-text="pendingMove?.fromLabel"></span>
                </span>
                <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                <span class="px-2.5 py-1.5 rounded-lg bg-blue-50 text-blue-700 font-medium">
                    <svg class="w-3 h-3 inline mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                    <span x-text="pendingMove?.toLabel"></span>
                </span>
            </div>
        </div>
        <div class="flex border-t border-gray-100">
            <button @click="modal.confirmMove = false; pendingMove = null"
                    class="flex-1 px-4 py-3 text-sm text-gray-600 hover:bg-gray-50 transition-colors font-medium">Cancel</button>
            <button @click="
                var f = document.createElement('form');
                f.method = 'POST';
                f.action = '{{ route('documents.move') }}';
                f.innerHTML = '<input type=hidden name=_token value={{ csrf_token() }}><input type=hidden name=path value=' + pendingMove.path + '><input type=hidden name=destination value=' + pendingMove.destination + '>';
                document.body.appendChild(f);
                f.submit();
            " class="flex-1 px-4 py-3 text-sm text-blue-600 hover:bg-blue-50 transition-colors font-medium border-l border-gray-100">Move</button>
        </div>
    </div>
</div>
