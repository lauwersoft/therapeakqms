@php
    $docId = $meta['id'] ?? null;
    $comments = $docComments ?? [];
    $openComments = collect($comments)->where('resolved', false);
    $resolvedComments = collect($comments)->where('resolved', true);
    $unresolvedRequired = collect($comments)->where('type', 'required_change')->where('resolved', false)->count();
    $userRole = auth()->user()->role;
    $canComment = in_array($userRole, [\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_EDITOR, \App\Models\User::ROLE_AUDITOR]);
@endphp

@if($docId)
<div x-data="{
    showComments: {{ count($comments) > 0 ? 'true' : 'false' }},
    filter: 'open',
    replyTo: null,
    resolveId: null,
    resolveNote: '',
    newComment: { section: '', type: 'observation', visibility: '{{ $userRole === 'auditor' ? 'all' : 'internal' }}', content: '' },
    showNewComment: false,
    sections: @json(collect($comments)->pluck('section')->filter()->unique()->values()),
}" class="bg-white rounded-lg shadow-sm border border-gray-200 mt-6">

    {{-- Header --}}
    <button @click="showComments = !showComments" class="w-full px-5 py-3.5 flex items-center justify-between hover:bg-gray-50 transition-colors rounded-lg">
        <div class="flex items-center gap-3">
            <h3 class="text-sm font-semibold text-gray-700">Comments</h3>
            @if($openComments->count() > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-blue-100 text-blue-700">
                    {{ $openComments->count() }} open
                </span>
            @endif
            @if($unresolvedRequired > 0)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-red-100 text-red-600">
                    {{ $unresolvedRequired }} required {{ Str::plural('change', $unresolvedRequired) }}
                </span>
            @endif
            @if($resolvedComments->count() > 0)
                <span class="text-xs text-gray-400">{{ $resolvedComments->count() }} resolved</span>
            @endif
        </div>
        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="showComments ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    <div x-show="showComments" x-cloak>
        {{-- Toolbar --}}
        <div class="px-5 py-2 border-t border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <button @click="filter = 'open'" class="px-2.5 py-1 text-xs rounded-full transition-colors"
                        :class="filter === 'open' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                    Open ({{ $openComments->count() }})
                </button>
                <button @click="filter = 'resolved'" class="px-2.5 py-1 text-xs rounded-full transition-colors"
                        :class="filter === 'resolved' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                    Resolved ({{ $resolvedComments->count() }})
                </button>
                <button @click="filter = 'all'" class="px-2.5 py-1 text-xs rounded-full transition-colors"
                        :class="filter === 'all' ? 'bg-gray-800 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                    All ({{ count($comments) }})
                </button>
            </div>
            @if($canComment)
                <button @click="showNewComment = !showNewComment" class="inline-flex items-center gap-1 px-2.5 py-1 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Comment
                </button>
            @endif
        </div>

        {{-- New comment form --}}
        @if($canComment)
            <div x-show="showNewComment" x-cloak class="px-5 py-3 border-t border-gray-100 bg-blue-50/30">
                <form method="POST" action="{{ route('comments.store') }}">
                    @csrf
                    <input type="hidden" name="doc_id" value="{{ $docId }}">
                    <div class="grid grid-cols-3 gap-2 mb-2">
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Section</label>
                            <select name="section" x-model="newComment.section" class="w-full border-gray-200 rounded text-xs py-1 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">General (whole document)</option>
                                @php
                                    // Extract sections from the document content
                                    $docSections = [];
                                    if (isset($content)) {
                                        preg_match_all('/<h[23][^>]*>(.*?)<\/h[23]>/s', $content, $sectionMatches);
                                        foreach ($sectionMatches[1] as $s) {
                                            $docSections[] = strip_tags($s);
                                        }
                                    }
                                @endphp
                                @foreach($docSections as $section)
                                    <option value="{{ $section }}">{{ Str::limit($section, 60) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Type</label>
                            <select name="type" x-model="newComment.type" class="w-full border-gray-200 rounded text-xs py-1 focus:ring-blue-500 focus:border-blue-500">
                                <option value="observation">Observation</option>
                                <option value="required_change">Required Change</option>
                                <option value="question">Question</option>
                            </select>
                        </div>
                        @if($userRole !== 'auditor')
                            <div>
                                <label class="block text-[10px] font-medium text-gray-500 mb-0.5">Visibility</label>
                                <select name="visibility" x-model="newComment.visibility" class="w-full border-gray-200 rounded text-xs py-1 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="internal">Internal only</option>
                                    <option value="all">Visible to auditors</option>
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="visibility" value="all">
                        @endif
                    </div>
                    <textarea name="content" x-model="newComment.content" placeholder="Write your comment..."
                              class="w-full border-gray-200 rounded text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 min-h-[60px]" rows="2"></textarea>
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-2 text-[10px] text-gray-400">
                            <span x-show="newComment.type === 'required_change'" x-cloak class="text-red-500 font-medium">Blocks approval until resolved</span>
                            <span x-show="newComment.visibility === 'internal'" x-cloak>Only admins & editors can see this</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="showNewComment = false; newComment.content = ''" class="px-2.5 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded">Cancel</button>
                            <button type="submit" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Add Comment</button>
                        </div>
                    </div>
                </form>
            </div>
        @endif

        {{-- Comments list --}}
        <div class="divide-y divide-gray-50">
            @forelse($comments as $comment)
                <div x-show="filter === 'all' || (filter === 'open' && !{{ $comment['resolved'] ? 'false' : 'true' }}) === false || (filter === 'open' && {{ $comment['resolved'] ? 'false' : 'true' }}) || (filter === 'resolved' && {{ $comment['resolved'] ? 'true' : 'false' }})"
                     class="px-5 py-3 {{ $comment['resolved'] ? 'bg-gray-50/50' : '' }}">
                    <div class="flex items-start gap-3">
                        {{-- Avatar --}}
                        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5
                            {{ $comment['type'] === 'required_change' && !$comment['resolved'] ? 'bg-red-100 text-red-600' : '' }}
                            {{ $comment['type'] === 'question' && !$comment['resolved'] ? 'bg-purple-100 text-purple-600' : '' }}
                            {{ $comment['type'] === 'observation' && !$comment['resolved'] ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $comment['resolved'] ? 'bg-green-100 text-green-600' : '' }}">
                            @if($comment['resolved'])
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @else
                                <span class="text-[10px] font-bold">{{ strtoupper(substr($comment['user_name'], 0, 1)) }}</span>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-semibold text-gray-800">{{ $comment['user_name'] }}</span>
                                {{-- Type badge --}}
                                @if($comment['type'] === 'required_change')
                                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-red-100 text-red-600">Required Change</span>
                                @elseif($comment['type'] === 'question')
                                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-purple-100 text-purple-600">Question</span>
                                @else
                                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-gray-100 text-gray-500">Observation</span>
                                @endif
                                {{-- Visibility --}}
                                @if(($comment['visibility'] ?? 'internal') === 'internal')
                                    <span class="text-[10px] text-gray-400">internal</span>
                                @endif
                                {{-- Section --}}
                                @if($comment['section'] ?? null)
                                    <span class="text-[10px] text-gray-400">on <span class="font-medium text-gray-500">{{ Str::limit($comment['section'], 40) }}</span></span>
                                @endif
                                <span class="text-[10px] text-gray-300">{{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}</span>
                            </div>

                            <p class="text-sm text-gray-700 mt-1 {{ $comment['resolved'] ? 'line-through text-gray-400' : '' }}">{{ $comment['content'] }}</p>

                            {{-- Resolved info --}}
                            @if($comment['resolved'])
                                <div class="mt-1.5 text-xs text-green-600 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Resolved by {{ $comment['resolved_by'] }}
                                    @if($comment['resolved_note'])
                                        — {{ $comment['resolved_note'] }}
                                    @endif
                                </div>
                            @endif

                            {{-- Replies --}}
                            @if(!empty($comment['replies']))
                                <div class="mt-2 ml-2 pl-3 border-l-2 border-gray-100 space-y-2">
                                    @foreach($comment['replies'] as $reply)
                                        <div>
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-xs font-semibold text-gray-700">{{ $reply['user_name'] }}</span>
                                                <span class="text-[10px] text-gray-300">{{ \Carbon\Carbon::parse($reply['created_at'])->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-sm text-gray-600 mt-0.5">{{ $reply['content'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Actions --}}
                            <div class="flex items-center gap-2 mt-2">
                                @if(!$comment['resolved'])
                                    {{-- Reply --}}
                                    <button @click="replyTo = replyTo === '{{ $comment['id'] }}' ? null : '{{ $comment['id'] }}'"
                                            class="text-[11px] text-gray-400 hover:text-blue-600">Reply</button>
                                    {{-- Resolve --}}
                                    @if(in_array($userRole, ['admin', 'editor']))
                                        <button @click="resolveId = resolveId === '{{ $comment['id'] }}' ? null : '{{ $comment['id'] }}'; resolveNote = ''"
                                                class="text-[11px] text-gray-400 hover:text-green-600">Resolve</button>
                                    @endif
                                @else
                                    {{-- Reopen --}}
                                    @if(in_array($userRole, ['admin', 'editor']))
                                        <form method="POST" action="{{ route('comments.unresolve') }}" class="inline">
                                            @csrf
                                            <input type="hidden" name="doc_id" value="{{ $docId }}">
                                            <input type="hidden" name="comment_id" value="{{ $comment['id'] }}">
                                            <button type="submit" class="text-[11px] text-gray-400 hover:text-amber-600">Reopen</button>
                                        </form>
                                    @endif
                                @endif
                                {{-- Delete (admin only) --}}
                                @if($userRole === 'admin')
                                    <form method="POST" action="{{ route('comments.destroy') }}" class="inline" onsubmit="return confirm('Delete this comment?')">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="doc_id" value="{{ $docId }}">
                                        <input type="hidden" name="comment_id" value="{{ $comment['id'] }}">
                                        <button type="submit" class="text-[11px] text-gray-400 hover:text-red-600">Delete</button>
                                    </form>
                                @endif
                            </div>

                            {{-- Reply form --}}
                            <div x-show="replyTo === '{{ $comment['id'] }}'" x-cloak class="mt-2">
                                <form method="POST" action="{{ route('comments.reply') }}">
                                    @csrf
                                    <input type="hidden" name="doc_id" value="{{ $docId }}">
                                    <input type="hidden" name="comment_id" value="{{ $comment['id'] }}">
                                    <textarea name="content" placeholder="Write a reply..." rows="2"
                                              class="w-full border-gray-200 rounded text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                    <div class="flex justify-end gap-2 mt-1">
                                        <button type="button" @click="replyTo = null" class="px-2 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded">Cancel</button>
                                        <button type="submit" class="px-2.5 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Reply</button>
                                    </div>
                                </form>
                            </div>

                            {{-- Resolve form --}}
                            <div x-show="resolveId === '{{ $comment['id'] }}'" x-cloak class="mt-2">
                                <form method="POST" action="{{ route('comments.resolve') }}">
                                    @csrf
                                    <input type="hidden" name="doc_id" value="{{ $docId }}">
                                    <input type="hidden" name="comment_id" value="{{ $comment['id'] }}">
                                    <input type="text" name="note" x-model="resolveNote" placeholder="Resolution note (optional)..."
                                           class="w-full border-gray-200 rounded text-sm py-1.5 px-3 focus:ring-blue-500 focus:border-blue-500">
                                    <div class="flex justify-end gap-2 mt-1">
                                        <button type="button" @click="resolveId = null" class="px-2 py-1 text-xs text-gray-500 hover:bg-gray-100 rounded">Cancel</button>
                                        <button type="submit" class="px-2.5 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">Resolve</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-6 text-center text-sm text-gray-400">
                    No comments yet.
                    @if($canComment)
                        <button @click="showNewComment = true" class="text-blue-500 hover:text-blue-700">Add one.</button>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>
@endif
