@php
    $docId = $meta['id'] ?? null;
    $comments = $docComments ?? [];
    $openComments = collect($comments)->where('resolved', false);
    $resolvedComments = collect($comments)->where('resolved', true);
    $unresolvedRequired = collect($comments)->where('type', 'required_change')->where('resolved', false)->count();
    $userRole = auth()->user()->role;
    $canComment = in_array($userRole, [\App\Models\User::ROLE_ADMIN, \App\Models\User::ROLE_EDITOR, \App\Models\User::ROLE_AUDITOR]);
    $generalComments = collect($comments)->filter(fn($c) => empty($c['section']))->values();
    $docSections = [];
    if (isset($content)) {
        preg_match_all('/<h[23][^>]*>(.*?)<\/h[23]>/s', $content, $sectionMatches);
        foreach ($sectionMatches[1] as $s) {
            $docSections[] = strip_tags($s);
        }
    }
    // Group comments by section, sorted by document order
    $sectionComments = collect($comments)->filter(fn($c) => !empty($c['section']))->groupBy('section')
        ->sortBy(function($group, $section) use ($docSections) {
            $pos = array_search($section, $docSections);
            return $pos !== false ? $pos : 9999;
        });
@endphp

@if($docId)
<div id="comments-container" data-doc-id="{{ $docId }}" data-partial-url="{{ route('comments.partial', $docId) }}">

{{-- Comment indicators injected into document via JS --}}
<div id="comment-data" class="hidden"
     data-comments="{{ json_encode($sectionComments->map(fn($group) => collect($group)->where('resolved', false)->count())->toArray()) }}"
     data-doc-id="{{ $docId }}">
</div>

{{-- Floating new comment button --}}
@if($canComment)
<div id="comment-fab" class="fixed bottom-6 right-6 z-30 lg:bottom-8 lg:right-8">
    <button onclick="document.getElementById('new-comment-dialog').classList.toggle('hidden')"
            class="w-12 h-12 bg-blue-600 text-white rounded-full shadow-lg hover:bg-blue-700 flex items-center justify-center transition-colors"
            title="Add comment">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
    </button>
</div>
@endif

{{-- New comment dialog --}}
@if($canComment)
<div id="new-comment-dialog" class="hidden fixed z-30
    inset-x-0 bottom-0 rounded-t-xl
    lg:inset-x-auto lg:bottom-24 lg:right-8 lg:w-96 lg:rounded-lg
    bg-white shadow-xl border border-gray-200 overflow-hidden">
    <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
        <span class="text-xs font-semibold text-gray-700">New Comment</span>
        <button onclick="this.closest('#new-comment-dialog').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    <form method="POST" action="{{ route('comments.store') }}" class="p-4">
        @csrf
        <input type="hidden" name="doc_id" value="{{ $docId }}">
        <div class="space-y-2 mb-3">
            <select name="section" onchange="if(this.value){var s=slugify(this.value);glowElement(s);scrollToElement(s);}" class="w-full border-gray-200 rounded text-xs py-1.5 focus:ring-blue-500 focus:border-blue-500">
                <option value="">General (whole document)</option>
                @foreach($docSections as $section)
                    <option value="{{ $section }}">{{ Str::limit($section, 50) }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <select name="type" class="flex-1 border-gray-200 rounded text-xs py-1.5 focus:ring-blue-500 focus:border-blue-500">
                    <option value="observation">Observation</option>
                    <option value="required_change">Required Change</option>
                    <option value="question">Question</option>
                </select>
                @if($userRole !== 'auditor')
                    <select name="visibility" class="flex-1 border-gray-200 rounded text-xs py-1.5 focus:ring-blue-500 focus:border-blue-500">
                        <option value="internal">Internal</option>
                        <option value="all">Visible to all</option>
                    </select>
                @else
                    <input type="hidden" name="visibility" value="all">
                @endif
            </div>
        </div>
        <textarea name="content" placeholder="Write your comment..." rows="3"
                  class="w-full border-gray-200 rounded text-sm py-2 px-3 focus:ring-blue-500 focus:border-blue-500"></textarea>
        <div class="flex justify-end mt-2">
            <button type="submit" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">Add Comment</button>
        </div>
    </form>
</div>
@endif

{{-- Comments panel below document --}}
<div x-data="{ showResolved: false, replyTo: null, resolveId: null, activeSection: null }" class="mt-6 space-y-3">

    {{-- Summary --}}
    @if(count($comments) > 0)
    <div class="flex items-center justify-between px-1">
        <div class="flex items-center gap-2">
            <span class="text-xs font-medium text-gray-500">
                {{ $openComments->count() }} open {{ Str::plural('comment', $openComments->count()) }}
                @if($unresolvedRequired > 0)
                    <span class="text-red-500">({{ $unresolvedRequired }} blocks approval)</span>
                @endif
            </span>
        </div>
        @if($resolvedComments->count() > 0)
            <button @click="showResolved = !showResolved" class="text-xs text-gray-400 hover:text-gray-600" x-text="showResolved ? 'Hide resolved ({{ $resolvedComments->count() }})' : 'Show resolved ({{ $resolvedComments->count() }})'"></button>
        @endif
    </div>
    @endif

    {{-- Section comments --}}
    @foreach($sectionComments as $section => $sectionGroup)
        @php
            $sectionOpen = collect($sectionGroup)->where('resolved', false);
            $sectionResolved = collect($sectionGroup)->where('resolved', true);
        @endphp
        <div x-show="{{ $sectionOpen->count() > 0 ? 'true' : 'showResolved' }}"
             data-section="{{ $section }}"
             class="rounded-lg border border-gray-200 bg-white overflow-hidden">
            {{-- Section label --}}
            <div class="px-4 py-2 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                </svg>
                <a href="#{{ \Illuminate\Support\Str::slug(strip_tags($section)) }}" onclick="glowElement('{{ \Illuminate\Support\Str::slug(strip_tags($section)) }}')" class="text-xs font-medium text-blue-600 hover:text-blue-800 hover:underline">{{ $section }}</a>
                @if($sectionOpen->count() > 0)
                    <span class="text-[10px] px-1.5 py-0.5 rounded-full {{ $sectionOpen->where('type', 'required_change')->count() > 0 ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600' }} font-medium">{{ $sectionOpen->count() }}</span>
                @endif
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($sectionGroup as $comment)
                    <div x-show="{{ !$comment['resolved'] ? 'true' : 'showResolved' }}" class="{{ $comment['resolved'] ? 'opacity-50' : '' }}">
                        @include('documents.partials.comment-item', ['comment' => $comment, 'docId' => $docId, 'userRole' => $userRole, 'canComment' => $canComment])
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    {{-- General comments --}}
    @if($generalComments->count() > 0)
        @php
            $generalOpen = $generalComments->where('resolved', false);
        @endphp
        <div x-show="{{ $generalOpen->count() > 0 ? 'true' : 'showResolved' }}" class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="text-xs font-medium text-gray-600">General</span>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($generalComments as $comment)
                    <div x-show="{{ !$comment['resolved'] ? 'true' : 'showResolved' }}" class="{{ $comment['resolved'] ? 'opacity-50' : '' }}">
                        @include('documents.partials.comment-item', ['comment' => $comment, 'docId' => $docId, 'userRole' => $userRole, 'canComment' => $canComment])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

</div>{{-- close #comments-container --}}

@push('styles')
<style>
    /* Comment indicator badges injected next to headings */
    .comment-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        margin-left: 8px;
        padding: 2px 8px;
        font-size: 11px;
        font-weight: 600;
        border-radius: 9999px;
        cursor: pointer;
        vertical-align: middle;
        text-decoration: none;
        font-family: system-ui, sans-serif;
        background: #fef3c7;
        color: #d97706;
        transition: all 0.15s;
    }
    .comment-indicator:hover {
        background: #fde68a;
    }
    .comment-indicator svg {
        width: 12px;
        height: 12px;
    }

    /* Simple heading highlight — no layout shift */
    .heading-flash {
        background-color: rgba(59, 130, 246, 0.12);
        border-radius: 4px;
    }
    .heading-flash-fade {
        transition: background-color 0.5s ease-out;
        background-color: transparent;
    }
    .card-flash {
        box-shadow: 0 0 0 2px #3b82f6;
        border-radius: 8px;
    }
    .card-flash-fade {
        transition: box-shadow 0.5s ease-out;
        box-shadow: 0 0 0 2px transparent;
    }
</style>
@endpush

@push('scripts')
<script>
    // Flash when visible — waits for element to enter viewport
    function flashWhenVisible(el, cssClass, fadeCssClass) {
        if (!el) return;
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    observer.disconnect();
                    el.classList.remove(cssClass, fadeCssClass);
                    el.classList.add(cssClass);
                    setTimeout(function() { el.classList.add(fadeCssClass); }, 600);
                    setTimeout(function() { el.classList.remove(cssClass, fadeCssClass); }, 1100);
                }
            });
        }, { threshold: 0.3 });
        observer.observe(el);
        // Safety: disconnect after 5s in case it never becomes visible
        setTimeout(function() { observer.disconnect(); }, 5000);
    }

    function flashElement(id) {
        var el = document.getElementById(id);
        if (!el) return;
        flashWhenVisible(el, 'heading-flash', 'heading-flash-fade');
    }

    function flashCard(el) {
        if (!el) return;
        flashWhenVisible(el, 'card-flash', 'card-flash-fade');
    }

    // Scroll then flash when arrived
    function glowElement(id) {
        var el = document.getElementById(id);
        if (!el) return;
        flashElement(id);
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    function scrollToElement(id) {
        glowElement(id);
    }

    // Slugify helper (matches Laravel's Str::slug)
    function slugify(text) {
        return text.toString().toLowerCase()
            .replace(/[^\w\s-]/g, '').replace(/[\s_]+/g, '-')
            .replace(/^-+|-+$/g, '').replace(/--+/g, '-');
    }

    // On page load, scroll to and highlight a specific comment if hash present
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.hash && window.location.hash.startsWith('#comment-')) {
            var el = document.getElementById(window.location.hash.substring(1));
            if (el) {
                el.scrollIntoView({ block: 'center' });
                // Flash the parent card
                var card = el.closest('[data-section], .rounded-lg');
                if (card) flashCard(card);
            }
        }
    });

    // Inject comment indicators into document headings
    document.addEventListener('DOMContentLoaded', function() {
        var dataEl = document.getElementById('comment-data');
        if (!dataEl) return;
        var sectionCounts = JSON.parse(dataEl.dataset.comments || '{}');

        var headings = document.querySelectorAll('.prose h1, .prose h2, .prose h3');
        headings.forEach(function(heading) {
            var text = heading.textContent.trim();
            var count = sectionCounts[text] || 0;
            if (count > 0) {
                var badge = document.createElement('a');
                badge.href = '#comments-section';
                badge.className = 'comment-indicator';
                badge.onclick = function(e) {
                    e.preventDefault();
                    var cards = document.querySelectorAll('[data-section]');
                    cards.forEach(function(card) {
                        if (card.dataset.section === text) {
                            flashCard(card);
                            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                };
                badge.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>' + count;
                heading.appendChild(badge);
            }
        });

        // Add anchor for scrolling to comments
        var commentsSection = document.querySelector('[class*="mt-6 space-y-3"]');
        if (commentsSection) {
            commentsSection.id = 'comments-section';
        }
    });

    // ===== AJAX COMMENT SYSTEM =====
    function initCommentAjax() {
        var container = document.getElementById('comments-container');
        if (!container) return;

        var docId = container.dataset.docId;
        var partialUrl = container.dataset.partialUrl;
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        container.addEventListener('submit', function(e) {
            var form = e.target.closest('form');
            if (!form) return;

            // Only intercept comment-related forms
            var action = form.getAttribute('action') || '';
            if (!action.includes('/qms/comments')) return;

            // Handle confirmation dialogs
            if (form.dataset.confirm && !confirm(form.dataset.confirm)) {
                return;
            }

            e.preventDefault();

            var formData = new FormData(form);
            var method = form.querySelector('input[name="_method"]')?.value || 'POST';

            var fetchOptions = {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            };

            // Disable submit button
            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
            }

            var targetCommentId = null;

            fetch(action, fetchOptions)
                .then(function(response) {
                    if (!response.ok) throw new Error('Request failed');
                    return response.json();
                })
                .then(function(data) {
                    targetCommentId = data.comment_id;
                    if (data.message && window.showToast) showToast(data.message);
                    // Reload the comments partial
                    return fetch(partialUrl, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                })
                .then(function(response) {
                    return response.text();
                })
                .then(function(html) {
                    // Replace comments container content
                    var temp = document.createElement('div');
                    temp.innerHTML = html;

                    var newContainer = temp.querySelector('#comments-container');
                    if (newContainer) {
                        container.innerHTML = newContainer.innerHTML;
                    } else {
                        container.innerHTML = html;
                    }

                    // Re-init Alpine on the new content
                    if (window.Alpine) {
                        Alpine.initTree(container);
                    }

                    // Re-inject comment indicators into headings
                    reinjectCommentIndicators();

                    // Update sidebar comment badge
                    updateSidebarBadge();

                    // Close the new comment dialog if open
                    var dialog = document.getElementById('new-comment-dialog');
                    if (dialog) dialog.classList.add('hidden');

                    // Scroll to and flash the target comment
                    if (targetCommentId) {
                        var targetEl = document.getElementById('comment-' + targetCommentId);
                        if (targetEl) {
                            var card = targetEl.closest('[data-section], .rounded-lg');
                            if (card) flashCard(card);
                            targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                })
                .catch(function(err) {
                    if (window.showToast) showToast('Something went wrong. Retrying...', 'error');
                    // Fallback: submit the form normally
                    form.submit();
                });
        });
    }

    function reinjectCommentIndicators() {
        // Remove existing indicators
        document.querySelectorAll('.comment-indicator').forEach(function(el) { el.remove(); });

        var dataEl = document.getElementById('comment-data');
        if (!dataEl) return;
        var sectionCounts = JSON.parse(dataEl.dataset.comments || '{}');

        var headings = document.querySelectorAll('.prose h1, .prose h2, .prose h3');
        headings.forEach(function(heading) {
            // Strip any existing indicators from text comparison
            var text = heading.childNodes[0]?.textContent?.trim() || heading.textContent.trim();
            var count = sectionCounts[text] || 0;
            if (count > 0) {
                var badge = document.createElement('a');
                badge.href = '#comments-section';
                badge.className = 'comment-indicator';
                badge.onclick = function(e) {
                    e.preventDefault();
                    var cards = document.querySelectorAll('[data-section]');
                    cards.forEach(function(card) {
                        if (card.dataset.section === text) {
                            flashCard(card);
                            card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    });
                };
                badge.innerHTML = '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>' + count;
                heading.appendChild(badge);
            }
        });
    }

    function updateSidebarBadge() {
        var container = document.getElementById('comments-container');
        if (!container) return;
        var docId = container.dataset.docId;

        // Count open comments from the refreshed HTML
        var openCount = container.querySelectorAll('[id^="comment-"]').length;
        // More accurate: count from the comment-data element
        var dataEl = container.querySelector('#comment-data');
        if (dataEl) {
            var counts = JSON.parse(dataEl.dataset.comments || '{}');
            openCount = 0;
            for (var k in counts) openCount += counts[k];
        }

        // Find the sidebar item for this document and update its badge
        var sidebarItems = document.querySelectorAll('.sortable-item');
        sidebarItems.forEach(function(item) {
            // Check if this item's doc ID matches
            var idBadge = item.querySelector('[class*="font-mono"]');
            if (idBadge && idBadge.textContent.trim() === docId) {
                // Find or create the comment badge
                var existingBadge = item.querySelector('.sidebar-comment-badge');
                var link = item.querySelector('a');
                if (!link) return;

                if (openCount > 0) {
                    if (existingBadge) {
                        existingBadge.querySelector('span:last-child').textContent = openCount;
                    } else {
                        // Create badge
                        var badge = document.createElement('span');
                        badge.className = 'sidebar-comment-badge ml-auto shrink-0 flex items-center gap-0.5 text-[10px] text-amber-600';
                        badge.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg><span class="font-medium">' + openCount + '</span>';
                        // Insert before the last child (the arrow or status dot)
                        var changesDot = link.querySelector('[class*="rounded-full"]');
                        if (changesDot) {
                            link.insertBefore(badge, changesDot);
                        } else {
                            link.appendChild(badge);
                        }
                    }
                } else if (existingBadge) {
                    existingBadge.remove();
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', initCommentAjax);
</script>
@endpush
@endif
