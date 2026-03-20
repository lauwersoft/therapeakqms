@foreach ($items as $item)
    @if ($item['type'] === 'directory')
        <div x-data="{ open: true }" class="mb-1">
            <button @click="open = !open" class="flex items-center w-full px-2 py-1.5 text-sm font-medium text-gray-700 rounded hover:bg-gray-100">
                <svg class="w-4 h-4 mr-2 text-gray-400 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <svg class="w-4 h-4 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                </svg>
                {{ $item['name'] }}
            </button>
            <div x-show="open" class="ml-4">
                @include('documents.partials.tree', ['items' => $item['children'], 'currentPath' => $currentPath])
            </div>
        </div>
    @else
        <a href="{{ route('documents.index', ['path' => $item['path']]) }}"
           class="flex items-center px-2 py-1.5 text-sm rounded mb-0.5
                  {{ $currentPath === $item['path'] ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-4 h-4 mr-2 {{ $currentPath === $item['path'] ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            {{ $item['name'] }}
        </a>
    @endif
@endforeach
