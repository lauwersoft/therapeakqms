<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Form</h2>
            <a href="{{ route('documents.index', ['path' => $path]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to form
            </a>
        </div>
    </x-slot>

    <div x-data="formEditor()" class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- Form info --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-4 flex items-center gap-3">
                <span class="text-xs font-mono font-semibold px-1.5 py-0.5 rounded {{ \App\Services\DocumentMetadata::typeColor('FM') }}">{{ $meta['id'] }}</span>
                <span class="text-sm text-gray-600">{{ $meta['status'] }}</span>
                <span class="text-xs text-gray-400 font-mono ml-auto">/{{ $path }}</span>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="POST" action="{{ route('forms.update') }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="path" value="{{ $path }}">

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Form title</label>
                        <input type="text" name="title" required x-model="title"
                               class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="border-t border-gray-100 pt-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-gray-700">Fields</h3>
                            <span class="text-xs text-gray-400" x-text="fields.length + ' ' + (fields.length === 1 ? 'field' : 'fields')"></span>
                        </div>

                        {{-- Fields list --}}
                        <div class="space-y-3 mb-4">
                            <template x-for="(field, index) in fields" :key="index">
                                <div class="p-3 bg-gray-50 rounded-md border border-gray-200">
                                    <div class="flex items-start gap-3">
                                        {{-- Drag handle --}}
                                        <div class="flex flex-col items-center gap-0.5 pt-2 cursor-grab text-gray-300">
                                            <span class="text-[10px] font-bold text-gray-400" x-text="index + 1"></span>
                                        </div>
                                        <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                            <div>
                                                <label class="block text-[10px] text-gray-400 mb-0.5">Label</label>
                                                <input type="text" :name="'fields[' + index + '][label]'" x-model="field.label" placeholder="Field label" required
                                                       class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] text-gray-400 mb-0.5">Type</label>
                                                <select :name="'fields[' + index + '][type]'" x-model="field.type"
                                                        class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="text">Text</option>
                                                    <option value="textarea">Text area</option>
                                                    <option value="date">Date</option>
                                                    <option value="number">Number</option>
                                                    <option value="email">Email</option>
                                                    <option value="select">Dropdown</option>
                                                    <option value="checkbox">Checkbox</option>
                                                </select>
                                            </div>
                                            <div class="flex items-end gap-3">
                                                <label class="flex items-center gap-1.5 text-xs text-gray-600 pb-2">
                                                    <input type="hidden" :name="'fields[' + index + '][required]'" value="0">
                                                    <input type="checkbox" :name="'fields[' + index + '][required]'" value="1" x-model="field.required"
                                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    Required
                                                </label>
                                                <div class="flex items-center gap-1 ml-auto pb-1.5">
                                                    <button type="button" @click="moveField(index, -1)" x-show="index > 0"
                                                            class="p-1 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-200">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                                    </button>
                                                    <button type="button" @click="moveField(index, 1)" x-show="index < fields.length - 1"
                                                            class="p-1 text-gray-400 hover:text-gray-600 rounded hover:bg-gray-200">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                                    </button>
                                                    <button type="button" @click="removeField(index)"
                                                            class="p-1 text-gray-400 hover:text-red-500 rounded hover:bg-red-50">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Options for select fields --}}
                                    <div x-show="field.type === 'select'" x-cloak class="mt-2 ml-8">
                                        <label class="text-[10px] text-gray-400 mb-0.5 block">Options (comma separated)</label>
                                        <input type="text" x-model="field.options_text"
                                               @input="field.options = field.options_text.split(',').map(s => s.trim()).filter(s => s)"
                                               placeholder="e.g. Low, Medium, High, Critical"
                                               class="w-full border-gray-300 rounded-md text-xs focus:ring-blue-500 focus:border-blue-500">
                                        <template x-for="(opt, oi) in (field.options || [])" :key="'o-' + oi">
                                            <input type="hidden" :name="'fields[' + index + '][options][' + oi + ']'" :value="opt">
                                        </template>
                                    </div>
                                    {{-- Description --}}
                                    <div class="mt-2 ml-8">
                                        <label class="text-[10px] text-gray-400 mb-0.5 block">Help text (optional)</label>
                                        <input type="text" :name="'fields[' + index + '][description]'" x-model="field.description"
                                               placeholder="Help text shown below the field"
                                               class="w-full border-gray-300 rounded-md text-xs focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="addField()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-blue-600 hover:bg-blue-50 rounded-md border border-blue-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add field
                        </button>
                    </div>

                    <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100">
                        <a href="{{ route('documents.index', ['path' => $path]) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                        <button type="submit" class="px-5 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                            Save Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function formEditor() {
                var existingFields = @json($schema['fields'] ?? []);
                return {
                    title: @json($schema['title'] ?? ''),
                    fields: existingFields.map(function(f) {
                        return {
                            label: f.label || '',
                            type: f.type || 'text',
                            required: f.required || false,
                            options: f.options || [],
                            options_text: (f.options || []).join(', '),
                            description: f.description || '',
                        };
                    }),
                    addField() {
                        this.fields.push({ label: '', type: 'text', required: false, options: [], options_text: '', description: '' });
                    },
                    removeField(index) {
                        if (this.fields.length > 1) {
                            this.fields.splice(index, 1);
                        }
                    },
                    moveField(index, direction) {
                        var newIndex = index + direction;
                        if (newIndex < 0 || newIndex >= this.fields.length) return;
                        var temp = this.fields[index];
                        this.fields[index] = this.fields[newIndex];
                        this.fields[newIndex] = temp;
                        // Force Alpine to re-render
                        this.fields = [...this.fields];
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
