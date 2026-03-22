<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">{{ $title }}</h2>
            <a href="{{ route('references.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200 shrink-0 ml-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to references
            </a>
        </div>
    </x-slot>

    <div x-data="refViewer()" class="flex h-full overflow-hidden">
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:leave="transition-opacity ease-in duration-150"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden"></div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 top-16 w-80 bg-white border-r border-gray-200 shadow-[2px_0_6px_-2px_rgba(0,0,0,0.06)] z-30
                      transform transition-transform duration-200 ease-in-out
                      lg:relative lg:top-0 lg:translate-x-0 lg:shrink-0 flex flex-col overflow-hidden">
            {{-- Mobile close --}}
            <div class="flex items-center justify-between px-4 h-14 border-b border-gray-200 lg:hidden shrink-0">
                <span class="font-semibold text-gray-800">Navigation</span>
                <button @click="sidebarOpen = false" class="p-1.5 rounded hover:bg-gray-100 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- File list --}}
            <div class="border-b border-gray-200 overflow-y-auto lg:border-t" style="max-height: 40%">
                <div class="px-4 pt-4 pb-2">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">References</h3>
                </div>
                <nav class="px-2 pb-2">
                    @php $currentCategory = ''; @endphp
                    @foreach($files as $file)
                        @if($file['category'] !== $currentCategory)
                            @php $currentCategory = $file['category']; @endphp
                            <div class="px-2 pt-2 pb-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ $file['category'] }}</div>
                        @endif
                        <a href="{{ route('references.show', $file['filename']) }}"
                           class="flex items-center gap-2 px-2 py-1.5 text-xs rounded mb-0.5
                                  {{ $path === $file['filename'] ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100' }}">
                            <svg class="w-3.5 h-3.5 shrink-0 {{ $path === $file['filename'] ? 'text-blue-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="truncate">{{ $file['title'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>

            {{-- Table of contents --}}
            @if(count($toc) > 0)
                <div class="flex-1 overflow-y-auto">
                    <div class="px-4 pt-4 pb-2">
                        <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Contents</h3>
                    </div>
                    <nav class="px-2 pb-4">
                        @foreach($toc as $idx => $item)
                            <a href="#{{ $item['id'] }}" @click="sidebarOpen = false"
                               :class="activeSection === '{{ $item['id'] }}' ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-100'"
                               class="block px-2 py-1 text-xs rounded truncate transition-colors">
                                {{ $item['title'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
            @endif
        </aside>

        {{-- Content --}}
        <main class="flex-1 min-w-0 flex flex-col overflow-hidden">
            {{-- Mobile top bar with hamburger --}}
            <div class="bg-white border-b border-gray-200 shadow-sm shrink-0 px-4 h-12 flex items-center lg:hidden">
                <button @click="sidebarOpen = true" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="ml-3 text-xs text-gray-500 truncate">Contents & References</span>
            </div>

            <div class="flex-1 overflow-y-scroll" x-ref="content" @scroll.throttle.100ms="onScroll()">
                <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6 lg:p-10">
                        <div class="prose prose-sm sm:prose-base max-w-none
                                    text-gray-700 prose-headings:text-gray-800
                                    prose-h1:text-xl sm:prose-h1:text-2xl prose-h1:border-b prose-h1:border-gray-200 prose-h1:pb-3 prose-h1:mb-6
                                    prose-h2:text-lg sm:prose-h2:text-xl prose-h2:mt-8 prose-h2:scroll-mt-4
                                    prose-h3:text-base prose-h3:mt-6
                                    prose-strong:text-gray-800
                                    prose-table:text-sm prose-th:bg-gray-50 prose-th:px-3 prose-th:py-2 prose-td:px-3 prose-td:py-2
                                    prose-a:text-blue-600 prose-a:break-all">
                            {!! $content !!}
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
        <script>
            function refViewer() {
                return {
                    activeSection: '',
                    sidebarOpen: false,
                    sections: @json(collect($toc)->pluck('id')->values()),

                    onScroll() {
                        var container = this.$refs.content;
                        if (!container) return;
                        var scrollTop = container.scrollTop;
                        var active = '';

                        for (var i = 0; i < this.sections.length; i++) {
                            var el = document.getElementById(this.sections[i]);
                            if (el) {
                                var offset = el.offsetTop - container.offsetTop;
                                if (offset <= scrollTop + 100) {
                                    active = this.sections[i];
                                }
                            }
                        }
                        this.activeSection = active;
                    },

                    init() {
                        this.$nextTick(() => this.onScroll());
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
