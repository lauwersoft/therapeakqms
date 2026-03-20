<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    @endpush
    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        <main class="flex-1 overflow-y-auto bg-gray-50 min-w-0">
            <div class="max-w-5xl mx-auto py-6 px-4 sm:py-8 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 mb-4">
                    <a href="{{ route('documents.index') }}"
                       class="inline-flex items-center gap-1 text-sm text-gray-600 hover:text-gray-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        Back
                    </a>
                    <span class="text-sm text-gray-400">New Document</span>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-4">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 sm:p-6">
                    <form method="POST" action="{{ route('documents.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Document type</label>
                                <select name="doc_type" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($documentTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('doc_type', 'SOP') === $key ? 'selected' : '' }}>{{ $key }} — {{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Document name</label>
                                <input type="text" name="filename" value="{{ old('filename') }}" placeholder="e.g. CAPA Procedure"
                                       class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500" autofocus required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Directory</label>
                                <select name="directory" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($directories as $value => $label)
                                        <option value="{{ $value }}" {{ old('directory', $directory) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mb-4">Document ID will be assigned automatically based on the type.</p>

                        <textarea id="editor" name="content">{{ old('content', '') }}</textarea>

                        <div class="flex justify-end gap-2 mt-4">
                            <a href="{{ route('documents.index') }}"
                               class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md border border-gray-300">Cancel</a>
                            <button type="submit"
                                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Document</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
        <script>
            const easyMDE = new EasyMDE({
                element: document.getElementById('editor'),
                spellChecker: false,
                toolbar: [
                    'bold', 'italic', 'heading', '|',
                    'unordered-list', 'ordered-list', '|',
                    'link', 'table', 'horizontal-rule', '|',
                    'preview', 'side-by-side', 'fullscreen', '|',
                    'guide'
                ],
                minHeight: '300px',
                placeholder: 'Start writing your document...',
            });
        </script>
    @endpush
</x-app-layout>
