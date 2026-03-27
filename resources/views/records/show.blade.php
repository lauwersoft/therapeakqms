<x-app-layout>
    @section('page-title', ($record['id'] ?? '') . ' — ' . ($record['title'] ?? 'Record'))
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">{{ $record['title'] ?? 'Record' }}</h2>
                @if($record['id'] ?? null)
                    <span class="text-xs font-mono font-semibold px-1.5 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor('REC') }}">{{ $record['id'] }}</span>
                @endif
            </div>
            <a href="{{ route('records.index') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200 shrink-0">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to records
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-xs text-gray-400 flex items-center gap-2 mb-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="font-mono">records/{{ $filename }}</span>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                {{-- Record info header --}}
                <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center gap-2 mb-2">
                        @if($record['id'] ?? null)
                            <span class="text-xs font-mono font-semibold px-1.5 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor('REC') }}">{{ $record['id'] }}</span>
                        @endif
                        <span class="text-sm font-medium text-gray-800">{{ $record['title'] ?? '' }}</span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-medium bg-blue-100 text-blue-700 ml-auto">Submitted</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                        @if($record['form_id'] ?? null)
                            <a href="{{ $record['form_path'] ? route('documents.index', ['path' => $record['form_path']]) : '#' }}" class="inline-flex items-center gap-1 hover:text-blue-600 transition-colors">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                Form: <span class="font-mono font-medium text-blue-600 ml-1">{{ $record['form_id'] }}</span>
                                @if($record['form_title'] ?? null)
                                    <span class="text-gray-400">{{ $record['form_title'] }}</span>
                                @endif
                            </a>
                            <span class="text-gray-300">·</span>
                        @endif
                        @if($record['author'] ?? null)
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                {{ $record['author'] }}
                            </span>
                            <span class="text-gray-300">·</span>
                        @endif
                        @if($record['submitted_at'] ?? null)
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ usertime($record['submitted_at'], 'M j, Y \a\t H:i') }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Filled fields --}}
                <div class="divide-y divide-gray-50">
                    @foreach(($record['data'] ?? []) as $label => $value)
                        <div class="px-6 py-3.5">
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

                {{-- Link to form template --}}
                @if($record['form_path'] ?? null)
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                        <a href="{{ route('documents.index', ['path' => $record['form_path']]) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 inline-flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            View form template ({{ $record['form_id'] ?? '' }})
                        </a>
                    </div>
                @endif
            </div>

            {{-- Actions --}}
            @if(auth()->user()->isAdmin())
                <div class="mt-4 flex justify-end">
                    <form method="POST" action="{{ route('records.destroy', $filename) }}" onsubmit="return confirm('Delete this record? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 rounded-md border border-red-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete record
                        </button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
