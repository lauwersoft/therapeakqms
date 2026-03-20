<div class="sortable-group" data-directory="{{ $directory ?? '' }}"
     x-init="initSortable($el, '{{ $directory ?? '' }}')">
    @foreach ($items as $item)
        @if ($item['type'] === 'directory')
            <div class="mb-1">
                <div x-data="{ open: true }">
                    <div class="group flex items-center">
                        <button @click="open = !open"
                                @if($canEdit)
                                    @contextmenu="openDirMenu($event, '{{ $item['path'] }}', '{{ addslashes($item['name']) }}')"
                                @endif
                                class="flex items-center flex-1 min-w-0 px-2 py-1.5 text-sm font-medium text-gray-700 rounded hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-2 text-gray-400 transition-transform shrink-0" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <svg class="w-4 h-4 mr-2 text-yellow-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                            </svg>
                            <span class="truncate">{{ $item['name'] }}</span>
                        </button>
                        @if($canEdit)
                            <div x-data="{ ddOpen: false }" class="relative shrink-0 opacity-0 group-hover:opacity-100 sm:opacity-100 sm:group-hover:opacity-100 transition-opacity">
                                <button @click.stop="ddOpen = !ddOpen" class="p-1 rounded hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/>
                                    </svg>
                                </button>
                                <div x-show="ddOpen" x-cloak @click.outside="ddOpen = false"
                                     class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                    <button @click="ddOpen = false; showQuickCreate('{{ $item['path'] }}')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        New file here
                                    </button>
                                    <button @click="ddOpen = false; showNewSubdir('{{ $item['path'] }}')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                        New subdirectory
                                    </button>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <button @click="ddOpen = false; ctx.dirPath = '{{ $item['path'] }}'; ctx.dirName = '{{ addslashes($item['name']) }}'; showRenameDir()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Rename
                                    </button>
                                    <button @click="ddOpen = false; ctx.dirPath = '{{ $item['path'] }}'; showDeleteDir()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div x-show="open" class="ml-4">
                        @include('documents.partials.tree', [
                            'items' => $item['children'],
                            'currentPath' => $currentPath,
                            'canEdit' => $canEdit,
                            'changedFiles' => $changedFiles,
                            'directory' => $item['path'],
                        ])
                    </div>
                </div>
            </div>
        @else
            @php $fileStatus = isset($changedFiles[$item['path']]) ? $changedFiles[$item['path']]['status'] : null; @endphp
            <div class="sortable-item" data-path="{{ $item['path'] }}">
                <div class="group flex items-center">
                    <a href="{{ route('documents.index', ['path' => $item['path']]) }}"
                       @if($canEdit)
                           @contextmenu="openFileMenu($event, '{{ $item['path'] }}', '{{ addslashes($item['name']) }}')"
                           @dblclick.prevent="window.location='{{ route('documents.edit', ['path' => $item['path']]) }}'"
                       @endif
                       class="flex items-center flex-1 min-w-0 px-2 py-1.5 text-sm rounded mb-0.5 cursor-pointer
                              {{ $currentPath === $item['path'] ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        @if($canEdit)
                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-300 cursor-grab shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                        @endif
                        <svg class="w-4 h-4 mr-2 shrink-0 {{ $currentPath === $item['path'] ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="truncate">{{ $item['name'] }}</span>
                        @if($fileStatus)
                            <span class="ml-auto shrink-0 w-2 h-2 rounded-full
                                {{ in_array($fileStatus, ['new', 'added']) ? 'bg-green-500' : '' }}
                                {{ $fileStatus === 'modified' ? 'bg-amber-500' : '' }}
                                {{ $fileStatus === 'deleted' ? 'bg-red-500' : '' }}
                                {{ in_array($fileStatus, ['move', 'rename']) ? 'bg-blue-500' : '' }}"
                                  title="{{ ucfirst($fileStatus) }}">
                            </span>
                        @endif
                    </a>
                    @if($canEdit)
                        <div x-data="{ ddOpen: false }" class="relative shrink-0 opacity-0 group-hover:opacity-100 sm:opacity-100 sm:group-hover:opacity-100 transition-opacity">
                            <button @click.stop="ddOpen = !ddOpen" class="p-1 rounded hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/>
                                </svg>
                            </button>
                            <div x-show="ddOpen" x-cloak @click.outside="ddOpen = false"
                                 class="absolute right-0 mt-1 w-40 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                <a href="{{ route('documents.edit', ['path' => $item['path']]) }}" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                <button @click="ddOpen = false; ctx.path = '{{ $item['path'] }}'; ctx.name = '{{ addslashes($item['name']) }}'; showRename()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Rename
                                </button>
                                <button @click="ddOpen = false; ctx.path = '{{ $item['path'] }}'; showMove()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                    Move to...
                                </button>
                                <div class="border-t border-gray-100 my-1"></div>
                                <button @click="ddOpen = false; ctx.path = '{{ $item['path'] }}'; showDelete()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endforeach
</div>
