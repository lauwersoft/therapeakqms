@php
    $sidebarCanEdit = $sidebarCanEdit ?? $canEdit ?? false;
@endphp

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:leave="transition-opacity ease-in duration-150"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-gray-900/50 z-[60] lg:hidden" style="display:none;" x-cloak></div>

{{-- Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       @if($sidebarCanEdit)
           @dragover.prevent="dragOver = true"
           @drop.prevent="handleDrop($event)"
       @endif
       class="fixed inset-y-0 left-0 top-0 w-80 bg-white border-r border-gray-200 shadow-[2px_0_6px_-2px_rgba(0,0,0,0.06)] overflow-y-auto z-[70]
              -translate-x-full transform transition-transform duration-200 ease-in-out
              lg:relative lg:top-0 lg:z-30 lg:translate-x-0 lg:shrink-0 flex flex-col">
    <div class="px-4 h-16 border-b border-gray-200 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2">
            <h2 class="font-semibold text-gray-800 text-lg">Documents</h2>
            <span class="text-xs text-gray-400">{{ count($sidebarDocs) }}</span>
        </div>
        <div class="flex items-center gap-1">
            @if($sidebarCanEdit)
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
        @php
            $cs = $commentSummary ?? [];
            $withComments = collect($sidebarDocs)->filter(fn($d) => isset($cs[$d['doc_id'] ?? '']) && ($cs[$d['doc_id']]['unresolved'] ?? 0) > 0)->count();
            $withoutComments = count($sidebarDocs) - $withComments;
        @endphp
        <div>
            <select x-model="sidebarCommentFilter" class="w-full text-[11px] border-gray-200 rounded-md py-1 pl-2 pr-6 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All</option>
                <option value="with">💬 With comments ({{ $withComments }})</option>
                <option value="without">No comments ({{ $withoutComments }})</option>
            </select>
        </div>
        <button x-show="sidebarSearch || sidebarTypeFilter || sidebarStatusFilter || sidebarCommentFilter" x-cloak
                @click="sidebarSearch = ''; sidebarTypeFilter = ''; sidebarStatusFilter = ''; sidebarCommentFilter = ''"
                class="text-[11px] text-blue-500 hover:text-blue-700">Clear filters</button>
    </div>
    <nav id="sidebar-nav" class="p-3 flex-1 flex flex-col overflow-y-auto" onclick="if(event.target.closest('a'))sessionStorage.setItem('sidebarScroll',this.scrollTop)">
        <div>
            @include('documents.partials.tree', ['items' => $tree, 'currentPath' => $currentPath, 'canEdit' => $sidebarCanEdit, 'changedFiles' => $changedFiles, 'commentSummary' => $commentSummary ?? []])
        </div>
        @if($sidebarCanEdit)
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
    <script>
        (function(){
            var n=document.getElementById('sidebar-nav');if(!n)return;
            var s=sessionStorage.getItem('sidebarScroll');
            if(s!==null){n.scrollTop=parseInt(s)}
            else{var a=n.querySelector('[data-active-sidebar-item]');if(a){n.scrollTop=a.offsetTop-n.offsetTop-n.clientHeight/2}}
        })();
    </script>
    @if($pendingCount > 0)
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
