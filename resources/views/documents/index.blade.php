<x-app-layout>
    <div class="flex h-[calc(100vh-64px)]">
        {{-- Sidebar --}}
        <aside class="w-72 bg-white border-r border-gray-200 overflow-y-auto shrink-0">
            <div class="p-4 border-b border-gray-200">
                <h2 class="font-semibold text-gray-800 text-lg">QMS Documents</h2>
            </div>
            <nav class="p-3">
                @include('documents.partials.tree', ['items' => $tree, 'currentPath' => $currentPath])
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto bg-gray-50">
            <div class="max-w-4xl mx-auto py-8 px-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <div class="prose prose-gray max-w-none">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
