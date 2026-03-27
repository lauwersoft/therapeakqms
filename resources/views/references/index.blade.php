<x-app-layout>
    @section('page-title', 'References')
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">References</h2>
            <span class="text-sm text-gray-400">{{ $groups->flatten(1)->count() }} {{ Str::plural('document', $groups->flatten(1)->count()) }}</span>
        </div>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <p class="text-sm text-gray-500 mb-6">Regulatory standards and guidance documents for reference. These are not controlled QMS documents.</p>

        @forelse($groups as $category => $docs)
            <div class="mb-8">
                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ $category }}</h3>
                <div class="space-y-2">
                    @foreach($docs as $doc)
                        <a href="{{ route('references.show', $doc['filename']) }}"
                           class="flex items-center gap-4 bg-white rounded-lg border border-gray-200 shadow-sm px-5 py-4 hover:bg-gray-50 transition-colors">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0
                                {{ str_starts_with($doc['filename'], 'iso-') ? 'bg-blue-50 text-blue-600' : '' }}
                                {{ str_starts_with($doc['filename'], 'eu-mdr') ? 'bg-emerald-50 text-emerald-600' : '' }}
                                {{ str_starts_with($doc['filename'], 'mdcg-') ? 'bg-purple-50 text-purple-600' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-sm font-medium text-gray-800 block">{{ $doc['title'] }}</span>
                                <span class="text-xs text-gray-400">{{ number_format($doc['size'] / 1024, 0) }} KB</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-12 text-gray-400">
                <p>No reference documents found.</p>
                <p class="text-xs mt-1">Add markdown files to <code class="bg-gray-100 px-1 rounded">qms/references/</code></p>
            </div>
        @endforelse
    </div>
</x-app-layout>
