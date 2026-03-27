@php
    $comment = array_merge([
        'id' => '', 'user_id' => 0, 'user_name' => 'Unknown', 'section' => null,
        'type' => 'observation', 'visibility' => 'internal', 'content' => '',
        'resolved' => false, 'resolved_by' => null, 'resolved_note' => null,
        'resolved_at' => null, 'replies' => [], 'created_at' => now()->toIso8601String(),
    ], $comment);
@endphp
<div id="comment-{{ $comment['id'] }}" class="px-4 py-3">
    <div class="flex items-start gap-3">
        {{-- Type indicator --}}
        <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 mt-0.5
            {{ $comment['resolved'] ? 'bg-green-100' : '' }}
            {{ !$comment['resolved'] && $comment['type'] === 'required_change' ? 'bg-red-100' : '' }}
            {{ !$comment['resolved'] && $comment['type'] === 'question' ? 'bg-purple-100' : '' }}
            {{ !$comment['resolved'] && $comment['type'] === 'observation' ? 'bg-blue-100' : '' }}">
            @if($comment['resolved'])
                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            @elseif($comment['type'] === 'required_change')
                <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            @elseif($comment['type'] === 'question')
                <span class="text-sm font-bold text-purple-500">?</span>
            @else
                <span class="text-[10px] font-bold text-blue-500">{{ strtoupper(substr($comment['user_name'], 0, 1)) }}</span>
            @endif
        </div>

        {{-- Content --}}
        <div class="flex-1 min-w-0">
            {{-- Header line --}}
            <div class="flex items-center gap-1.5 flex-wrap">
                <span class="text-xs font-semibold text-gray-800">{{ $comment['user_name'] }}</span>
                @if($comment['type'] === 'required_change')
                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full {{ $comment['resolved'] ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ $comment['resolved'] ? 'Required (resolved)' : 'Required Change' }}
                    </span>
                @elseif($comment['type'] === 'question')
                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-full bg-purple-50 text-purple-600">Question</span>
                @endif
                @if(($comment['visibility'] ?? 'internal') === 'internal')
                    <span class="text-[10px] text-gray-400 italic">internal</span>
                @endif
                <span class="text-[10px] text-gray-300">{{ usertime($comment['created_at'])->diffForHumans() }}</span>
            </div>

            {{-- Comment text --}}
            <p class="text-sm text-gray-700 mt-1 leading-relaxed {{ $comment['resolved'] ? 'line-through text-gray-400' : '' }}">{{ $comment['content'] }}</p>

            {{-- Resolved note --}}
            @if($comment['resolved'] && $comment['resolved_by'])
                <div class="mt-2 flex items-start gap-1.5 text-xs text-green-600 bg-green-50 rounded px-2.5 py-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <div>
                        <span class="font-medium">Resolved by {{ $comment['resolved_by'] }}</span>
                        @if($comment['resolved_note'])
                            <span class="text-green-500"> — {{ $comment['resolved_note'] }}</span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Replies --}}
            @if(!empty($comment['replies']))
                <div class="mt-3 ml-1 pl-3 border-l-2 border-gray-300 space-y-2.5">
                    @foreach($comment['replies'] as $reply)
                        <div>
                            <div class="flex items-center gap-1.5">
                                <div class="w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center">
                                    <span class="text-[8px] font-bold text-gray-500">{{ strtoupper(substr($reply['user_name'], 0, 1)) }}</span>
                                </div>
                                <span class="text-xs font-semibold text-gray-700">{{ $reply['user_name'] }}</span>
                                <span class="text-[10px] text-gray-300">{{ usertime($reply['created_at'])->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center gap-2 ml-7">
                                <p class="text-sm text-gray-600 mt-0.5 flex-1">{{ $reply['content'] }}</p>
                                @if(($userRole ?? '') === 'admin')
                                    <form method="POST" action="{{ route('comments.destroy-reply') }}" class="inline shrink-0" data-confirm="Delete this reply?">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="doc_id" value="{{ $docId }}">
                                        <input type="hidden" name="comment_id" value="{{ $comment['id'] }}">
                                        <input type="hidden" name="reply_id" value="{{ $reply['id'] }}">
                                        <button type="submit" class="text-[10px] text-gray-300 hover:text-red-500">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Actions --}}
            <div class="flex items-center gap-3 mt-2">
                @if(!$comment['resolved'] && $canComment)
                    <button @click="replyTo = replyTo === '{{ $comment['id'] }}' ? null : '{{ $comment['id'] }}'"
                            class="text-[11px] text-gray-400 hover:text-blue-600 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Reply
                    </button>
                @endif
                @if(!$comment['resolved'] && in_array($userRole, ['admin', 'editor']))
                    <button @click="resolveId = resolveId === '{{ $comment['id'] }}' ? null : '{{ $comment['id'] }}'"
                            class="text-[11px] text-gray-400 hover:text-green-600 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Resolve
                    </button>
                @endif
                @if($comment['resolved'] && in_array($userRole, ['admin', 'editor']))
                    <form method="POST" action="{{ route('comments.unresolve') }}" class="inline">
                        @csrf
                        <input type="hidden" name="doc_id" value="{{ $docId }}">
                        <input type="hidden" name="comment_id" value="{{ $comment['id'] }}">
                        <button type="submit" class="text-[11px] text-gray-400 hover:text-amber-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reopen
                        </button>
                    </form>
                @endif
                @if($userRole === 'admin')
                    <form method="POST" action="{{ route('comments.destroy') }}" class="inline" data-confirm="Delete this comment?">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="doc_id" value="{{ $docId }}">
                        <input type="hidden" name="comment_id" value="{{ $comment['id'] }}">
                        <button type="submit" class="text-[11px] text-gray-400 hover:text-red-600 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                @endif
            </div>

            {{-- Reply form --}}
            <div x-show="replyTo === '{{ $comment['id'] }}'" x-cloak class="mt-2 bg-gray-50 rounded-lg p-3">
                <form method="POST" action="{{ route('comments.reply') }}">
                    @csrf
                    <input type="hidden" name="doc_id" value="{{ $docId }}">
                    <input type="hidden" name="comment_id" value="{{ $comment['id'] }}">
                    <textarea name="content" placeholder="Write a reply..." rows="2"
                              class="w-full border-gray-200 rounded text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500 bg-white"></textarea>
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="button" @click="replyTo = null" class="px-2.5 py-1 text-xs text-gray-500 hover:bg-gray-200 rounded">Cancel</button>
                        <button type="submit" class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Reply</button>
                    </div>
                </form>
            </div>

            {{-- Resolve form --}}
            <div x-show="resolveId === '{{ $comment['id'] }}'" x-cloak class="mt-2 bg-green-50 rounded-lg p-3">
                <form method="POST" action="{{ route('comments.resolve') }}">
                    @csrf
                    <input type="hidden" name="doc_id" value="{{ $docId }}">
                    <input type="hidden" name="comment_id" value="{{ $comment['id'] }}">
                    <input type="text" name="note" placeholder="What did you do to resolve this? (optional)"
                           class="w-full border-gray-200 rounded text-sm py-2 px-3 focus:ring-green-500 focus:border-green-500 bg-white">
                    <div class="flex justify-end gap-2 mt-2">
                        <button type="button" @click="resolveId = null" class="px-2.5 py-1 text-xs text-gray-500 hover:bg-gray-200 rounded">Cancel</button>
                        <button type="submit" class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">Resolve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
