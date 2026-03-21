<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $submission->title }}</h2>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                    {{ $submission->status === 'draft' ? 'bg-gray-100 text-gray-500' : '' }}
                    {{ $submission->status === 'submitted' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $submission->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}">
                    {{ ucfirst($submission->status) }}
                </span>
            </div>
            @if($meta)
                <a href="{{ route('documents.index', ['path' => $submission->form_path]) }}" class="text-sm text-gray-500 hover:text-gray-900">Back to form</a>
            @else
                <a href="{{ route('documents.index') }}" class="text-sm text-gray-500 hover:text-gray-900">Back to Documents</a>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-green-700 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                {{-- Submission info --}}
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-4 text-sm text-gray-500">
                        <span>Form: <span class="font-mono text-gray-700">{{ $submission->form_id }}</span></span>
                        <span>·</span>
                        <span>Submitted by <span class="text-gray-700">{{ $submission->user->name }}</span></span>
                        <span>·</span>
                        <span>{{ $submission->created_at->format('M j, Y \a\t H:i') }}</span>
                    </div>
                </div>

                {{-- Filled fields --}}
                <div class="divide-y divide-gray-50">
                    @foreach($submission->data as $label => $value)
                        <div class="px-6 py-3">
                            <div class="text-xs font-medium text-gray-500 mb-1">{{ $label }}</div>
                            <div class="text-sm text-gray-800">
                                @if(is_string($value) && strlen($value) > 100)
                                    <div class="whitespace-pre-line">{{ $value }}</div>
                                @elseif($value === 'Yes')
                                    <span class="inline-flex items-center gap-1 text-green-600">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Yes
                                    </span>
                                @elseif($value === 'No' || empty($value))
                                    <span class="text-gray-400">{{ $value ?: '—' }}</span>
                                @else
                                    {{ $value }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
