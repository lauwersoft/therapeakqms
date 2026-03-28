<x-app-layout>
    @section('page-title', $title)
    <div x-data="refViewer()" class="flex lg:h-full lg:overflow-hidden">
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:leave="transition-opacity ease-in duration-150"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 z-[60] lg:hidden" style="display:none;" x-cloak></div>

        {{-- Sidebar --}}
        <aside x-effect="if(window.innerWidth<1024){document.body.style.overflow=sidebarOpen?'hidden':''}" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 top-0 w-80 bg-white border-r border-gray-200 shadow-[2px_0_6px_-2px_rgba(0,0,0,0.06)] z-[70]
                      -translate-x-full transform transition-transform duration-200 ease-in-out
                      lg:relative lg:top-0 lg:z-30 lg:translate-x-0 lg:shrink-0 flex flex-col overflow-hidden">
            {{-- Sidebar header --}}
            <div class="px-4 h-16 border-b border-gray-200 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-2">
                    <h2 class="font-semibold text-gray-800 text-lg">Contents</h2>
                </div>
                <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded hover:bg-gray-100 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Document selector dropdown --}}
            <div class="px-3 py-3 border-b border-gray-200 shrink-0">
                <div x-data="{ refOpen: false }" class="relative">
                    <button @click="refOpen = !refOpen" class="w-full flex items-center justify-between gap-2 px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-left hover:bg-gray-100 transition-colors">
                        <div class="flex items-center gap-2 min-w-0">
                            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="truncate font-medium text-gray-700">{{ $title }}</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform" :class="refOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="refOpen" x-cloak @click.outside="refOpen = false"
                         x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                         class="absolute left-0 right-0 mt-1 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50 max-h-72 overflow-y-auto">
                        @php $currentCategory = ''; @endphp
                        @foreach($files as $file)
                            @if($file['category'] !== $currentCategory)
                                @php $currentCategory = $file['category']; @endphp
                                <div class="px-3 pt-2 pb-1 text-[10px] font-medium text-gray-400 uppercase tracking-wider">{{ $file['category'] }}</div>
                            @endif
                            <a href="{{ route('references.show', $file['filename']) }}"
                               @click="refOpen = false"
                               class="block px-3 py-1.5 text-xs {{ $path === $file['filename'] ? 'bg-blue-50 text-blue-700 font-medium' : 'text-gray-600 hover:bg-gray-50' }}">
                                {{ $file['title'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Table of contents --}}
            @if(count($toc) > 0)
                <div class="flex-1 overflow-y-auto overscroll-contain" x-ref="tocContainer">
                    <nav class="px-2 py-2">
                        @foreach($toc as $idx => $item)
                            <a href="#{{ $item['id'] }}" @click.prevent="sidebarOpen = false; var el = document.getElementById('{{ $item['id'] }}'); if(el){ if(window.innerWidth<1024){ window.scrollTo({top: el.getBoundingClientRect().top + window.scrollY - 140, behavior:'smooth'}); } else { var c=$refs.content; c.scrollTop = el.offsetTop - c.offsetTop - 20; } highlightRefSection('{{ $item['id'] }}'); }"
                               :id="'toc-' + '{{ $item['id'] }}'"
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
        <main class="flex-1 min-w-0 flex flex-col lg:overflow-hidden">
            {{-- Top bar --}}
            <div x-data="{ barZ: false }" x-effect="if (sidebarOpen) { barZ = true } else { setTimeout(() => barZ = false, 200) }"
                 class="bg-white border-b border-gray-200 shadow-sm shrink-0 sticky top-[65px] lg:relative lg:top-0 px-4 h-16 flex items-center" :class="barZ ? 'z-0' : 'z-40'">
                <div class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-3 min-w-0">
                        <button @click="sidebarOpen = true" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-400 lg:hidden shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <span class="text-base font-semibold text-gray-800 truncate">{{ $title }}</span>
                    </div>
                    <a href="{{ route('references.index') }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200 shrink-0">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span class="hidden sm:inline">Back to references</span><span class="sm:hidden">Back</span>
                    </a>
                </div>
            </div>

            <div class="flex-1 lg:overflow-y-scroll" x-ref="content" @scroll.throttle.100ms="onScroll()">
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
            function highlightRefSection(id) {
                var el = document.getElementById(id);
                if (!el) return;
                // If target is a small anchor tag, find the nearest heading sibling
                if (el.tagName === 'A') {
                    var sibling = el.nextElementSibling;
                    while (sibling && sibling.tagName === 'A') sibling = sibling.nextElementSibling;
                    if (sibling && /^H[234]$/i.test(sibling.tagName)) el = sibling;
                }
                el.style.transition = 'none';
                el.style.backgroundColor = 'rgba(59, 130, 246, 0.15)';
                setTimeout(function() {
                    el.style.transition = 'background-color 1.5s ease-out';
                    el.style.backgroundColor = '';
                }, 800);
            }

            function refViewer() {
                var isMobile = window.innerWidth < 1024;
                return {
                    activeSection: '',
                    sidebarOpen: false,
                    sections: @json(collect($toc)->pluck('id')->values()),

                    getScrollContainer() {
                        return isMobile ? document.documentElement : this.$refs.content;
                    },

                    getScrollTop() {
                        return isMobile ? window.scrollY : (this.$refs.content?.scrollTop || 0);
                    },

                    getScrollHeight() {
                        var c = this.getScrollContainer();
                        return isMobile ? document.body.scrollHeight : (c?.scrollHeight || 0);
                    },

                    getClientHeight() {
                        return isMobile ? window.innerHeight : (this.$refs.content?.clientHeight || 0);
                    },

                    onScroll() {
                        var scrollTop = this.getScrollTop();
                        var active = '';
                        var stickyOffset = isMobile ? 150 : 100;

                        if (scrollTop + this.getClientHeight() >= this.getScrollHeight() - 10) {
                            active = this.sections[this.sections.length - 1];
                        } else {
                            for (var i = 0; i < this.sections.length; i++) {
                                var el = document.getElementById(this.sections[i]);
                                if (el) {
                                    var rect = el.getBoundingClientRect();
                                    var offset = isMobile ? rect.top + window.scrollY : (el.offsetTop - (this.$refs.content?.offsetTop || 0));
                                    if (offset <= scrollTop + stickyOffset) {
                                        active = this.sections[i];
                                    }
                                }
                            }
                        }
                        if (active !== this.activeSection) {
                            this.activeSection = active;
                            this.$nextTick(() => {
                                var tocItem = document.getElementById('toc-' + active);
                                if (tocItem && this.$refs.tocContainer) {
                                    tocItem.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                                }
                            });
                        }
                    },

                    init() {
                        this.$nextTick(() => {
                            this.onScroll();

                            // Listen on window scroll for mobile
                            if (isMobile) {
                                window.addEventListener('scroll', () => this.onScroll(), { passive: true });
                            }

                            // Scroll to and highlight section if URL has a hash
                            if (window.location.hash) {
                                var id = window.location.hash.substring(1);
                                var el = document.getElementById(id);
                                if (!el) {
                                    var all = document.querySelectorAll('[id]');
                                    for (var i = 0; i < all.length; i++) {
                                        if (all[i].id.startsWith(id)) { el = all[i]; break; }
                                    }
                                }
                                if (el) {
                                    if (isMobile) {
                                        window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - 140 });
                                    } else {
                                        var container = this.$refs.content;
                                        var elTop = el.getBoundingClientRect().top - container.getBoundingClientRect().top + container.scrollTop;
                                        container.scrollTop = elTop - 20;
                                    }
                                    highlightRefSection(el.id);
                                }
                            }
                        });
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>
