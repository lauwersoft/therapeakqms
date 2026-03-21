<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Browser</h2>
                <span class="text-sm text-gray-400">{{ $totalDocs }} {{ Str::plural('document', $totalDocs) }}</span>
            </div>
            @if($canEdit)
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <a href="{{ route('forms.create') }}" class="inline-flex items-center gap-1.5 px-2 sm:px-3 py-1.5 bg-white border border-gray-300 rounded-md text-xs sm:text-sm text-gray-600 hover:bg-gray-50 whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        <span class="hidden sm:inline">New</span> Form
                    </a>
                    <button onclick="document.getElementById('browse-upload-modal-toggle').click()" class="inline-flex items-center gap-1.5 px-2 sm:px-3 py-1.5 bg-white border border-gray-300 rounded-md text-xs sm:text-sm text-gray-600 hover:bg-gray-50 whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        Upload
                    </button>
                    <button @click="ctx.dir = ''; quickCreateModal = true; $nextTick(() => $refs.browseQuickCreateInput?.focus())" class="inline-flex items-center gap-1.5 px-2 sm:px-3 py-1.5 bg-blue-600 text-white text-xs sm:text-sm rounded-md hover:bg-blue-700 whitespace-nowrap">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span class="hidden sm:inline">New</span> Doc
                    </button>
                </div>
            @endif
        </div>
    </x-slot>

    <div x-data="documentBrowser()" @click="ctx.show = false" @contextmenu="ctx.show = false"
         @dragover.prevent="dragOver = true"
         @dragleave.self.prevent="dragOver = false"
         @drop.prevent="handleDrop($event)"
         x-init="document.addEventListener('dragleave', (e) => { if (!e.relatedTarget && e.clientX === 0 && e.clientY === 0) dragOver = false; }); document.addEventListener('drop', () => dragOver = false);"
         class="py-6 relative">

        @if($canEdit)
            {{-- Single context menu --}}
            <div x-show="ctx.show" x-cloak
                 :style="`top:${ctx.y}px;left:${ctx.x}px`"
                 @click.outside="ctx.show = false"
                 class="fixed z-50 bg-white rounded-lg shadow-lg border border-gray-200 py-1 w-52">

                {{-- File actions --}}
                <template x-if="ctx.type === 'file'">
                    <div>
                        <div class="px-3 py-1.5 text-[10px] font-medium text-gray-400 uppercase tracking-wider">This document</div>
                        <a :href="ctx.isMarkdown ? ('/qms/edit/' + ctx.path.replace(/\.md$/, '')) : ('/qms/download/' + ctx.path)"
                           class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x-bind:d="ctx.isMarkdown ? 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z' : 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'"/>
                            </svg>
                            <span x-text="ctx.isMarkdown ? 'Edit' : 'Download'"></span>
                        </a>
                        <a :href="'/qms/' + ctx.urlPath" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            View
                        </a>
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
                    </div>
                </template>

                {{-- Create actions (always shown) --}}
                <div class="px-3 py-1.5 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Create new</div>
                <button @click="ctx.show = false; quickCreateModal = true; $nextTick(() => $refs.browseQuickCreateInput?.focus())" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    New document
                </button>
                <a href="{{ route('forms.create') }}" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    New form
                </a>
                <button @click="ctx.show = false; newDirModal = true; $nextTick(() => $refs.browseNewDirInput?.focus())" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                    New directory
                </button>
                <button @click="ctx.show = false; uploadModal = true" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    Upload file
                </button>
            </div>

            {{-- Quick create document modal --}}
            <div x-show="quickCreateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="quickCreateModal = false">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                    <h3 class="text-base font-semibold mb-3">New document</h3>
                    <form method="POST" action="{{ route('documents.quick-create') }}">
                        @csrf
                        <input type="hidden" name="directory" :value="ctx.dir">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Document type</label>
                        <select name="doc_type" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 mb-3">
                            @foreach(\App\Services\DocumentMetadata::TYPES as $key => $label)
                                <option value="{{ $key }}" {{ $key === 'SOP' ? 'selected' : '' }}>{{ $key }} — {{ $label }}</option>
                            @endforeach
                        </select>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Document name</label>
                        <input type="text" name="filename" placeholder="e.g. CAPA Procedure" x-ref="browseQuickCreateInput" required
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-400 mt-1" x-show="ctx.dir">
                            In: <span x-text="'/' + ctx.dir"></span>
                        </p>
                        <p class="text-xs text-gray-400 mt-1">ID assigned automatically.</p>
                        <div class="flex justify-end gap-2 mt-3">
                            <button type="button" @click="quickCreateModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Create & Edit</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- New directory modal --}}
            <div x-show="newDirModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="newDirModal = false">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                    <h3 class="text-base font-semibold mb-3">New directory</h3>
                    <form method="POST" action="{{ route('documents.directory.store') }}">
                        @csrf
                        <input type="hidden" name="parent" :value="ctx.dir">
                        <input type="text" name="name" placeholder="Directory name" x-ref="browseNewDirInput" required
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        <p class="text-xs text-gray-400 mt-1" x-show="ctx.dir">
                            In: <span x-text="'/' + ctx.dir"></span>
                        </p>
                        <div class="flex justify-end gap-2 mt-3">
                            <button type="button" @click="newDirModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Create</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Rename modal --}}
            <div x-show="renameModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="renameModal = false">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                    <h3 class="text-base font-semibold mb-3">Rename</h3>
                    <form method="POST" action="{{ route('documents.rename') }}">
                        @csrf
                        <input type="hidden" name="path" :value="ctx.path">
                        <input type="text" name="new_name" x-model="renameName" x-ref="browseRenameInput"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        <div class="flex justify-end gap-2 mt-3">
                            <button type="button" @click="renameModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Rename</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Move modal --}}
            <div x-show="moveModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="moveModal = false">
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
                            <button type="button" @click="moveModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Move</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Delete modal --}}
            <div x-show="deleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="deleteModal = false">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-sm mx-4 p-5" @click.stop>
                    <h3 class="text-base font-semibold mb-2">Delete document?</h3>
                    <p class="text-sm text-gray-600 mb-4">This is tracked and can be reverted from git.</p>
                    <form method="POST" action="{{ route('documents.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="path" :value="ctx.path">
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="deleteModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Upload modal --}}
            <div x-show="uploadModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="uploadModal = false">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-5" @click.stop>
                    <h3 class="text-base font-semibold mb-3">Upload file</h3>
                    <form method="POST" action="{{ route('documents.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">File</label>
                                <input type="file" name="file" id="browse-upload-file" required
                                       class="w-full text-sm border border-gray-300 rounded-md file:mr-3 file:py-1.5 file:px-3 file:border-0 file:text-sm file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
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
                                <input type="text" name="title" id="browse-upload-title" placeholder="e.g. ISO 13485 Certificate" required
                                       class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Directory</label>
                                <select name="directory" id="browse-upload-dir" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($directories as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="uploadModal = false" class="px-3 py-1.5 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                            <button type="submit" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
            <button id="browse-upload-modal-toggle" @click="uploadModal = true" class="hidden"></button>

            {{-- Drop overlay --}}
            <div x-show="dragOver" x-cloak
                 class="fixed inset-0 z-40 bg-blue-50/80 flex items-center justify-center pointer-events-none" style="top: 128px;">
                <div class="text-center bg-white rounded-2xl shadow-lg border-2 border-dashed border-blue-400 px-12 py-8">
                    <svg class="w-12 h-12 text-blue-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-lg font-medium text-blue-600">Drop file to upload</p>
                    <p class="text-sm text-blue-400 mt-1">PDF, images, spreadsheets, or any file</p>
                </div>
            </div>
        @endif

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Unpublished changes banner --}}
            @if($pendingCount > 0 && $canEdit)
                <div class="mb-5">
                    <a href="{{ route('documents.changes') }}"
                       class="flex items-center justify-between w-full px-4 py-3 text-sm bg-amber-50 text-amber-800 rounded-lg hover:bg-amber-100 border border-amber-200">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 bg-amber-500 rounded-full shrink-0"></span>
                            <span class="font-medium">{{ $pendingCount }} unpublished {{ Str::plural('change', $pendingCount) }}</span>
                        </div>
                        <span class="text-xs text-amber-600">Review & publish</span>
                    </a>
                </div>
            @endif

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
                    {{-- Root files --}}
                    <template x-if="filteredDocs.some(d => d.raw_directory === '')">
                        <div class="mb-5">
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                                <template x-for="doc in filteredDocs.filter(d => d.raw_directory === '')" :key="doc.path">
                                    <a :href="'/qms/' + doc.url_path"
                                       @if($canEdit)
                                           @contextmenu.prevent="openFileCtx($event, doc)"
                                       @endif
                                       class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                        <span class="font-mono text-xs text-gray-400 w-20 shrink-0" x-text="doc.doc_id"></span>
                                        <div class="flex-1 min-w-0">
                                            <span class="text-sm text-gray-800 block truncate" x-text="doc.title"></span>
                                            <span class="text-[11px] text-gray-400 font-mono block truncate" x-text="'/' + doc.path"></span>
                                        </div>
                                        <span x-show="doc.type" class="text-[11px] text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded shrink-0" x-text="doc.type"></span>
                                        <span class="shrink-0 text-[11px] font-medium px-1.5 py-0.5 rounded"
                                              :class="statusClass(doc.status)" x-text="doc.status_label"></span>
                                        <span x-show="doc.version" class="text-xs text-gray-400 w-10 text-right shrink-0" x-text="'v' + doc.version"></span>
                                        <span x-show="doc.changed" class="w-2 h-2 rounded-full shrink-0"
                                              :class="{'bg-green-500': doc.changed === 'new' || doc.changed === 'added', 'bg-amber-500': doc.changed === 'modified', 'bg-red-500': doc.changed === 'deleted', 'bg-blue-500': doc.changed === 'move' || doc.changed === 'rename'}"></span>
                                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Directories --}}
                    <template x-for="dir in uniqueDirs.filter(d => d !== '')" :key="dir">
                        <div x-show="filteredDocs.some(d => d.raw_directory === dir)" class="mb-5">
                            <div class="flex items-center gap-2 mb-2 px-3 py-2 -mx-3 rounded-lg cursor-pointer hover:bg-gray-200/60 transition-colors"
                                 @if($canEdit)
                                     @contextmenu.prevent.stop="openDirCtx($event, dir)"
                                 @endif>
                                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                <span class="text-sm font-medium text-gray-500" x-text="dir.replace(/[-_]/g, ' ').replace(/\//g, ' / ').replace(/\b\w/g, l => l.toUpperCase())"></span>
                                <span class="text-[11px] text-gray-400" x-text="filteredDocs.filter(d => d.raw_directory === dir).length"></span>
                            </div>
                            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden ml-6">
                                <template x-for="doc in filteredDocs.filter(d => d.raw_directory === dir)" :key="doc.path">
                                    <a :href="'/qms/' + doc.url_path"
                                       @if($canEdit)
                                           @contextmenu.prevent="openFileCtx($event, doc)"
                                       @endif
                                       class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                        <span class="font-mono text-xs text-gray-400 w-20 shrink-0" x-text="doc.doc_id"></span>
                                        <div class="flex-1 min-w-0">
                                            <span class="text-sm text-gray-800 block truncate" x-text="doc.title"></span>
                                            <span class="text-[11px] text-gray-400 font-mono block truncate" x-text="'/' + doc.path"></span>
                                        </div>
                                        <span x-show="doc.type" class="text-[11px] text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded shrink-0" x-text="doc.type"></span>
                                        <span class="shrink-0 text-[11px] font-medium px-1.5 py-0.5 rounded"
                                              :class="statusClass(doc.status)" x-text="doc.status_label"></span>
                                        <span x-show="doc.version" class="text-xs text-gray-400 w-10 text-right shrink-0" x-text="'v' + doc.version"></span>
                                        <span x-show="doc.author" class="text-xs text-gray-400 shrink-0 hidden sm:inline" x-text="doc.author"></span>
                                        <span x-show="doc.changed" class="w-2 h-2 rounded-full shrink-0"
                                              :class="{'bg-green-500': doc.changed === 'new' || doc.changed === 'added', 'bg-amber-500': doc.changed === 'modified', 'bg-red-500': doc.changed === 'deleted', 'bg-blue-500': doc.changed === 'move' || doc.changed === 'rename'}"></span>
                                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>

            <div x-show="filteredDocs.length === 0" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                <p class="text-gray-400">No documents match your filters.</p>
            </div>

            {{-- Empty space for background right-click --}}
            @if($canEdit)
                <div class="min-h-[200px]"
                     @contextmenu.prevent.stop="ctx = { show: true, type: 'bg', x: $event.clientX, y: $event.clientY, path: '', urlPath: '', isMarkdown: false, title: '', dir: '' }">
                </div>
            @endif
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
                    ctx: { show: false, type: '', x: 0, y: 0, path: '', urlPath: '', isMarkdown: true, title: '', dir: '' },
                    dragOver: false,
                    uploadModal: false,
                    renameModal: false,
                    moveModal: false,
                    deleteModal: false,
                    quickCreateModal: false,
                    newDirModal: false,
                    renameName: '',

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

                    statusClass(status) {
                        return {
                            'bg-gray-100 text-gray-500': status === 'draft',
                            'bg-yellow-100 text-yellow-700': status === 'in_review',
                            'bg-green-100 text-green-700': status === 'approved',
                            'bg-red-100 text-red-600': status === 'obsolete',
                        };
                    },

                    openFileCtx(e, doc) {
                        e.preventDefault();
                        e.stopPropagation();
                        this.ctx = {
                            show: true, type: 'file',
                            x: e.clientX, y: e.clientY,
                            path: doc.path,
                            urlPath: doc.url_path,
                            isMarkdown: doc.path.endsWith('.md'),
                            title: doc.title,
                            dir: doc.raw_directory,
                        };
                    },

                    openDirCtx(e, dir) {
                        e.preventDefault();
                        e.stopPropagation();
                        this.ctx = {
                            show: true, type: 'dir',
                            x: e.clientX, y: e.clientY,
                            path: '', urlPath: '', isMarkdown: false, title: '', dir: dir,
                        };
                    },

                    showRename() {
                        this.ctx.show = false;
                        this.renameName = this.ctx.title;
                        this.renameModal = true;
                        this.$nextTick(() => this.$refs.browseRenameInput?.focus());
                    },

                    showMove() {
                        this.ctx.show = false;
                        this.moveModal = true;
                    },

                    showDelete() {
                        this.ctx.show = false;
                        this.deleteModal = true;
                    },

                    handleDrop(e) {
                        this.dragOver = false;
                        const files = e.dataTransfer.files;
                        if (files.length === 0) return;
                        this.uploadModal = true;
                        this.$nextTick(() => {
                            const fi = document.querySelector('#browse-upload-file');
                            if (fi) { const dt = new DataTransfer(); dt.items.add(files[0]); fi.files = dt.files; }
                            const ti = document.querySelector('#browse-upload-title');
                            if (ti && !ti.value) {
                                const name = files[0].name.replace(/\.[^.]+$/, '').replace(/[-_]/g, ' ');
                                ti.value = name.charAt(0).toUpperCase() + name.slice(1);
                            }
                        });
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
