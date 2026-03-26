<x-app-layout>
    @section('page-title', $formId . ' — Records')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-xs font-mono font-semibold px-1.5 py-0.5 rounded {{ \App\Services\DocumentMetadata::typeColor('FM') }}">{{ $formId }}</span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $formTitle }}</h2>
                <span class="text-sm text-gray-400">{{ $records->count() }} {{ Str::plural('record', $records->count()) }}</span>
            </div>
            <a href="{{ route('records.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                All records
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($records->isEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <p class="text-gray-400">No submissions for this form yet.</p>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    @foreach($records as $record)
                        <a href="{{ route('records.show', $record['filename']) }}"
                           class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-mono font-medium text-gray-500">{{ $record['id'] }}</span>
                                    <span class="text-sm font-medium text-gray-800 truncate">{{ $record['title'] }}</span>
                                </div>
                                <div class="text-[11px] text-gray-400 font-mono mt-0.5">records/{{ $record['filename'] }}</div>
                                <div class="flex items-center gap-2 text-xs text-gray-400 mt-0.5">
                                    <span>{{ $record['author'] }}</span>
                                    @if($record['submitted_at'])
                                        <span>·</span>
                                        <span>{{ \Carbon\Carbon::parse($record['submitted_at'])->format('M j, Y') }}</span>
                                        <span class="text-gray-300">·</span>
                                        <span>{{ \Carbon\Carbon::parse($record['submitted_at'])->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
