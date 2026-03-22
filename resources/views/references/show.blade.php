<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('references.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">{{ $title }}</h2>
        </div>
    </x-slot>

    <div class="flex h-full overflow-hidden">
        {{-- Table of contents sidebar --}}
        @if(count($toc) > 3)
            <aside class="hidden lg:block w-72 shrink-0 border-r border-gray-200 bg-white overflow-y-auto">
                <div class="px-4 py-4">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Contents</h3>
                    <nav class="space-y-0.5">
                        @foreach($toc as $item)
                            <a href="#{{ $item['id'] }}"
                               class="block px-2 py-1.5 text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded truncate">
                                {{ $item['title'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            </aside>
        @endif

        {{-- Content --}}
        <main class="flex-1 overflow-y-scroll min-w-0">
            <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sm:p-10">
                    <div class="prose prose-sm sm:prose-base max-w-none
                                text-gray-700 prose-headings:text-gray-800
                                prose-h1:text-xl sm:prose-h1:text-2xl prose-h1:border-b prose-h1:border-gray-200 prose-h1:pb-3 prose-h1:mb-6
                                prose-h2:text-lg sm:prose-h2:text-xl prose-h2:mt-8 prose-h2:scroll-mt-4
                                prose-h3:text-base prose-h3:mt-6
                                prose-strong:text-gray-800
                                prose-table:text-sm prose-th:bg-gray-50 prose-th:px-3 prose-th:py-2 prose-td:px-3 prose-td:py-2
                                prose-a:text-blue-600">
                        {!! $content !!}
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
