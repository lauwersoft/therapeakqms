<x-app-layout>
    @push('styles')
        <style>
            body, .min-h-screen { overflow: hidden; height: 100vh; }
            .EasyMDEContainer .CodeMirror { font-size: 14px; line-height: 1.6; font-family: Ubuntu, sans-serif; }
            .EasyMDEContainer .cm-header-1 { font-size: 1.5em; }
            .EasyMDEContainer .cm-header-2 { font-size: 1.3em; }
            .EasyMDEContainer .cm-header-3 { font-size: 1.1em; }
            .EasyMDEContainer .editor-preview, .EasyMDEContainer .editor-preview-side {
                font-family: Ubuntu, sans-serif;
                font-size: 14px;
                line-height: 1.7;
                padding: 1.5rem;
            }
            .EasyMDEContainer .editor-preview h1, .EasyMDEContainer .editor-preview-side h1 { font-size: 1.8em; font-weight: 700; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 1rem; }
            .EasyMDEContainer .editor-preview h2, .EasyMDEContainer .editor-preview-side h2 { font-size: 1.4em; font-weight: 600; margin-top: 1.5rem; }
            .EasyMDEContainer .editor-preview h3, .EasyMDEContainer .editor-preview-side h3 { font-size: 1.15em; font-weight: 600; margin-top: 1.2rem; }
            .EasyMDEContainer .editor-preview table, .EasyMDEContainer .editor-preview-side table { border-collapse: collapse; width: 100%; margin: 1rem 0; }
            .EasyMDEContainer .editor-preview th, .EasyMDEContainer .editor-preview-side th { background: #f9fafb; padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; text-align: left; font-weight: 600; }
            .EasyMDEContainer .editor-preview td, .EasyMDEContainer .editor-preview-side td { padding: 0.5rem 0.75rem; border: 1px solid #e5e7eb; }
            .EasyMDEContainer .editor-preview ul, .EasyMDEContainer .editor-preview-side ul { list-style: disc; padding-left: 1.5rem; }
            .EasyMDEContainer .editor-preview ol, .EasyMDEContainer .editor-preview-side ol { list-style: decimal; padding-left: 1.5rem; }
            .EasyMDEContainer .editor-preview a, .EasyMDEContainer .editor-preview-side a { color: #2563eb; text-decoration: underline; }
            .EasyMDEContainer .editor-toolbar { border-color: #e5e7eb; }
            .EasyMDEContainer .editor-toolbar button { color: #6b7280 !important; }
            .EasyMDEContainer .editor-toolbar button:hover { background: #f3f4f6; }
            .EasyMDEContainer .editor-toolbar button.active { background: #e5e7eb; }
        </style>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    @endpush
    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        <main class="flex-1 overflow-y-auto bg-gray-50 min-w-0">
            <div class="max-w-5xl mx-auto py-6 px-4 sm:py-8 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('documents.index', ['path' => str_replace('.md', '', $currentPath)]) }}"
                           class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Back
                        </a>
                        <span class="text-sm text-gray-400">{{ str_replace('/', ' / ', $currentPath) }}</span>
                        @if($meta['id'])
                            <span class="text-sm font-mono font-semibold text-gray-700">{{ $meta['id'] }}</span>
                        @endif
                    </div>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-4">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('documents.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="path" value="{{ $currentPath }}">

                    {{-- Metadata panel --}}
                    @if($meta['id'])
                        <div x-data="{ showMeta: false }" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3 text-sm">
                                    <span class="font-mono font-semibold text-gray-800">{{ $meta['id'] }}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $meta['status'] === 'draft' ? 'bg-gray-100 text-gray-600' : '' }}
                                        {{ $meta['status'] === 'in_review' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $meta['status'] === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $meta['status'] === 'obsolete' ? 'bg-red-100 text-red-600' : '' }}">
                                        {{ $statuses[$meta['status']] ?? ucfirst($meta['status']) }}
                                    </span>
                                    <span class="text-gray-400">v{{ $meta['version'] }}</span>
                                    @if($meta['author'])
                                        <span class="text-gray-400">{{ $meta['author'] }}</span>
                                    @endif
                                </div>
                                <button type="button" @click="showMeta = !showMeta" class="text-xs text-blue-600 hover:text-blue-800">
                                    <span x-text="showMeta ? 'Hide properties' : 'Edit properties'"></span>
                                </button>
                            </div>

                            <div x-show="showMeta" x-cloak class="mt-4 pt-4 border-t border-gray-100">
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                                        <select name="meta_status" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                            @foreach($statuses as $key => $label)
                                                <option value="{{ $key }}" {{ $meta['status'] === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Version</label>
                                        <input type="text" name="meta_version" value="{{ $meta['version'] }}"
                                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Effective date</label>
                                        <input type="date" name="meta_effective_date" value="{{ $meta['effective_date'] }}"
                                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Author</label>
                                        <input type="text" name="meta_author" value="{{ $meta['author'] }}"
                                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Editor --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 sm:p-6">
                        <textarea id="editor" name="content">{{ $content }}</textarea>
                        <div class="flex justify-end gap-2 mt-4">
                            <a href="{{ route('documents.index', ['path' => str_replace('.md', '', $currentPath)]) }}"
                               class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md border border-gray-300">Cancel</a>
                            <button type="submit"
                                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Document</button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
        <script>
            const easyMDE = new EasyMDE({
                element: document.getElementById('editor'),
                spellChecker: false,
                autosave: {
                    enabled: true,
                    uniqueId: '{{ $currentPath }}',
                    delay: 5000,
                },
                toolbar: [
                    'bold', 'italic', 'heading', '|',
                    'unordered-list', 'ordered-list', '|',
                    'link', 'table', 'horizontal-rule', '|',
                    'preview', 'side-by-side', 'fullscreen', '|',
                    'guide'
                ],
                minHeight: '400px',
                placeholder: 'Start writing your document...',
            });
        </script>
    @endpush
</x-app-layout>
