<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $meta['title'] ?? $schema['title'] ?? 'Fill Form' }}</h2>
                @if($meta['id'] ?? null)
                    <span class="text-sm font-mono text-gray-400">{{ $meta['id'] }}</span>
                @endif
            </div>
            <a href="{{ route('documents.index', ['path' => $path]) }}" class="text-sm text-gray-500 hover:text-gray-900">Back to form</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="POST" action="{{ route('forms.submit') }}">
                    @csrf
                    <input type="hidden" name="form_path" value="{{ $path }}">

                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Submission title / reference</label>
                        <input type="text" name="title" required placeholder="e.g. CAPA-2026-001"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="border-t border-gray-100 pt-5 space-y-5">
                        @foreach($schema['fields'] ?? [] as $i => $field)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $field['label'] }}
                                    @if($field['required'] ?? false)
                                        <span class="text-red-400">*</span>
                                    @endif
                                </label>

                                @if($field['type'] === 'text')
                                    <input type="text" name="fields[{{ $field['label'] }}]"
                                           {{ ($field['required'] ?? false) ? 'required' : '' }}
                                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">

                                @elseif($field['type'] === 'email')
                                    <input type="email" name="fields[{{ $field['label'] }}]"
                                           {{ ($field['required'] ?? false) ? 'required' : '' }}
                                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">

                                @elseif($field['type'] === 'number')
                                    <input type="number" name="fields[{{ $field['label'] }}]"
                                           {{ ($field['required'] ?? false) ? 'required' : '' }}
                                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">

                                @elseif($field['type'] === 'date')
                                    <input type="date" name="fields[{{ $field['label'] }}]"
                                           {{ ($field['required'] ?? false) ? 'required' : '' }}
                                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">

                                @elseif($field['type'] === 'textarea')
                                    <textarea name="fields[{{ $field['label'] }}]" rows="4"
                                              {{ ($field['required'] ?? false) ? 'required' : '' }}
                                              class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>

                                @elseif($field['type'] === 'select')
                                    <select name="fields[{{ $field['label'] }}]"
                                            {{ ($field['required'] ?? false) ? 'required' : '' }}
                                            class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">Select...</option>
                                        @foreach($field['options'] ?? [] as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>

                                @elseif($field['type'] === 'checkbox')
                                    <div class="flex items-center gap-2 mt-1">
                                        <input type="hidden" name="fields[{{ $field['label'] }}]" value="No">
                                        <input type="checkbox" name="fields[{{ $field['label'] }}]" value="Yes"
                                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-sm text-gray-600">{{ $field['label'] }}</span>
                                    </div>
                                @endif

                                @if($field['description'] ?? null)
                                    <p class="text-xs text-gray-400 mt-1">{{ $field['description'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100">
                        <a href="{{ route('documents.index', ['path' => $path]) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                        <button type="submit" class="px-5 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
