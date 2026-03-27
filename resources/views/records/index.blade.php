<x-app-layout>
    @section('page-title', 'Records')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Records</h2>
                <span class="text-sm text-gray-400">{{ $totalRecords }} {{ Str::plural('record', $totalRecords) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($forms->isEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-400">No records yet.</p>
                    <p class="text-xs text-gray-400 mt-1">Records are created when forms are submitted.</p>
                </div>
            @else
                @foreach($forms as $form)
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-mono font-semibold px-1.5 py-0.5 rounded {{ \App\Services\DocumentMetadata::typeColor('FM') }}">{{ $form['form_id'] }}</span>
                                <h3 class="text-sm font-semibold text-gray-700">{{ $form['form_title'] }}</h3>
                                <span class="text-xs text-gray-400">({{ $form['count'] }})</span>
                            </div>
                            <a href="{{ route('records.form', $form['form_id']) }}" class="text-xs text-blue-600 hover:text-blue-800">View all</a>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            @foreach($form['records'] as $record)
                                <a href="{{ route('records.show', $record['filename']) }}"
                                   class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                    <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-mono font-semibold px-1 py-0.5 rounded {{ \App\Services\DocumentMetadata::typeColor('REC') }}">{{ $record['id'] }}</span>
                                            <span class="text-sm font-medium text-gray-800 truncate">{{ $record['title'] }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-400 font-mono mt-0.5">records/{{ $record['filename'] }}</div>
                                        <div class="flex items-center gap-2 text-xs text-gray-400 mt-0.5">
                                            <span>{{ $record['author'] }}</span>
                                            @if($record['submitted_at'])
                                                <span>·</span>
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
                        @if($form['has_more'])
                            <a href="{{ route('records.form', $form['form_id']) }}" class="block mt-2 text-center text-xs text-blue-600 hover:text-blue-800 py-2">
                                {{ $form['count'] - 5 }} more {{ Str::plural('submission', $form['count'] - 5) }} →
                            </a>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
