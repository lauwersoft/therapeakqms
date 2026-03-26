<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $meta['title'] ?? $schema['title'] ?? 'Fill Form' }}</h2>
                @if($meta['id'] ?? null)
                    <span class="text-sm font-mono text-gray-400">{{ $meta['id'] }}</span>
                @endif
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('records.form', $meta['id'] ?? '') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Records
                </a>
                <a href="{{ route('documents.index', ['path' => $path]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back to form
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Form info card --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex items-center gap-2 mb-1.5">
                    @if($meta['id'] ?? null)
                        <span class="text-[10px] font-mono font-semibold px-1.5 py-0.5 rounded {{ \App\Services\DocumentMetadata::typeColor($meta['type'] ?? 'FM') }}">{{ $meta['id'] }}</span>
                    @endif
                    <span class="text-sm font-medium text-gray-800">{{ $meta['title'] ?? $schema['title'] ?? 'Form' }}</span>
                    @if($meta['status'] ?? null)
                        <span class="text-[10px] font-medium px-1.5 py-0.5 rounded
                            {{ $meta['status'] === 'approved' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $meta['status'] === 'draft' ? 'bg-gray-100 text-gray-500' : '' }}
                            {{ $meta['status'] === 'in_review' ? 'bg-yellow-100 text-yellow-700' : '' }}">{{ ucfirst($meta['status']) }}</span>
                    @endif
                </div>
                <div class="text-[11px] text-gray-400 font-mono mb-1.5">documents/{{ $path }}</div>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    @if($meta['version'] ?? null)<span>v{{ $meta['version'] }}</span>@endif
                    @if($meta['author'] ?? null)<span>Author: {{ $meta['author'] }}</span>@endif
                    <span>{{ count($schema['fields'] ?? []) }} {{ Str::plural('field', count($schema['fields'] ?? [])) }}</span>
                </div>
            </div>

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
