<div class="sortable-group" data-directory="{{ $directory ?? '' }}"
     x-init="initSortable($el, '{{ $directory ?? '' }}')">
    @foreach ($items as $item)
        @if ($item['type'] === 'directory')
            <div class="mb-1">
                <div x-data="{ open: true }">
                    <button @click="open = !open"
                            class="flex items-center w-full px-2 py-1.5 text-sm font-medium text-gray-700 rounded hover:bg-gray-100">
                        <svg class="w-4 h-4 mr-2 text-gray-400 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        {{ $item['name'] }}
                    </button>
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
            @php $fileStatus = $changedFiles[$item['path']] ?? null; @endphp
            <div class="sortable-item" data-path="{{ $item['path'] }}">
                <a href="{{ route('documents.index', ['path' => $item['path']]) }}"
                   @if($canEdit)
                       @contextmenu="openContextMenu($event, '{{ $item['path'] }}', '{{ addslashes($item['name']) }}')"
                       @dblclick.prevent="window.location='{{ route('documents.edit', ['path' => $item['path']]) }}'"
                   @endif
                   class="flex items-center px-2 py-1.5 text-sm rounded mb-0.5 cursor-pointer
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
                            {{ $fileStatus === 'new' ? 'bg-green-500' : '' }}
                            {{ $fileStatus === 'modified' ? 'bg-amber-500' : '' }}
                            {{ $fileStatus === 'deleted' ? 'bg-red-500' : '' }}
                            {{ $fileStatus === 'added' ? 'bg-green-500' : '' }}"
                              title="{{ ucfirst($fileStatus) }}">
                        </span>
                    @endif
                </a>
            </div>
        @endif
    @endforeach
</div>
