@php
    $docId = $meta['id'] ?? null;
    $comments = $docComments ?? [];
    $openComments = collect($comments)->where('resolved', false);
    $resolvedComments = collect($comments)->where('resolved', true);
    $unresolvedRequired = collect($comments)->where('type', 'required_change')->where('resolved', false)->count();
    $userRole = auth()->user()->role;
    $canComment = in_array($userRole, [\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_EDITOR, \App\Models\User::ROLE_AUDITOR]);

    // Group comments by section
    $generalComments = collect($comments)->whereNull('section')->values();
    $sectionComments = collect($comments)->whereNotNull('section')->groupBy('section');
@endphp

@if($docId)
<div x-data="{ showResolved: false, newCommentOpen: false, newCommentSection: '', replyTo: null, resolveId: null }" class="mt-6 space-y-3">

    {{-- Summary bar --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            <span class="text-sm text-gray-600">
                @if($openComments->count() > 0)
                    <span class="font-medium">{{ $openComments->count() }} open</span>
                    @if($unresolvedRequired > 0)
                        <span class="text-red-500">({{ $unresolvedRequired }} required {{ Str::plural('change', $unresolvedRequired) }})</span>
                    @endif
                @else
                    <span class="text-gray-400">No open comments</span>
                @endif
                @if($resolvedComments->count() > 0)
                    <span class="text-gray-400 ml-1">· {{ $resolvedComments->count() }} resolved</span>
                @endif
            </span>
        </div>
        <div class="flex items-center gap-2">
            <button @click="showResolved = !showResolved" class="text-xs text-gray-400 hover:text-gray-600" x-text="showResolved ? 'Hide resolved' : 'Show resolved'"></button>
            @if($canComment)
                <button @click="newCommentOpen = !newCommentOpen; newCommentSection = ''" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Comment
                </button>
            @endif
        </div>
    </div>

    {{-- New comment form --}}
    @if($canComment)
        <div x-show="newCommentOpen" x-cloak class="bg-white rounded-lg shadow-sm border border-blue-200 overflow-hidden">
            <div class="px-4 py-2 bg-blue-50 border-b border-blue-100 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-xs font-medium text-blue-700">New comment</span>
                <span x-show="newCommentSection" x-cloak class="text-xs text-blue-500">on <span x-text="newCommentSection" class="font-medium"></span></span>
            </div>
            <form method="POST" action="{{ route('comments.store') }}" class="p-4">
                @csrf
                <input type="hidden" name="doc_id" value="{{ $docId }}">
                <input type="hidden" name="section" x-model="newCommentSection">
                <div class="flex gap-2 mb-3">
                    <div class="flex-1">
                        <select name="section" x-model="newCommentSection" class="w-full border-gray-200 rounded text-xs py-1.5 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">General (whole document)</option>
                            @php
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
                    <select name="type" class="border-gray-200 rounded text-xs py-1.5 focus:ring-blue-500 focus:border-blue-500">
                        <option value="observation">Observation</option>
                        <option value="required_change">Required Change</option>
                        <option value="question">Question</option>
                    </select>
                    @if($userRole !== 'auditor')
                        <select name="visibility" class="border-gray-200 rounded text-xs py-1.5 focus:ring-blue-500 focus:border-blue-500">
                            <option value="internal">Internal</option>
                            <option value="all">All</option>
                        </select>
                    @else
                        <input type="hidden" name="visibility" value="all">
                    @endif
                </div>
                <textarea name="content" placeholder="Write your comment..." rows="2"
                          class="w-full border-gray-200 rounded text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" @click="newCommentOpen = false" class="px-3 py-1.5 text-xs text-gray-500 hover:bg-gray-100 rounded">Cancel</button>
                    <button type="submit" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Add Comment</button>
                </div>
            </form>
        </div>
    @endif

    {{-- Section-grouped comments --}}
    @foreach($sectionComments as $section => $sectionGroup)
        @php
            $sectionOpen = collect($sectionGroup)->where('resolved', false);
            $sectionResolved = collect($sectionGroup)->where('resolved', true);
        @endphp
            <div x-show="{{ $sectionOpen->count() > 0 ? 'true' : 'showResolved' }}" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                {{-- Section header --}}
                <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    <span class="text-xs font-medium text-gray-600">{{ $section }}</span>
                    @if($sectionOpen->count() > 0)
                        <span class="text-[10px] px-1.5 py-0.5 rounded-full bg-blue-100 text-blue-600 font-medium">{{ $sectionOpen->count() }}</span>
                    @endif
                </div>
                {{-- Comments in this section --}}
                <div class="divide-y divide-gray-50">
                    @foreach($sectionGroup as $comment)
                        <div x-show="{{ !$comment['resolved'] ? 'true' : 'showResolved' }}" class="{{ $comment['resolved'] ? 'opacity-60' : '' }}">
                            @include('documents.partials.comment-item', ['comment' => $comment, 'docId' => $docId, 'userRole' => $userRole, 'canComment' => $canComment])
                        </div>
                    @endforeach
                </div>
            </div>
    @endforeach

    {{-- General comments (no section) --}}
    @if($generalComments->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-xs font-medium text-gray-600">General</span>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($generalComments as $comment)
                    <div x-show="{{ !$comment['resolved'] ? 'true' : 'showResolved' }}" class="{{ $comment['resolved'] ? 'opacity-60' : '' }}">
                        @include('documents.partials.comment-item', ['comment' => $comment, 'docId' => $docId, 'userRole' => $userRole, 'canComment' => $canComment])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@endif
