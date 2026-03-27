<x-app-layout>
    @section('page-title', 'Unpublished Changes')
    @push('styles')
        <style>
            .diff-line { font-family: ui-monospace, SFMono-Regular, 'SF Mono', Menlo, Consolas, monospace; font-size: 0.8rem; line-height: 1.5; white-space: pre-wrap; word-break: break-all; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Unpublished Changes</h2>
            <a href="{{ route('documents.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to Documents</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if(count($changedFiles) === 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-gray-500">All changes are published. Everything is up to date.</p>
                </div>
            @else
                {{-- Summary bar --}}
                @php
                    $counts = ['new' => 0, 'added' => 0, 'modified' => 0, 'deleted' => 0, 'move' => 0, 'rename' => 0];
                    foreach ($changedFiles as $info) { $counts[$info['status']] = ($counts[$info['status']] ?? 0) + 1; }
                    $addedCount = $counts['new'] + $counts['added'];
                    $movedCount = $counts['move'] + $counts['rename'];
                @endphp
                <div class="flex items-center gap-4 mb-6 text-sm flex-wrap">
                    <span class="text-gray-600">
                        <span class="font-semibold text-gray-900">{{ count($changedFiles) }}</span> {{ Str::plural('change', count($changedFiles)) }}
                    </span>
                    @if($addedCount)
                        <span class="text-green-700">+{{ $addedCount }} added</span>
                    @endif
                    @if($counts['modified'])
                        <span class="text-amber-700">~{{ $counts['modified'] }} modified</span>
                    @endif
                    @if($counts['deleted'])
                        <span class="text-red-700">-{{ $counts['deleted'] }} deleted</span>
                    @endif
                    @if($movedCount)
                        <span class="text-blue-700">{{ $movedCount }} moved/renamed</span>
                    @endif
                </div>

                {{-- File diffs --}}
                @foreach($changedFiles as $path => $info)
                    @php
                        $status = $info['status'];
                        $isDir = ($info['type'] ?? null) === 'directory';
                    @endphp
                    <div x-data="{ open: true }" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 overflow-hidden">
                        {{-- File header --}}
                        <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 cursor-pointer" @click="open = !open">
                            <div class="flex items-center gap-3 min-w-0">
                                <svg class="w-4 h-4 text-gray-400 transition-transform shrink-0" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full
                                    {{ in_array($status, ['new', 'added']) ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $status === 'modified' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $status === 'deleted' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ in_array($status, ['move', 'rename']) ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $status === 'create' && $isDir ? 'bg-green-100 text-green-700' : '' }}">
                                    {{ $isDir ? 'Directory ' : '' }}{{ ucfirst($status === 'move' ? 'moved' : ($status === 'rename' ? 'renamed' : ($status === 'create' ? 'created' : $status))) }}
                                </span>
                                @if($isDir)
                                    <svg class="w-4 h-4 text-yellow-500 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                                @endif
                                <span class="text-sm font-mono text-gray-700 truncate">{{ $path }}</span>
                            </div>
                            <form method="POST" action="{{ route('documents.discard') }}" class="shrink-0 ml-2" @click.stop>
                                @csrf
                                <input type="hidden" name="path" value="{{ $path }}">
                                <button type="submit" class="text-xs text-gray-500 hover:text-red-600 px-2 py-1 rounded hover:bg-gray-100"
                                        onclick="return confirm('Discard changes to {{ $path }}?')">
                                    Discard
                                </button>
                            </form>
                        </div>

                        {{-- Move/rename info --}}
                        @if(in_array($status, ['move', 'rename']) && isset($info['old_path']))
                            <div class="px-4 py-2 bg-blue-50 border-b border-blue-100 text-sm">
                                <span class="text-blue-600">{{ $status === 'rename' ? 'Renamed' : 'Moved' }} from</span>
                                <span class="font-mono text-blue-800">{{ $info['old_path'] }}</span>
                                <span class="text-blue-600">to</span>
                                <span class="font-mono text-blue-800">{{ $path }}</span>
                            </div>
                        @endif

                        {{-- Property changes --}}
                        @if(isset($metaChanges[$path]) && !empty($metaChanges[$path]))
                            <div class="px-4 py-3 bg-purple-50 border-b border-purple-100">
                                <div class="text-xs font-medium text-purple-600 mb-2">Property changes</div>
                                @foreach($metaChanges[$path] as $pc)
                                    <div class="flex items-center gap-2 text-sm mb-1 last:mb-0">
                                        <span class="text-purple-700 font-medium">{{ $pc['field'] }}:</span>
                                        @if($pc['old'])
                                            <span class="line-through text-red-500">{{ $pc['old'] }}</span>
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        @endif
                                        <span class="text-green-600 font-medium">{{ $pc['new'] ?? '(empty)' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Diff content --}}
                        <div x-show="open">
                            @if($isDir)
                                <div class="px-4 py-4 text-center text-sm text-gray-500">
                                    {{ $status === 'create' ? 'New directory created.' : ($status === 'delete' ? 'Directory deleted.' : ($status === 'rename' ? 'Directory renamed from ' . ($info['old_path'] ?? '?') . '.' : 'Directory changed.')) }}
                                </div>
                            @elseif($status === 'deleted' && isset($diffs[$path]))
                                {{-- Deleted file: show content that was removed --}}
                                <div class="overflow-x-auto">
                                    <div class="px-4 py-2 bg-red-50 text-xs text-red-600 font-medium border-b border-red-100">Entire file deleted</div>
                                    <table class="w-full">
                                        @foreach(explode("\n", $diffs[$path]) as $i => $line)
                                            <tr class="bg-red-50/50">
                                                <td class="diff-line text-right pr-2 pl-3 py-0 text-red-300 select-none w-12 align-top">{{ $i + 1 }}</td>
                                                <td class="diff-line py-0 px-1 w-4 text-center select-none text-red-300">−</td>
                                                <td class="diff-line py-0 pr-3 text-red-600/70">{{ $line ?: ' ' }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @elseif($status === 'deleted')
                                <div class="px-4 py-4 text-center text-sm text-red-500">This file has been deleted.</div>

                            @elseif(in_array($status, ['new', 'added']) && isset($diffs[$path]))
                                {{-- New file: show full content --}}
                                <div class="overflow-x-auto">
                                    <div class="px-4 py-2 bg-green-50 text-xs text-green-600 font-medium border-b border-green-100">New file</div>
                                    <table class="w-full">
                                        @foreach(explode("\n", $diffs[$path]) as $i => $line)
                                            <tr class="bg-green-50/50">
                                                <td class="diff-line text-right pr-2 pl-3 py-0 text-green-300 select-none w-12 align-top">{{ $i + 1 }}</td>
                                                <td class="diff-line py-0 px-1 w-4 text-center select-none text-green-300">+</td>
                                                <td class="diff-line py-0 pr-3 text-green-700">{{ $line ?: ' ' }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>

                            @elseif(in_array($status, ['move', 'rename']) && ($diffs[$path] ?? null) === null)
                                {{-- Move/rename with no content changes --}}
                                <div class="px-4 py-4 text-center text-sm text-gray-500">File {{ $status === 'rename' ? 'renamed' : 'moved' }}, no content changes.</div>

                            @elseif(isset($diffs[$path]) && !empty(trim($diffs[$path])))
                                {{-- Modified or move/rename with content changes: show diff --}}
                                <div class="overflow-x-auto">
                                    @php
                                        $diffLines = explode("\n", $diffs[$path]);
                                        $oldLineNum = 0;
                                        $newLineNum = 0;
                                        $rows = [];
                                        $inFrontmatter = false;

                                        foreach ($diffLines as $line) {
                                            if (str_starts_with($line, 'diff ') || str_starts_with($line, 'index ') ||
                                                str_starts_with($line, '---') || str_starts_with($line, '+++') ||
                                                str_starts_with($line, 'new file mode') || str_starts_with($line, 'deleted file mode') ||
                                                str_starts_with($line, '\\ No newline') || strlen($line) === 0) {
                                                continue;
                                            }

                                            if (str_starts_with($line, '@@')) {
                                                preg_match('/@@ -(\d+),?\d* \+(\d+)/', $line, $m);
                                                $oldLineNum = (int)($m[1] ?? 0);
                                                $newLineNum = (int)($m[2] ?? 0);
                                                // Detect if this hunk is in the frontmatter area (starts near line 1)
                                                $inFrontmatter = ($oldLineNum <= 1 || $newLineNum <= 1);
                                                if (!empty($rows)) {
                                                    $rows[] = ['type' => 'separator'];
                                                }
                                                continue;
                                            }

                                            // Skip frontmatter YAML lines (between --- markers at start of file)
                                            $lineContent = substr($line, 1);
                                            if ($inFrontmatter) {
                                                // Check if we've passed the closing ---
                                                if (trim($lineContent) === '---' || trim($line) === '---') {
                                                    // Skip the closing --- and mark end of frontmatter
                                                    if ($line[0] === ' ') {
                                                        // Context line with --- means we're at the closing marker
                                                        $inFrontmatter = false;
                                                    }
                                                    if ($line[0] === '-' || $line[0] === '+') {
                                                        // Changed --- line, skip it
                                                    }
                                                    if ($line[0] === '-') $oldLineNum++;
                                                    elseif ($line[0] === '+') $newLineNum++;
                                                    else { $oldLineNum++; $newLineNum++; }
                                                    continue;
                                                }

                                                // Skip all frontmatter content lines
                                                if ($line[0] === '-') $oldLineNum++;
                                                elseif ($line[0] === '+') $newLineNum++;
                                                else { $oldLineNum++; $newLineNum++; }
                                                continue;
                                            }

                                            if (str_starts_with($line, '-')) {
                                                $rows[] = ['type' => 'removed', 'text' => substr($line, 1), 'line' => $oldLineNum];
                                                $oldLineNum++;
                                            } elseif (str_starts_with($line, '+')) {
                                                $rows[] = ['type' => 'added', 'text' => substr($line, 1), 'line' => $newLineNum];
                                                $newLineNum++;
                                            } else {
                                                $text = str_starts_with($line, ' ') ? substr($line, 1) : $line;
                                                $rows[] = ['type' => 'context', 'text' => $text, 'line' => $newLineNum];
                                                $oldLineNum++;
                                                $newLineNum++;
                                            }
                                        }

                                        // Word-level highlight
                                        if (!function_exists('highlightWordDiff')) { function highlightWordDiff($old, $new) {
                                            $oldWords = preg_split('/([ \t]+)/', $old, -1, PREG_SPLIT_DELIM_CAPTURE);
                                            $newWords = preg_split('/([ \t]+)/', $new, -1, PREG_SPLIT_DELIM_CAPTURE);

                                            $prefixLen = 0;
                                            $minLen = min(count($oldWords), count($newWords));
                                            while ($prefixLen < $minLen && $oldWords[$prefixLen] === $newWords[$prefixLen]) {
                                                $prefixLen++;
                                            }

                                            $oldSuffixStart = count($oldWords);
                                            $newSuffixStart = count($newWords);
                                            while ($oldSuffixStart > $prefixLen && $newSuffixStart > $prefixLen &&
                                                   $oldWords[$oldSuffixStart - 1] === $newWords[$newSuffixStart - 1]) {
                                                $oldSuffixStart--;
                                                $newSuffixStart--;
                                            }

                                            $oldHtml = '';
                                            $newHtml = '';
                                            foreach ($oldWords as $i => $w) {
                                                $escaped = e($w);
                                                $oldHtml .= ($i >= $prefixLen && $i < $oldSuffixStart) ? '<mark class="bg-red-200 rounded-sm">' . $escaped . '</mark>' : $escaped;
                                            }
                                            foreach ($newWords as $i => $w) {
                                                $escaped = e($w);
                                                $newHtml .= ($i >= $prefixLen && $i < $newSuffixStart) ? '<mark class="bg-green-200 rounded-sm">' . $escaped . '</mark>' : $escaped;
                                            }

                                            return [$oldHtml, $newHtml];
                                        } }

                                        $highlighted = [];
                                        for ($i = 0; $i < count($rows); $i++) {
                                            if ($rows[$i]['type'] === 'removed' &&
                                                isset($rows[$i + 1]) && $rows[$i + 1]['type'] === 'added') {
                                                [$oldHtml, $newHtml] = highlightWordDiff($rows[$i]['text'], $rows[$i + 1]['text']);
                                                $highlighted[$i] = $oldHtml;
                                                $highlighted[$i + 1] = $newHtml;
                                            }
                                        }
                                    @endphp

                                    @if(in_array($status, ['move', 'rename']))
                                        <div class="px-4 py-2 bg-amber-50 text-xs text-amber-600 font-medium border-b border-amber-100">Content also changed</div>
                                    @endif

                                    <table class="w-full">
                                        @foreach($rows as $idx => $dl)
                                            @if($dl['type'] === 'separator')
                                                <tr>
                                                    <td colspan="3" class="py-1 px-3">
                                                        <div class="border-t border-gray-200 border-dashed"></div>
                                                    </td>
                                                </tr>
                                            @elseif($dl['type'] === 'context')
                                                <tr>
                                                    <td class="diff-line text-right pr-2 pl-3 py-0 text-gray-300 select-none w-12 align-top">{{ $dl['line'] }}</td>
                                                    <td class="diff-line py-0 px-1 w-4"></td>
                                                    <td class="diff-line py-0 pr-3 text-gray-500">{{ $dl['text'] ?: ' ' }}</td>
                                                </tr>
                                            @elseif($dl['type'] === 'removed')
                                                <tr class="bg-red-50">
                                                    <td class="diff-line text-right pr-2 pl-3 py-0 text-red-300 select-none w-12 align-top">{{ $dl['line'] }}</td>
                                                    <td class="diff-line py-0 px-1 w-4 text-center select-none text-red-400">−</td>
                                                    <td class="diff-line py-0 pr-3 text-red-700">{!! $highlighted[$idx] ?? e($dl['text'] ?: ' ') !!}</td>
                                                </tr>
                                            @elseif($dl['type'] === 'added')
                                                <tr class="bg-green-50">
                                                    <td class="diff-line text-right pr-2 pl-3 py-0 text-green-300 select-none w-12 align-top">{{ $dl['line'] }}</td>
                                                    <td class="diff-line py-0 px-1 w-4 text-center select-none text-green-400">+</td>
                                                    <td class="diff-line py-0 pr-3 text-green-700">{!! $highlighted[$idx] ?? e($dl['text'] ?: ' ') !!}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>

                            @else
                                <div class="px-4 py-4 text-center text-sm text-gray-500">No diff available.</div>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{-- Activity log --}}
                @if($changeLog->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800">Activity log</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($changeLog as $change)
                                <div class="px-4 py-3 flex items-center gap-3 text-sm">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-600 shrink-0">
                                        {{ strtoupper(substr($change->user->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-medium text-gray-900">{{ $change->user->name }}</span>
                                        <span class="text-gray-500">
                                            {{ match($change->action) {
                                                'edit' => 'edited',
                                                'create' => 'created',
                                                'delete' => 'deleted',
                                                'move' => 'moved',
                                                'rename' => 'renamed',
                                                default => $change->action,
                                            } }}
                                        </span>
                                        <span class="font-mono text-gray-700 text-xs">{{ $change->path }}</span>
                                        @if($change->action === 'move' && isset($change->details['old_path']))
                                            <span class="text-gray-400 text-xs">from {{ $change->details['old_path'] }}</span>
                                        @endif
                                        @if($change->action === 'rename' && isset($change->details['old_path']))
                                            <span class="text-gray-400 text-xs">was {{ basename($change->details['old_path']) }}</span>
                                        @endif
                                    </div>
                                    <span class="text-gray-400 ml-auto shrink-0 text-xs">{{ usertime($change->created_at)->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Publish / Discard All --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                    @if($canPublish)
                        <form method="POST" action="{{ route('documents.publish') }}">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-2">Publish message</label>
                            <input type="text" name="message" placeholder="e.g. Updated risk management procedures"
                                   class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500 mb-4" required>
                            <div class="flex items-center justify-between">
                                <div>
                                    <button type="button"
                                            onclick="if(confirm('Discard ALL unpublished changes? This cannot be undone.')) { document.getElementById('discard-all-form').submit(); }"
                                            class="text-sm text-red-600 hover:text-red-700 px-3 py-1.5 rounded hover:bg-red-50">
                                        Discard all changes
                                    </button>
                                </div>
                                <button type="submit" class="px-5 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                    Publish {{ count($changedFiles) }} {{ Str::plural('change', count($changedFiles)) }}
                                </button>
                            </div>
                        </form>
                        <form id="discard-all-form" method="POST" action="{{ route('documents.discard-all') }}" class="hidden">
                            @csrf
                        </form>
                    @else
                        <p class="text-sm text-gray-600">Only admins can publish changes. Ask an admin to review and publish.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
