<x-app-layout>
    @section('page-title', 'New Form')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Form</h2>
            <a href="{{ route('documents.index') }}" class="text-sm text-gray-500 hover:text-gray-900"><span class="hidden sm:inline">Back to Documents</span><span class="sm:hidden">Back</span></a>
        </div>
    </x-slot>

    <div x-data="formBuilder()" class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="POST" action="{{ route('forms.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Form title</label>
                            <input type="text" name="title" required placeholder="e.g. CAPA Form"
                                   class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Directory</label>
                            <select name="directory" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach($directories as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-5">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-gray-700">Fields</h3>
                        </div>

                        {{-- Fields list --}}
                        <div class="space-y-3 mb-4">
                            <template x-for="(field, index) in fields" :key="index">
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-md border border-gray-200">
                                    <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                        <input type="text" :name="'fields[' + index + '][label]'" x-model="field.label" placeholder="Field label" required
                                               class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                        <select :name="'fields[' + index + '][type]'" x-model="field.type"
                                                class="border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                            <option value="text">Text</option>
                                            <option value="textarea">Text area</option>
                                            <option value="date">Date</option>
                                            <option value="number">Number</option>
                                            <option value="email">Email</option>
                                            <option value="select">Dropdown</option>
                                            <option value="checkbox">Checkbox</option>
                                        </select>
                                        <div class="flex items-center gap-3">
                                            <label class="flex items-center gap-1.5 text-xs text-gray-600">
                                                <input type="hidden" :name="'fields[' + index + '][required]'" value="0">
                                                <input type="checkbox" :name="'fields[' + index + '][required]'" value="1" x-model="field.required"
                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                Required
                                            </label>
                                            <button type="button" @click="removeField(index)" class="text-gray-400 hover:text-red-500 ml-auto">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template x-for="(field, index) in fields" :key="'opt-' + index">
                                <div x-show="field.type === 'select'" class="ml-3 pl-3 border-l-2 border-gray-200">
                                    <label class="text-xs text-gray-500 mb-1 block">Options for "<span x-text="field.label"></span>" (comma separated)</label>
                                    <input type="text" :name="'fields[' + index + '][options_text]'" x-model="field.options_text"
                                           placeholder="e.g. Low, Medium, High, Critical" @input="field.options = field.options_text.split(',').map(s => s.trim()).filter(s => s)"
                                           class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                    <template x-for="(opt, oi) in (field.options || [])" :key="'o-' + oi">
                                        <input type="hidden" :name="'fields[' + index + '][options][' + oi + ']'" :value="opt">
                                    </template>
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
                        <a href="{{ route('documents.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                        <button type="submit" class="px-5 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                            Create Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function formBuilder() {
                return {
                    fields: [
                        { label: '', type: 'text', required: false, options: [], options_text: '' },
                    ],
                    addField() {
                        this.fields.push({ label: '', type: 'text', required: false, options: [], options_text: '' });
                    },
                    removeField(index) {
                        if (this.fields.length > 1) {
                            this.fields.splice(index, 1);
                        }
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
