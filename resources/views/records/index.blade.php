<x-app-layout>
    @section('page-title', 'Records')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Records</h2>
                <span class="text-sm text-gray-400">{{ $totalRecords }} {{ Str::plural('submission', $totalRecords) }}</span>
            </div>
        </div>
    </x-slot>

    <script>
        function recordsPage() {
            return {
                dateFilter: '',
                selectedForms: @json($forms->pluck('form_id')->toArray()),
                allForms: @json($forms->map(fn($f) => ['id' => $f['form_id'], 'title' => $f['form_title'], 'count' => $f['count']])->toArray()),
                exportModal: false,
                exportStatus: '',
                exportTotal: 0,
                exportProcessed: 0,
                exportError: '',
                exportDownloadUrl: '',
                showOptions: false,

                get allSelected() { return this.selectedForms.length === this.allForms.length },
                toggleAll() {
                    if (this.allSelected) this.selectedForms = [];
                    else this.selectedForms = this.allForms.map(f => f.id);
                },
                toggleForm(id) {
                    const idx = this.selectedForms.indexOf(id);
                    if (idx > -1) this.selectedForms.splice(idx, 1);
                    else this.selectedForms.push(id);
                },

                startExport() {
                    this.showOptions = false;
                    this.exportModal = true;
                    this.exportStatus = 'starting';
                    this.exportTotal = 0;
                    this.exportProcessed = 0;
                    this.exportError = '';
                    this.exportDownloadUrl = '';

                    fetch('{{ route("records.export-all") }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ date_filter: this.dateFilter, form_ids: this.selectedForms }),
                    })
                    .then(r => r.json())
                    .then(data => this.pollStatus(data.id))
                    .catch(() => { this.exportStatus = 'failed'; this.exportError = 'Failed to start export'; });
                },

                pollStatus(id) {
                    const poll = () => {
                        fetch('/records/export-all-status/' + id)
                            .then(r => r.json())
                            .then(data => {
                                this.exportStatus = data.status;
                                this.exportTotal = data.total;
                                this.exportProcessed = data.processed;
                                this.exportError = data.error;
                                if (data.status === 'ready') {
                                    this.exportDownloadUrl = '/records/export-all-download/' + id;
                                } else if (data.status !== 'failed') {
                                    setTimeout(poll, 2000);
                                }
                            });
                    };
                    setTimeout(poll, 2000);
                },
            };
        }
    </script>
    <div class="py-8" x-data="recordsPage()">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Export modal --}}
            <div x-show="exportModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="exportModal = false">
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6" @click.stop>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Export Records</h3>
                        <button @click="exportModal = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <template x-if="exportStatus === 'starting' || exportStatus === 'pending' || exportStatus === 'processing'">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <svg class="w-5 h-5 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                <span class="text-sm text-gray-600">Generating record PDFs...</span>
                            </div>
                            <template x-if="exportTotal > 0">
                                <div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                        <div class="bg-blue-500 h-2 rounded-full transition-all" :style="'width: ' + (exportProcessed / exportTotal * 100) + '%'"></div>
                                    </div>
                                    <p class="text-xs text-gray-500" x-text="exportProcessed + ' of ' + exportTotal + ' records'"></p>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="exportStatus === 'ready'">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span class="text-sm text-gray-600">Export ready!</span>
                            </div>
                            <a :href="exportDownloadUrl" @click="setTimeout(() => { exportModal = false }, 1000)" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 w-full justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Download ZIP
                            </a>
                        </div>
                    </template>
                    <template x-if="exportStatus === 'failed'">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm text-red-600">Export failed</span>
                            </div>
                            <p class="text-xs text-gray-500" x-text="exportError"></p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Options panel --}}
            <div x-show="showOptions" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50" @click.self="showOptions = false">
                <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6" @click.stop>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Export Records</h3>
                        <button @click="showOptions = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Date filter --}}
                    <div class="mb-4">
                        <label class="text-xs font-medium text-gray-600 block mb-1">Date range</label>
                        <select x-model="dateFilter" class="w-full text-sm border-gray-200 rounded-md py-2">
                            <option value="">All time</option>
                            <option value="7">Last 7 days</option>
                            <option value="30">Last 30 days</option>
                            <option value="90">Last 90 days</option>
                            <option value="365">Last year</option>
                        </select>
                    </div>

                    {{-- Form checkboxes --}}
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-medium text-gray-600">Forms to include</label>
                            <button @click="toggleAll()" class="text-xs text-blue-600 hover:text-blue-800" x-text="allSelected ? 'Deselect all' : 'Select all'"></button>
                        </div>
                        <div class="space-y-1.5 max-h-60 overflow-y-auto border border-gray-100 rounded-lg p-2">
                            <template x-for="form in allForms" :key="form.id">
                                <label class="flex items-center gap-2.5 p-2 rounded-md cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" :value="form.id" :checked="selectedForms.includes(form.id)" @change="toggleForm(form.id)" class="rounded text-blue-600 border-gray-300">
                                    <span class="text-[10px] font-mono font-semibold px-1.5 py-0.5 rounded shrink-0 whitespace-nowrap bg-purple-50 text-purple-700" x-text="form.id"></span>
                                    <span class="text-sm text-gray-700 flex-1 truncate" x-text="form.title"></span>
                                    <span class="text-xs text-gray-400 shrink-0" x-text="form.count"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <button @click="startExport()" :disabled="selectedForms.length === 0" :class="selectedForms.length === 0 ? 'opacity-50 cursor-not-allowed' : ''" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 w-full justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span x-text="'Export ' + selectedForms.length + ' form' + (selectedForms.length !== 1 ? 's' : '')"></span>
                    </button>
                </div>
            </div>

            @if($forms->isEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-400">No records yet.</p>
                    <p class="text-xs text-gray-400 mt-1">Records are created when forms are submitted.</p>
                </div>
            @else
                {{-- Export all button --}}
                <div class="flex justify-end mb-4">
                    <button @click="showOptions = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 text-white text-xs rounded-md hover:bg-red-700">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Export All Records
                    </button>
                </div>

                @foreach($forms as $form)
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-mono font-semibold px-1.5 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor('FM') }}">{{ $form['form_id'] }}</span>
                                <h3 class="text-sm font-semibold text-gray-700">{{ $form['form_title'] }}</h3>
                                <span class="text-xs text-gray-400">({{ $form['count'] }})</span>
                            </div>
                            <a href="{{ route('records.form', $form['form_id']) }}" class="text-xs text-blue-600 hover:text-blue-800">View all</a>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            @foreach($form['records'] as $record)
                                <div class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                                    <a href="{{ route('records.show', $record['filename']) }}" class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('records.show', $record['filename']) }}" class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-mono font-semibold px-1 py-0.5 rounded shrink-0 whitespace-nowrap {{ \App\Services\DocumentMetadata::typeColor('REC') }}">{{ $record['id'] }}</span>
                                            <span class="text-sm font-medium text-gray-800 truncate">{{ $record['title'] }}</span>
                                        </div>
                                        <div class="text-[11px] text-gray-400 font-mono mt-0.5">records/{{ $record['filename'] }}</div>
                                        <div class="flex items-center gap-2 text-xs text-gray-400 mt-0.5">
                                            <span>{{ $record['author'] }}</span>
                                            @if($record['submitted_at'])
                                                <span>·</span>
                                                <span>{{ usertime($record['submitted_at'])->diffForHumans() }}</span>
                                            @endif
                                        </div>
                                    </a>
                                    <a href="{{ route('records.export', $record['filename']) }}" class="p-1.5 rounded bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-600 shrink-0 transition-colors" title="Download PDF">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </a>
                                    <a href="{{ route('records.show', $record['filename']) }}">
                                        <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
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
