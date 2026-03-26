<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Unresolved comments grouped by document --}}
            @if($unresolvedComments > 0)
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">{{ $unresolvedComments }} unresolved {{ Str::plural('comment', $unresolvedComments) }}</span>
                    </div>
                    @php
                        // Group recent comments by document
                        $groupedByDoc = collect($recentComments)->groupBy('_doc_id');
                    @endphp
                    <div class="space-y-3">
                        @foreach($groupedByDoc as $rcDocId => $rcGroup)
                            @php
                                $rcDocInfo = collect($docList)->firstWhere('doc_id', $rcDocId);
                                $rcPath = $rcDocInfo ? $rcDocInfo['path'] : '';
                                $rcTitle = $rcDocInfo ? $rcDocInfo['title'] : $rcDocId;
                                $rcType = $rcDocInfo ? $rcDocInfo['type'] : null;
                                $rcStatus = $rcDocInfo ? $rcDocInfo['status'] : 'draft';
                                $rcSummary = $commentSummaryDashboard[$rcDocId] ?? ['unresolved' => 0, 'total' => 0];
                            @endphp
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                {{-- Document header --}}
                                <a href="{{ $rcPath ? route('documents.index', ['path' => $rcPath]) . '#comments-section' : '#' }}"
                                   class="flex items-center gap-3 px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            @if($rcType)
                                                <span class="text-[10px] font-mono font-semibold px-1.5 py-0.5 rounded {{ \App\Services\DocumentMetadata::typeColor($rcType) }}">{{ $rcDocId }}</span>
                                            @endif
                                            <span class="text-sm font-medium text-gray-800 truncate">{{ $rcTitle }}</span>
                                        </div>
                                        @if($rcPath)
                                            <span class="text-[10px] text-gray-400 font-mono mt-0.5 block">/{{ $rcPath }}.md</span>
                                        @endif
                                    </div>
                                    <span class="flex items-center gap-1 text-[10px] text-amber-600 shrink-0">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                                        <span class="font-medium">{{ $rcSummary['unresolved'] }}</span>
                                    </span>
                                    <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                                {{-- Comments for this document --}}
                                <div class="divide-y divide-gray-50">
                                    @foreach($rcGroup as $rc)
                                        <a href="{{ $rcPath ? route('documents.index', ['path' => $rcPath]) : '#' }}#comment-{{ $rc['id'] ?? '' }}"
                                           class="flex items-start gap-2.5 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                            <div class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 mt-0.5
                                                {{ ($rc['type'] ?? '') === 'required_change' ? 'bg-red-100' : '' }}
                                                {{ ($rc['type'] ?? '') === 'question' ? 'bg-purple-100' : '' }}
                                                {{ ($rc['type'] ?? '') === 'observation' ? 'bg-gray-100' : '' }}">
                                                @if(($rc['type'] ?? '') === 'required_change')
                                                    <svg class="w-2.5 h-2.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                                                @elseif(($rc['type'] ?? '') === 'question')
                                                    <span class="text-[8px] font-bold text-purple-500">?</span>
                                                @else
                                                    <span class="text-[7px] font-bold text-gray-400">{{ strtoupper(substr($rc['user_name'] ?? '?', 0, 1)) }}</span>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="text-[11px] font-medium text-gray-500">{{ $rc['user_name'] ?? 'Unknown' }}</span>
                                                    @if($rc['section'] ?? null)
                                                        <span class="text-[10px] text-gray-400">on {{ Str::limit($rc['section'], 25) }}</span>
                                                    @endif
                                                    <span class="text-[10px] text-gray-300">{{ \Carbon\Carbon::parse($rc['created_at'] ?? now())->diffForHumans() }}</span>
                                                </div>
                                                <p class="text-xs text-gray-600 mt-0.5 line-clamp-2">{{ $rc['content'] ?? '' }}</p>
                                                @php $replyCount = count($rc['replies'] ?? []); @endphp
                                                @if($replyCount > 0)
                                                    <span class="text-[10px] text-gray-400 mt-0.5 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                        {{ $replyCount }} {{ Str::plural('reply', $replyCount) }}
                                                    </span>
                                                @endif
                                            </div>
                                            <svg class="w-3.5 h-3.5 text-gray-300 shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Unpublished changes warning --}}
            @if($pendingCount > 0 && in_array(Auth::user()->role, ['admin', 'editor']))
                <a href="{{ route('documents.changes') }}"
                   class="flex items-center justify-between w-full px-4 py-3 mb-6 text-sm bg-amber-50 text-amber-800 rounded-lg hover:bg-amber-100 border border-amber-200">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 bg-amber-500 rounded-full shrink-0 animate-pulse"></span>
                        <span><strong>{{ $pendingCount }} unpublished {{ Str::plural('change', $pendingCount) }}</strong> — review and publish to save to git</span>
                    </div>
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @endif

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

            {{-- User Activity --}}
            @if(Auth::user()->isAdmin() && $activeUsers->isNotEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                        <h3 class="font-semibold text-gray-800">User Activity</h3>
                        <a href="{{ route('activity.index') }}" class="text-xs text-blue-600 hover:text-blue-800">View details</a>
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach($activeUsers as $activeUser)
                            <a href="{{ route('activity.show', $activeUser) }}" class="block px-5 py-3 flex items-center gap-3 hover:bg-gray-50 transition-colors">
                                <div class="relative shrink-0">
                                    <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($activeUser->email))) }}?s=64&d=mp" alt="{{ $activeUser->name }}" class="w-8 h-8 rounded-full">
                                    @if($activeUser->last_active_at->gt(now()->subMinutes(5)))
                                        <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-green-500 rounded-full border-2 border-white"></span>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-sm font-medium text-gray-800">{{ $activeUser->name }}</span>
                                        <span class="text-[10px] font-medium px-1 py-0.5 rounded
                                            {{ $activeUser->role === 'admin' ? 'bg-purple-100 text-purple-600' : '' }}
                                            {{ $activeUser->role === 'editor' ? 'bg-blue-100 text-blue-600' : '' }}
                                            {{ $activeUser->role === 'auditor' ? 'bg-gray-100 text-gray-500' : '' }}">{{ ucfirst($activeUser->role) }}</span>
                                    </div>
                                    <span class="text-xs text-gray-400">
                                        @if($activeUser->last_active_at->gt(now()->subMinutes(5)))
                                            Online now
                                        @else
                                            Last active {{ $activeUser->last_active_at->diffForHumans() }}
                                        @endif
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(Auth::user()->isAdmin())
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
                    <a href="/telescope" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-1.5 bg-purple-50 rounded-lg">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800">Telescope</h3>
                                <p class="text-xs text-gray-400">Requests, exceptions, logs</p>
                            </div>
                        </div>
                    </a>
                    <a href="/horizon" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-1.5 bg-teal-50 rounded-lg">
                                <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800">Horizon</h3>
                                <p class="text-xs text-gray-400">Queue workers, jobs</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('activity.index') }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3">
                            <div class="p-1.5 bg-amber-50 rounded-lg">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-800">Activity</h3>
                                <p class="text-xs text-gray-400">User tracking details</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            {{-- Recent activity --}}
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
                                        <div class="space-y-1 mt-1.5">
                                            @foreach($commit['files'] as $file)
                                                <div class="flex items-center gap-2">
                                                    <span class="inline-flex items-center gap-1 text-[11px] px-1.5 py-0.5 rounded shrink-0
                                                        {{ $file['status'] === 'added' ? 'bg-green-50 text-green-600' : '' }}
                                                        {{ $file['status'] === 'modified' ? 'bg-blue-50 text-blue-600' : '' }}
                                                        {{ $file['status'] === 'deleted' ? 'bg-red-50 text-red-500' : '' }}">
                                                        @if($file['doc_id'])
                                                            <span class="font-mono font-medium">{{ $file['doc_id'] }}</span>
                                                        @endif
                                                        {{ $file['status'] === 'added' ? 'created' : ($file['status'] === 'deleted' ? 'removed' : 'updated') }}
                                                    </span>
                                                    <span class="text-[10px] text-gray-400 font-mono truncate">/{{ $file['path'] }}</span>
                                                </div>
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
    </div>
</x-app-layout>
