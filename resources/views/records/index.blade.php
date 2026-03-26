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
            <p class="text-sm text-gray-500 mb-6">Form submissions and QMS records, grouped by form.</p>

            @if($forms->isEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-400">No records yet.</p>
                    <p class="text-xs text-gray-400 mt-1">Records are created when forms are submitted.</p>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    @foreach($forms as $form)
                        <a href="{{ route('records.form', $form['form_id']) }}"
                           class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-mono font-semibold px-1.5 py-0.5 rounded {{ \App\Services\DocumentMetadata::typeColor('FM') }}">{{ $form['form_id'] }}</span>
                                    <span class="text-sm font-medium text-gray-800 truncate">{{ $form['form_title'] }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-400 mt-0.5">
                                    <span>{{ $form['count'] }} {{ Str::plural('submission', $form['count']) }}</span>
                                    @if($form['latest_at'])
                                        <span>·</span>
                                        <span>Latest: {{ \Carbon\Carbon::parse($form['latest_at'])->diffForHumans() }} by {{ $form['latest_author'] }}</span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-sm font-semibold text-gray-800 shrink-0">{{ $form['count'] }}</span>
                            <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
