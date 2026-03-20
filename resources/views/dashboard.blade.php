<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Stats --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-2xl font-bold text-gray-800">{{ $totalDocs }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">Total documents</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-2xl font-bold text-gray-500">{{ $draftCount }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">Draft</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ $inReviewCount }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">In review</div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="text-2xl font-bold text-green-600">{{ $approvedCount }}</div>
                    <div class="text-xs text-gray-500 mt-0.5">Approved</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                {{-- Recent activity --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                            <h3 class="font-semibold text-gray-800">Recent Activity</h3>
                            <a href="{{ route('documents.history') }}" class="text-xs text-blue-600 hover:text-blue-800">View all</a>
                        </div>

                        @if(empty($recentCommits))
                            <div class="px-5 py-8 text-center text-sm text-gray-400">No activity yet</div>
                        @else
                            <div class="divide-y divide-gray-50">
                                @foreach($recentCommits as $commit)
                                    <div class="px-5 py-3">
                                        <div class="flex items-start gap-3">
                                            <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                                                <span class="text-[10px] font-semibold text-blue-600">{{ strtoupper(substr($commit['author'], 0, 1)) }}</span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm text-gray-700">
                                                    <span class="font-medium text-gray-800">{{ $commit['author'] }}</span>
                                                    <span class="text-gray-400 mx-1">·</span>
                                                    <span class="text-gray-400">{{ $commit['date']->diffForHumans() }}</span>
                                                </p>
                                                <p class="text-sm text-gray-600 mt-0.5">{{ Str::before($commit['message'], "\n") }}</p>
                                                <div class="flex flex-wrap gap-1.5 mt-1.5">
                                                    @foreach($commit['files'] as $file)
                                                        <span class="inline-flex items-center gap-1 text-[11px] px-1.5 py-0.5 rounded
                                                            {{ $file['status'] === 'added' ? 'bg-green-50 text-green-600' : '' }}
                                                            {{ $file['status'] === 'modified' ? 'bg-blue-50 text-blue-600' : '' }}
                                                            {{ $file['status'] === 'deleted' ? 'bg-red-50 text-red-500' : '' }}">
                                                            @if($file['doc_id'])
                                                                <span class="font-mono font-medium">{{ $file['doc_id'] }}</span>
                                                            @endif
                                                            <span>{{ $file['status'] === 'added' ? 'created' : ($file['status'] === 'deleted' ? 'removed' : 'updated') }}</span>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Quick links --}}
                <div class="space-y-4">
                    <a href="{{ route('documents.index') }}" class="block bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-blue-50 rounded-lg">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800">Documents</h3>
                        </div>
                        <p class="text-xs text-gray-500">Browse and edit QMS documents</p>
                    </a>

                    <a href="{{ route('documents.history') }}" class="block bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-purple-50 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800">Change History</h3>
                        </div>
                        <p class="text-xs text-gray-500">Full audit trail of all document revisions</p>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="block bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-gray-50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800">Profile</h3>
                        </div>
                        <p class="text-xs text-gray-500">Account settings and password</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
