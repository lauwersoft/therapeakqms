<div class="sortable-group" data-directory="{{ $directory ?? '' }}"
     x-init="initSortable($el, '{{ $directory ?? '' }}')">
    @foreach ($items as $item)
        @if ($item['type'] === 'directory')
            @php
                $dirId = 'dir-' . Str::slug($item['path']);
                $fileCount = collect($item['children'])->where('type', 'file')->count();
                // Build JSON array of children file data for JS filtering
                $childrenData = collect($item['children'])->where('type', 'file')->map(function ($child) {
                    $childType = ($child['doc_id'] ?? null) ? explode('-', $child['doc_id'])[0] : '';
                    return [
                        'search' => strtolower(($child['doc_id'] ?? '') . ' ' . $child['name']),
                        'type' => $childType,
                        'status' => $child['doc_status'] ?? '',
                        'doc_id' => $child['doc_id'] ?? '',
                    ];
                })->values()->toArray();
            @endphp
            <div class="mb-1"
                 x-data="{
                    children: {{ json_encode($childrenData) }},
                    get visibleCount() {
                        if (!sidebarSearch && !sidebarTypeFilter && !sidebarStatusFilter && !sidebarCommentFilter) return this.children.length;
                        var cs = typeof commentSummary !== 'undefined' ? commentSummary : {};
                        return this.children.filter(c => {
                            if (sidebarTypeFilter && c.type !== sidebarTypeFilter) return false;
                            if (sidebarStatusFilter && c.status !== sidebarStatusFilter) return false;
                            if (sidebarSearch && !c.search.includes(sidebarSearch.toLowerCase())) return false;
                            if (sidebarCommentFilter === 'with' && !(c.doc_id && cs[c.doc_id] && cs[c.doc_id].unresolved > 0)) return false;
                            if (sidebarCommentFilter === 'without' && c.doc_id && cs[c.doc_id] && cs[c.doc_id].unresolved > 0) return false;
                            return true;
                        }).length;
                    }
                 }"
                 x-show="visibleCount > 0">
                <div x-data="{ open: sessionStorage.getItem('dir_{{ Str::slug($item['path']) }}') !== 'closed', dirDragOver: false }"
                     x-effect="if (sidebarSearch || sidebarTypeFilter || sidebarStatusFilter || sidebarCommentFilter) { open = true } else { sessionStorage.setItem('dir_{{ Str::slug($item['path']) }}', open ? 'open' : 'closed') }"
                     @dirs-collapse.window="open = false; sessionStorage.setItem('dir_{{ Str::slug($item['path']) }}', 'closed')"
                     @dirs-expand.window="open = true; sessionStorage.setItem('dir_{{ Str::slug($item['path']) }}', 'open')">
                    <div class="group flex items-center gap-0.5">
                        <button @click="open = !open"
                                @if($canEdit ?? false)
                                    @contextmenu="openDirMenu($event, '{{ $item['path'] }}', '{{ addslashes($item['name']) }}')"
                                    @dragover.prevent="dirDragOver = true"
                                    @dragleave.prevent="dirDragOver = false"
                                    @drop.prevent="dirDragOver = false; handleDropToDir($event, '{{ $item['path'] }}')"
                                @endif
                                :class="dirDragOver ? 'bg-blue-200 ring-2 ring-blue-500 shadow-sm' : 'hover:bg-gray-100'"
                                class="flex items-center flex-1 min-w-0 px-2 py-1.5 text-sm font-medium text-gray-700 rounded transition-all relative z-[1]">
                            <svg class="w-4 h-4 mr-2 text-gray-400 shrink-0 rotate-90" :class="open ? 'rotate-90' : 'rotate-0'" x-init="$nextTick(() => $el.classList.add('transition-transform'))" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <svg class="w-4 h-4 mr-2 text-yellow-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                            </svg>
                            <span class="truncate">{{ $item['name'] }}</span>
                            <span class="ml-auto text-[10px] text-gray-400 shrink-0" x-text="visibleCount"></span>
                        </button>
                        @if($canEdit ?? false)
                            <div x-data="{ ddOpen: false }" class="relative shrink-0 opacity-0 group-hover:opacity-100 sm:opacity-100 sm:group-hover:opacity-100 transition-opacity">
                                <button @click.stop="ddOpen = !ddOpen" class="p-1 rounded hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/>
                                    </svg>
                                </button>
                                <div x-show="ddOpen" x-cloak @click.outside="ddOpen = false"
                                     class="absolute right-0 mt-1 w-52 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                    <div class="px-3 py-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Create here</div>
                                    <button @click="ddOpen = false; showQuickCreate('{{ $item['path'] }}')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        New document
                                    </button>
                                    <a @click="ddOpen = false" href="{{ route('forms.create') }}" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                        New form
                                    </a>
                                    <button @click="ddOpen = false; showNewSubdir('{{ $item['path'] }}')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                                        New subdirectory
                                    </button>
                                    <button @click="ddOpen = false; modal.upload = true" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                        Upload file
                                    </button>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <div class="px-3 py-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">This folder</div>
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
                    <div x-show="open" x-cloak class="ml-4">
                        @include('documents.partials.tree', [
                            'items' => $item['children'],
                            'currentPath' => $currentPath,
                            'canEdit' => $canEdit,
                            'changedFiles' => $changedFiles,
                            'commentSummary' => $commentSummary ?? [],
                            'directory' => $item['path'],
                        ])
                    </div>
                </div>
            </div>
        @else
            @php
                $fileStatus = isset($changedFiles[$item['path']]) ? $changedFiles[$item['path']]['status'] : null;
                $searchStr = strtolower(($item['doc_id'] ?? '') . ' ' . $item['name']);
                $itemDocType = $item['doc_id'] ? explode('-', $item['doc_id'])[0] : '';
                $itemDocStatus = $item['doc_status'] ?? '';
            @endphp
            <div class="sortable-item" data-path="{{ $item['path'] }}"
                 x-show="(!sidebarSearch || '{{ addslashes($searchStr) }}'.includes(sidebarSearch.toLowerCase())) && (!sidebarTypeFilter || sidebarTypeFilter === '{{ $itemDocType }}') && (!sidebarStatusFilter || sidebarStatusFilter === '{{ $itemDocStatus }}') && (!sidebarCommentFilter || (sidebarCommentFilter === 'with' ? (commentSummary && commentSummary['{{ $item['doc_id'] ?? '' }}'] && commentSummary['{{ $item['doc_id'] ?? '' }}'].unresolved > 0) : !(commentSummary && commentSummary['{{ $item['doc_id'] ?? '' }}'] && commentSummary['{{ $item['doc_id'] ?? '' }}'].unresolved > 0)))"
            >
                @php
                    $isItemMarkdown = $item['is_markdown'] ?? true;
                    $linkPath = preg_replace('/(\.\w+)+$/', '', $item['path']);
                @endphp
                <div class="group flex items-center gap-0.5">
                    <a href="{{ route('documents.index', ['path' => $linkPath]) }}"
                       @if($canEdit ?? false)
                           @contextmenu="openFileMenu($event, '{{ $item['path'] }}', '{{ addslashes($item['name']) }}')"
                           @if($isItemMarkdown)
                               @dblclick.prevent="window.location='{{ route('documents.edit', ['path' => preg_replace('/\.md$/', '', $item['path'])]) }}'"
                           @endif
                       @endif
                       @if($currentPath === $item['path']) data-active-sidebar-item @endif
                       class="flex items-center flex-1 min-w-0 px-2 py-1.5 text-sm rounded mb-0.5 cursor-pointer
                              {{ $currentPath === $item['path'] ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-100' }}">
                        @if($canEdit ?? false)
                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-300 cursor-grab shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
                            </svg>
                        @endif
                        @if($item['is_markdown'] ?? true)
                            <svg class="w-4 h-4 mr-2 shrink-0 self-start mt-0.5 {{ $currentPath === $item['path'] ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @elseif(str_ends_with($item['path'] ?? '', '.form.json'))
                            <svg class="w-4 h-4 mr-2 shrink-0 self-start mt-0.5 {{ $currentPath === $item['path'] ? 'text-blue-500' : 'text-purple-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        @else
                            <span class="w-4 h-4 mr-2 shrink-0 self-start mt-0.5 flex items-center justify-center rounded text-[8px] font-bold uppercase
                                {{ $currentPath === $item['path'] ? 'bg-blue-100 text-blue-600' : 'bg-gray-200 text-gray-500' }}">
                                {{ Str::limit($item['extension'] ?? '?', 3, '') }}
                            </span>
                        @endif
                        <span class="min-w-0 flex-1">
                            <span class="truncate block leading-tight">{{ $item['name'] }}</span>
                            @if($item['doc_id'] ?? null)
                                <span class="text-[10px] font-mono block leading-tight"><span class="px-1 py-0.5 rounded {{ \App\Services\DocumentMetadata::typeColor($itemDocType) }}">{{ $item['doc_id'] }}</span>@if($item['doc_status'] ?? null) <span class="text-gray-400">· {{ ucfirst($item['doc_status'] === 'in_review' ? 'In Review' : $item['doc_status']) }}</span>@endif</span>
                            @endif
                        </span>
                        @php
                            $itemCommentCount = ($item['doc_id'] && isset($commentSummary)) ? ($commentSummary[$item['doc_id']]['unresolved'] ?? 0) : 0;
                        @endphp
                        @if($itemCommentCount > 0)
                            <span class="ml-auto shrink-0 flex items-center gap-0.5 text-[10px] text-amber-600" title="{{ $itemCommentCount }} {{ Str::plural('comment', $itemCommentCount) }}">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                <span class="font-medium">{{ $itemCommentCount }}</span>
                            </span>
                        @elseif($fileStatus)
                            <span class="ml-auto shrink-0 w-2 h-2 rounded-full
                                {{ in_array($fileStatus, ['new', 'added']) ? 'bg-green-500' : '' }}
                                {{ $fileStatus === 'modified' ? 'bg-amber-500' : '' }}
                                {{ $fileStatus === 'deleted' ? 'bg-red-500' : '' }}
                                {{ in_array($fileStatus, ['move', 'rename']) ? 'bg-blue-500' : '' }}"
                                  title="{{ ucfirst($fileStatus) }}">
                            </span>
                        @endif
                    </a>
                    @if($canEdit ?? false)
                        <div x-data="{ ddOpen: false }" class="relative shrink-0 opacity-0 group-hover:opacity-100 sm:opacity-100 sm:group-hover:opacity-100 transition-opacity">
                            <button @click.stop="ddOpen = !ddOpen" class="p-1 rounded hover:bg-gray-200 text-gray-400 hover:text-gray-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01"/>
                                </svg>
                            </button>
                            <div x-show="ddOpen" x-cloak @click.outside="ddOpen = false"
                                 class="absolute right-0 mt-1 w-52 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                @if($isItemMarkdown)
                                    <a href="{{ route('documents.edit', ['path' => preg_replace('/\.md$/', '', $item['path'])]) }}" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                @else
                                    <a href="{{ route('documents.download', $item['path']) }}" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Download
                                    </a>
                                @endif
                                <button @click="ddOpen = false; ctx.path = '{{ $item['path'] }}'; ctx.name = '{{ addslashes($item['name']) }}'; showRename()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Rename
                                </button>
                                <button @click="ddOpen = false; ctx.path = '{{ $item['path'] }}'; showMove()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                    Move to...
                                </button>
                                <button @click="ddOpen = false; ctx.path = '{{ $item['path'] }}'; showDelete()" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Delete
                                </button>
                                <div class="border-t border-gray-100 my-1"></div>
                                <div class="px-3 py-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">Create new</div>
                                <button @click="ddOpen = false; showQuickCreate('{{ dirname($item['path']) === '.' ? '' : dirname($item['path']) }}')" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    New document
                                </button>
                                <a @click="ddOpen = false" href="{{ route('forms.create') }}" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    New form
                                </a>
                                <button @click="ddOpen = false; modal.upload = true" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    Upload file
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endforeach
</div>
