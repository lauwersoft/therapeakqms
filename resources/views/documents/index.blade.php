<x-app-layout>
    @push('styles')
        <style>body, .min-h-screen { overflow: hidden; height: 100vh; }</style>
    @endpush
    <div x-data="{ sidebarOpen: false, showActions: false, showNewDir: false, showMove: false, showRename: false, showDelete: false }"
         class="flex h-[calc(100vh-64px)] relative overflow-hidden">
        {{-- Mobile overlay --}}
        <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-200" x-transition:leave="transition-opacity ease-in duration-150"
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden">
        </div>

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
               class="fixed inset-y-0 left-0 top-16 w-72 bg-white border-r border-gray-200 overflow-y-auto z-30
                      transform transition-transform duration-200 ease-in-out
                      lg:relative lg:top-0 lg:translate-x-0 lg:shrink-0 flex flex-col">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold text-gray-800 text-lg">Documents</h2>
                <div class="flex items-center gap-1">
                    @if($canEdit)
                        <a href="{{ route('documents.create') }}" class="p-1.5 rounded hover:bg-gray-100 text-gray-500" title="New document">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </a>
                        <button @click="showNewDir = true" class="p-1.5 rounded hover:bg-gray-100 text-gray-500" title="New directory">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                            </svg>
                        </button>
                    @endif
                    <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded hover:bg-gray-100 text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <nav class="p-3 flex-1" @click.self="sidebarOpen = false">
                @include('documents.partials.tree', ['items' => $tree, 'currentPath' => $currentPath, 'canEdit' => $canEdit])
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto bg-gray-50 min-w-0">
            {{-- Mobile header --}}
            <div class="lg:hidden flex items-center gap-3 px-4 py-3 bg-white border-b border-gray-200">
                <button @click="sidebarOpen = true" class="p-2 rounded-md hover:bg-gray-100 text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="text-sm font-medium text-gray-600 truncate">{{ str_replace('/', ' / ', $currentPath) }}</span>
            </div>

            {{-- Success message --}}
            @if(session('success'))
                <div class="max-w-4xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="max-w-4xl mx-auto py-6 px-4 sm:py-8 sm:px-6 lg:px-8">
                {{-- Action bar --}}
                @if($canEdit)
                    <div class="flex items-center gap-2 mb-4">
                        <a href="{{ route('documents.edit', ['path' => $currentPath]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <button @click="showRename = true"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                            Rename
                        </button>
                        <button @click="showMove = true"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">
                            Move
                        </button>
                        <button @click="showDelete = true"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-red-200 rounded-md text-sm text-red-600 hover:bg-red-50">
                            Delete
                        </button>
                    </div>
                @endif

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

        {{-- Rename Modal --}}
        @if($canEdit)
            <div x-show="showRename" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="showRename = false">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                    <h3 class="text-lg font-semibold mb-4">Rename Document</h3>
                    <form method="POST" action="{{ route('documents.rename') }}">
                        @csrf
                        <input type="hidden" name="path" value="{{ $currentPath }}">
                        <input type="text" name="new_name" value="{{ ucwords(str_replace(['-', '_'], ' ', str_replace('.md', '', basename($currentPath)))) }}"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500" autofocus>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="showRename = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Rename</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Move Modal --}}
            <div x-show="showMove" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="showMove = false">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                    <h3 class="text-lg font-semibold mb-4">Move Document</h3>
                    <form method="POST" action="{{ route('documents.move') }}">
                        @csrf
                        <input type="hidden" name="path" value="{{ $currentPath }}">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Move to directory</label>
                        <select name="destination" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach($directories ?? [] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="showMove = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Move</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Delete Modal --}}
            <div x-show="showDelete" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="showDelete = false">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                    <h3 class="text-lg font-semibold mb-2">Delete Document</h3>
                    <p class="text-sm text-gray-600 mb-4">Are you sure you want to delete <strong>{{ basename($currentPath) }}</strong>? This action is tracked in git and can be reverted.</p>
                    <form method="POST" action="{{ route('documents.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="path" value="{{ $currentPath }}">
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="showDelete = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                            <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white rounded-md hover:bg-red-700">Delete</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- New Directory Modal --}}
            <div x-show="showNewDir" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="showNewDir = false">
                <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6">
                    <h3 class="text-lg font-semibold mb-4">New Directory</h3>
                    <form method="POST" action="{{ route('documents.directory.store') }}">
                        @csrf
                        <label class="block text-sm font-medium text-gray-700 mb-1">Directory name</label>
                        <input type="text" name="name" placeholder="e.g. templates"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500" autofocus>
                        <label class="block text-sm font-medium text-gray-700 mb-1 mt-3">Parent directory</label>
                        <select name="parent" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach($directories ?? [] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" @click="showNewDir = false" class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">Cancel</button>
                            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
