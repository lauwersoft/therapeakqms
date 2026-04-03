@php
    $sidebarCanEdit = $sidebarCanEdit ?? $canEdit ?? false;
@endphp

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:leave="transition-opacity ease-in duration-150"
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-gray-900/50 z-[60] lg:hidden" style="display:none;" x-cloak></div>

{{-- Sidebar --}}
<aside x-effect="if(window.innerWidth<1024){document.body.style.overflow=sidebarOpen?'hidden':''}" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       @if($sidebarCanEdit)
           @dragover.prevent="dragOver = true"
           @drop.prevent="handleDrop($event)"
       @endif
       class="fixed inset-y-0 left-0 top-0 w-80 bg-white border-r border-gray-200 shadow-[2px_0_6px_-2px_rgba(0,0,0,0.06)] overflow-hidden z-[70]
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
        <div>
            <select x-model="sidebarCategoryFilter" class="w-full text-[11px] border-gray-200 rounded-md py-1 pl-2 pr-6 bg-gray-50 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All categories</option>
                @foreach(\App\Services\DocumentMetadata::CATEGORIES as $catKey => $catLabel)
                    <option value="{{ $catKey }}">{{ $catLabel }} ({{ collect($sidebarDocs)->filter(fn($d) => is_array($d['category'] ?? null) ? in_array($catKey, $d['category']) : ($d['category'] ?? '') === $catKey)->count() }})</option>
                @endforeach
            </select>
        </div>
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
        <button x-show="sidebarSearch || sidebarCategoryFilter || sidebarTypeFilter || sidebarStatusFilter || sidebarCommentFilter" x-cloak
                @click="sidebarSearch = ''; sidebarCategoryFilter = ''; sidebarTypeFilter = ''; sidebarStatusFilter = ''; sidebarCommentFilter = ''; sessionStorage.removeItem('sidebarCategoryFilter'); sessionStorage.removeItem('sidebarTypeFilter'); sessionStorage.removeItem('sidebarStatusFilter'); sessionStorage.removeItem('sidebarCommentFilter');"
                class="text-[11px] text-blue-500 hover:text-blue-700">Clear filters</button>
        <div class="flex gap-1.5">
            <button @click="$dispatch('dirs-collapse')" class="flex-1 flex items-center justify-center gap-1 text-[11px] border border-gray-200 rounded-md py-1 bg-gray-50 text-gray-500 hover:text-gray-700 hover:bg-gray-100">
                <svg class="w-3 h-3" viewBox="0 0 16 16" fill="currentColor"><path d="M1 3.5A1.5 1.5 0 012.5 2h3.879a1.5 1.5 0 011.06.44l1.122 1.12A1.5 1.5 0 009.62 4H13.5A1.5 1.5 0 0115 5.5v.5H1v-2.5z"/><path d="M1 7v5.5A1.5 1.5 0 002.5 14h11a1.5 1.5 0 001.5-1.5V7H1zm6 2h2v1.5l1.5-1.5L12 10.5 8 14.5l-4-4L5.5 9 7 10.5V9z"/></svg>
                Collapse all
            </button>
            <button @click="$dispatch('dirs-expand')" class="flex-1 flex items-center justify-center gap-1 text-[11px] border border-gray-200 rounded-md py-1 bg-gray-50 text-gray-500 hover:text-gray-700 hover:bg-gray-100">
                <svg class="w-3 h-3" viewBox="0 0 16 16" fill="currentColor"><path d="M1 3.5A1.5 1.5 0 012.5 2h3.879a1.5 1.5 0 011.06.44l1.122 1.12A1.5 1.5 0 009.62 4H13.5A1.5 1.5 0 0115 5.5v.5H1v-2.5z"/><path d="M1 7v5.5A1.5 1.5 0 002.5 14h11a1.5 1.5 0 001.5-1.5V7H1zm6 5.5V11L5.5 12.5 4 11l4-4 4 4-1.5 1.5L9 11v1.5H7z"/></svg>
                Expand all
            </button>
        </div>
    </div>
    <style id="sidebar-hide">#sidebar-nav>div{visibility:hidden}</style>
    <nav id="sidebar-nav" class="p-3 flex-1 flex flex-col overflow-y-auto overscroll-contain touch-pan-y"
         onclick="if(event.target.closest('a'))sessionStorage.setItem('sidebarClickNav','1')">
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
            var isMobile=window.innerWidth<1024;
            // 1. Find active item
            var active=n.querySelector('[data-active-sidebar-item]');
            var fromSidebar=sessionStorage.getItem('sidebarClickNav');
            var isReload=performance.getEntriesByType&&performance.getEntriesByType('navigation')[0]&&performance.getEntriesByType('navigation')[0].type==='reload';
            var keepScroll=fromSidebar||isReload;
            sessionStorage.removeItem('sidebarClickNav');

            // 2. Collapse closed directories, but open the one containing the active item if linking from outside
            var activeParentId=null;
            if(active&&!keepScroll){
                var p=active.closest('[id^="dir-children-"]');
                if(p)activeParentId=p.id;
            }
            for(var i=0;i<sessionStorage.length;i++){
                var k=sessionStorage.key(i);
                if(k.startsWith('dir_')&&sessionStorage.getItem(k)==='closed'){
                    var elId='dir-children-'+k.substring(4);
                    if(elId===activeParentId){
                        // Open this directory — active item is inside
                        sessionStorage.setItem(k,'open');
                    }else{
                        var el=document.getElementById(elId);
                        if(el)el.style.display='none';
                    }
                }
            }

            // 3. Restore scroll position
            var s=sessionStorage.getItem('sidebarScroll');
            var hasFilters=sessionStorage.getItem('sidebarCategoryFilter')||sessionStorage.getItem('sidebarTypeFilter')||sessionStorage.getItem('sidebarStatusFilter')||sessionStorage.getItem('sidebarCommentFilter');
            function restoreScroll(){
                if(!isMobile){
                    if(keepScroll){
                        if(s!==null)n.scrollTop=parseInt(s);
                    }else{
                        var a=active&&active.offsetParent!==null?active:n.querySelector('[data-active-sidebar-item]');
                        if(a&&a.offsetParent!==null)n.scrollTop=a.offsetTop-n.offsetTop-n.clientHeight/2;
                    }
                }else{
                    if(active&&active.offsetParent!==null)active.scrollIntoView({block:'center',behavior:'instant'});
                }
            }
            if(hasFilters){
                setTimeout(restoreScroll,150);
            }else{
                restoreScroll();
            }
            if(!isMobile){
                window.addEventListener('beforeunload',function(){sessionStorage.setItem('sidebarScroll',n.scrollTop)});
            }
            // 4. Show content
            var h=document.getElementById('sidebar-hide');if(h)h.remove();
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
