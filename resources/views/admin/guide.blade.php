<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">QMS Guide</h2>
            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-purple-100 text-purple-600">Internal</span>
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

            <div class="mb-2 text-xs text-gray-400 flex items-center gap-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span class="font-mono">qms/ADMIN_GUIDE.md</span>
            </div>

            {{-- Guide Content from qms/ADMIN_GUIDE.md --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sm:p-10">
                <div class="admin-guide prose prose-sm sm:prose-base max-w-none
                            text-gray-700 prose-headings:text-gray-800
                            prose-h1:text-xl sm:prose-h1:text-2xl prose-h1:border-b prose-h1:border-gray-200 prose-h1:pb-3 prose-h1:mb-6
                            prose-h2:text-lg sm:prose-h2:text-xl prose-h2:mt-10 prose-h2:mb-4 prose-h2:pb-2 prose-h2:border-b prose-h2:border-gray-100
                            prose-h3:text-base prose-h3:mt-6 prose-h3:mb-2
                            prose-strong:text-gray-800
                            prose-table:text-sm prose-th:bg-gray-50 prose-th:px-3 prose-th:py-2 prose-td:px-3 prose-td:py-2
                            prose-a:text-blue-600
                            prose-li:my-1
                            prose-hr:my-8">
                    {!! $guideHtml !!}
                </div>
            </div>

            @push('styles')
            <style>
                .admin-guide ul.contains-task-list {
                    list-style: none;
                    padding-left: 0;
                }
                .admin-guide ul.contains-task-list li {
                    display: flex;
                    align-items: flex-start;
                    gap: 0.75rem;
                    padding: 0.75rem 1rem;
                    margin: 0.5rem 0;
                    background: #fefce8;
                    border: 1px solid #fef08a;
                    border-radius: 0.5rem;
                }
                .admin-guide ul.contains-task-list li input[type="checkbox"] {
                    margin-top: 0.25rem;
                    accent-color: #2563eb;
                }
                .admin-guide h3 + p {
                    margin-top: 0.25rem;
                }
                .admin-guide h3 + p > strong:first-child {
                    display: inline-block;
                    font-size: 0.7rem;
                    text-transform: uppercase;
                    letter-spacing: 0.05em;
                    color: #6b7280;
                    margin-right: 0.25rem;
                }
            </style>
            @endpush

        </div>
    </div>
</x-app-layout>
