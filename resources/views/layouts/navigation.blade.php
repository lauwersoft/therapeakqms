<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 relative z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 lg:-my-px lg:ms-10 lg:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @php $navPendingCount = \App\Models\DocumentChange::count(); @endphp
                    <x-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index') || request()->routeIs('documents.edit') || request()->routeIs('documents.create') || request()->routeIs('documents.changes')">
                        {{ __('Documents') }}
                        @if($navPendingCount > 0)
                            <span class="ml-1 w-2 h-2 bg-amber-500 rounded-full inline-block"></span>
                        @endif
                    </x-nav-link>
                    <x-nav-link :href="route('documents.browse')" :active="request()->routeIs('documents.browse')">
                        {{ __('Browser') }}
                    </x-nav-link>
                    <x-nav-link :href="route('documents.history')" :active="request()->routeIs('documents.history') || request()->routeIs('documents.revision')">
                        {{ __('History') }}
                    </x-nav-link>
                    <x-nav-link :href="route('records.index')" :active="request()->routeIs('records.*')">
                        {{ __('Records') }}
                    </x-nav-link>
                    <x-nav-link :href="route('references.index')" :active="request()->routeIs('references.*')">
                        {{ __('References') }}
                    </x-nav-link>
                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.guide')" :active="request()->routeIs('admin.guide')">
                            {{ __('Guide') }}
                        </x-nav-link>
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            {{ __('Users') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-1.5">
                                {{ Auth::user()->name }}
                                <span class="text-[10px] font-medium px-1 py-0.5 rounded
                                    {{ Auth::user()->role === 'admin' ? 'bg-purple-100 text-purple-600' : '' }}
                                    {{ Auth::user()->role === 'editor' ? 'bg-blue-100 text-blue-600' : '' }}
                                    {{ Auth::user()->role === 'auditor' ? 'bg-gray-100 text-gray-500' : '' }}">{{ ucfirst(Auth::user()->role) }}</span>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Overlay (starts below the nav bar) -->
    <div x-show="open"
         x-transition:enter="transition-opacity ease-out duration-[50ms] delay-[50ms]"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="open = false" class="fixed left-0 right-0 bottom-0 bg-gray-900/50 lg:hidden" style="z-index: 45; top: 4rem; display: none;" x-cloak></div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden absolute left-0 right-0 bg-white shadow-lg border-b border-gray-200" style="z-index: 55;" x-cloak>
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('documents.index')" :active="request()->routeIs('documents.index') || request()->routeIs('documents.edit') || request()->routeIs('documents.create') || request()->routeIs('documents.changes')">
                {{ __('Documents') }}
                @if($navPendingCount > 0)
                    <span class="ml-1 w-2 h-2 bg-amber-500 rounded-full inline-block"></span>
                @endif
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('documents.browse')" :active="request()->routeIs('documents.browse')">
                {{ __('Browser') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('documents.history')" :active="request()->routeIs('documents.history') || request()->routeIs('documents.revision')">
                {{ __('History') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('records.index')" :active="request()->routeIs('records.*')">
                {{ __('Records') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('references.index')" :active="request()->routeIs('references.*')">
                {{ __('References') }}
            </x-responsive-nav-link>
            @if(Auth::user()->isAdmin())
                <x-responsive-nav-link :href="route('admin.guide')" :active="request()->routeIs('admin.guide')">
                    {{ __('Guide') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 flex items-center gap-1.5">
                    {{ Auth::user()->name }}
                    <span class="text-[10px] font-medium px-1 py-0.5 rounded
                        {{ Auth::user()->role === 'admin' ? 'bg-purple-100 text-purple-600' : '' }}
                        {{ Auth::user()->role === 'editor' ? 'bg-blue-100 text-blue-600' : '' }}
                        {{ Auth::user()->role === 'auditor' ? 'bg-gray-100 text-gray-500' : '' }}">{{ ucfirst(Auth::user()->role) }}</span>
                </div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
