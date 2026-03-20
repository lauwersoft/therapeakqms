<x-app-layout>
    @push('styles')
        <style>body, .min-h-screen { overflow: hidden; height: 100vh; }</style>
    @endpush
    <div x-data="{ sidebarOpen: false }" class="flex h-[calc(100vh-64px)] relative overflow-hidden">
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:leave="transition-opacity ease-in duration-150"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden">
        </div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 top-16 w-72 bg-white border-r border-gray-200 overflow-y-auto z-30
                      transform transition-transform duration-200 ease-in-out
                      lg:relative lg:top-0 lg:translate-x-0 lg:shrink-0">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 text-lg">Documents</h2>
                <button @click="sidebarOpen = false" class="lg:hidden p-1 rounded hover:bg-gray-100 text-gray-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <nav class="p-3" @click="sidebarOpen = false">
                @include('documents.partials.tree', ['items' => $tree, 'currentPath' => $currentPath])
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto bg-gray-50 min-w-0">
            {{-- Mobile header with menu button --}}
            <div class="lg:hidden flex items-center gap-3 px-4 py-3 bg-white border-b border-gray-200">
                <button @click="sidebarOpen = true" class="p-2 rounded-md hover:bg-gray-100 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="text-sm font-medium text-gray-600 truncate">{{ str_replace('/', ' / ', $currentPath) }}</span>
            </div>

            <div class="max-w-4xl mx-auto py-6 px-4 sm:py-8 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 sm:p-8">
                    <div class="prose prose-gray prose-sm sm:prose-base max-w-none
                                prose-headings:text-gray-900 prose-h1:text-2xl sm:prose-h1:text-3xl prose-h1:border-b prose-h1:border-gray-200 prose-h1:pb-3 prose-h1:mb-6
                                prose-h2:text-xl sm:prose-h2:text-2xl prose-h2:mt-8
                                prose-table:text-sm prose-th:bg-gray-50 prose-th:px-3 prose-th:py-2 prose-td:px-3 prose-td:py-2
                                prose-a:text-blue-600">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
