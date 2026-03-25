<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">QMS Guide</h2>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-purple-100 text-purple-600">Admin only</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- QMS Health Overview --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $totalDocs }}</div>
                    <div class="text-xs text-gray-500 mt-1">Documents</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $approvedCount }}</div>
                    <div class="text-xs text-gray-500 mt-1">Approved</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                    <div class="text-2xl font-bold {{ $unresolvedComments > 0 ? 'text-amber-600' : 'text-gray-800' }}">{{ $unresolvedComments }}</div>
                    <div class="text-xs text-gray-500 mt-1">Open Comments</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ $recordCount }}</div>
                    <div class="text-xs text-gray-500 mt-1">Records</div>
                </div>
            </div>

            {{-- Guide Content from qms/ADMIN_GUIDE.md --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sm:p-10">
                <div class="prose prose-sm sm:prose-base max-w-none
                            text-gray-700 prose-headings:text-gray-800
                            prose-h1:text-xl sm:prose-h1:text-2xl prose-h1:border-b prose-h1:border-gray-200 prose-h1:pb-3 prose-h1:mb-6
                            prose-h2:text-lg sm:prose-h2:text-xl prose-h2:mt-8
                            prose-h3:text-base prose-h3:mt-6
                            prose-strong:text-gray-800
                            prose-table:text-sm prose-th:bg-gray-50 prose-th:px-3 prose-th:py-2 prose-td:px-3 prose-td:py-2
                            prose-a:text-blue-600
                            prose-li:my-0.5">
                    {!! $guideHtml !!}
                </div>
            </div>

            <div class="mt-4 text-xs text-gray-400 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="font-mono">qms/ADMIN_GUIDE.md</span>
            </div>
        </div>
    </div>
</x-app-layout>
