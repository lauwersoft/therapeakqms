<x-app-layout>
    @push('styles')
        <style>
            .diff-line { font-family: ui-monospace, SFMono-Regular, 'SF Mono', Menlo, Consolas, monospace; font-size: 0.8rem; line-height: 1.5; white-space: pre-wrap; word-break: break-all; }
        </style>
    @endpush

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Revision {{ $commit['short_hash'] }}</h2>
                <span class="text-sm text-gray-400">{{ $commit['date']->format('F j, Y \a\t H:i') }}</span>
            </div>
            <a href="{{ route('documents.history') }}" class="text-sm text-gray-500 hover:text-gray-900">Back to History</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Commit info --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-6">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-100 flex items-center justify-center shrink-0">
                        <span class="text-sm font-semibold text-blue-600">{{ strtoupper(substr($commit['author'], 0, 1)) }}</span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-800">{{ $commit['author'] }}</span>
                            <span class="text-xs text-gray-400">{{ $commit['date']->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1 whitespace-pre-line">{{ $commit['message'] }}</p>
                        <div class="flex flex-wrap gap-1.5 mt-3">
                            @foreach($commit['files'] as $file)
                                <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full
                                    {{ $file['status'] === 'added' ? 'bg-green-50 text-green-700' : '' }}
                                    {{ $file['status'] === 'modified' ? 'bg-blue-50 text-blue-700' : '' }}
                                    {{ $file['status'] === 'deleted' ? 'bg-red-50 text-red-600' : '' }}">
                                    @if($file['doc_id'])
                                        <span class="font-mono font-medium">{{ $file['doc_id'] }}</span>
                                    @endif
                                    <span>{{ $file['doc_title'] }}</span>
                                    <span class="text-[10px] opacity-70">
                                        {{ $file['status'] === 'added' ? 'created' : ($file['status'] === 'deleted' ? 'removed' : 'updated') }}
                                    </span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- File diffs --}}
            @foreach($commit['files'] as $file)
                @php
                    $dir = dirname($file['path']);
                    $dirLabel = ($dir !== '.' && $dir !== '') ? ucwords(str_replace(['-', '_', '/'], [' ', ' ', ' / '], $dir)) . ' / ' : '';
                @endphp
                <div x-data="{ open: true }" class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200 cursor-pointer" @click="open = !open">
                        <div class="flex items-center gap-3 min-w-0">
                            <svg class="w-4 h-4 text-gray-400 transition-transform shrink-0" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full
                                {{ $file['status'] === 'added' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $file['status'] === 'modified' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $file['status'] === 'deleted' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ $file['status'] === 'added' ? 'Created' : ($file['status'] === 'deleted' ? 'Removed' : 'Updated') }}
                            </span>
                            @if($file['doc_id'])
                                <span class="text-xs text-gray-400">{{ $file['doc_id'] }}</span>
                            @endif
                            <span class="text-sm text-gray-700 truncate">
                                @if($dirLabel)<span class="text-gray-400">{{ $dirLabel }}</span>@endif{{ $file['doc_title'] }}
                            </span>
                        </div>
                    </div>

                    <div x-show="open" x-cloak>
                        {{-- Property changes --}}
                        @if(!empty($file['metaChanges']))
                            <div class="px-4 py-3 bg-purple-50 border-b border-purple-100">
                                <div class="text-xs font-medium text-purple-600 mb-2">Property changes</div>
                                @foreach($file['metaChanges'] as $pc)
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

                        @if($file['status'] === 'deleted' && empty($file['diff']))
                            <div class="px-4 py-4 text-center text-sm text-gray-500">File was removed in this revision.</div>

                        @elseif($file['status'] === 'deleted' && ! empty($file['diff']))
                            {{-- Deleted file: show old content --}}
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    @foreach(explode("\n", $file['diff']) as $i => $line)
                                        <tr class="bg-red-50/50">
                                            <td class="diff-line text-right pr-2 pl-3 py-0 text-red-300 select-none w-12 align-top">{{ $i + 1 }}</td>
                                            <td class="diff-line py-0 px-1 w-4 text-center select-none text-red-400">−</td>
                                            <td class="diff-line py-0 pr-3 text-red-600/70">{{ $line ?: ' ' }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>

                        @elseif($file['status'] === 'added' && ! empty($file['diff']) && ! str_contains($file['diff'], '@@'))
                            {{-- New file: show full content --}}
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    @foreach(explode("\n", $file['diff']) as $i => $line)
                                        <tr class="bg-green-50/50">
                                            <td class="diff-line text-right pr-2 pl-3 py-0 text-green-300 select-none w-12 align-top">{{ $i + 1 }}</td>
                                            <td class="diff-line py-0 px-1 w-4 text-center select-none text-green-400">+</td>
                                            <td class="diff-line py-0 pr-3 text-green-700">{{ $line ?: ' ' }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>

                        @elseif(! empty($file['diff']))
                            {{-- Standard diff --}}
                            <div class="overflow-x-auto">
                                @php
                                    $diffLines = explode("\n", $file['diff']);
                                    $oldLineNum = 0;
                                    $newLineNum = 0;
                                    $rows = [];
                                    $inFrontmatter = false;

                                    foreach ($diffLines as $line) {
                                        if (str_starts_with($line, 'diff ') || str_starts_with($line, 'index ') ||
                                            str_starts_with($line, '---') || str_starts_with($line, '+++') ||
                                            str_starts_with($line, 'new file mode') || str_starts_with($line, 'deleted file mode') ||
                                            str_starts_with($line, '\\ No newline')) {
                                            continue;
                                        }

                                        if (str_starts_with($line, '@@')) {
                                            preg_match('/@@ -(\d+),?\d* \+(\d+)/', $line, $m);
                                            $oldLineNum = (int)($m[1] ?? 0);
                                            $newLineNum = (int)($m[2] ?? 0);
                                            $inFrontmatter = ($oldLineNum <= 1 || $newLineNum <= 1);
                                            if (!empty($rows)) {
                                                $rows[] = ['type' => 'separator'];
                                            }
                                            continue;
                                        }

                                        if (strlen($line) === 0) continue;

                                        $lineContent = substr($line, 1);
                                        if ($inFrontmatter) {
                                            if (trim($lineContent) === '---' || trim($line) === '---') {
                                                if ($line[0] === ' ') $inFrontmatter = false;
                                                if ($line[0] === '-') $oldLineNum++;
                                                elseif ($line[0] === '+') $newLineNum++;
                                                else { $oldLineNum++; $newLineNum++; }
                                                continue;
                                            }
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

                                    // Word-level highlighting
                                    if (!function_exists('_highlightWordDiff')) {
                                        function _highlightWordDiff($old, $new) {
                                            $oldWords = preg_split('/([ \t]+)/', $old, -1, PREG_SPLIT_DELIM_CAPTURE);
                                            $newWords = preg_split('/([ \t]+)/', $new, -1, PREG_SPLIT_DELIM_CAPTURE);
                                            $prefixLen = 0;
                                            $minLen = min(count($oldWords), count($newWords));
                                            while ($prefixLen < $minLen && $oldWords[$prefixLen] === $newWords[$prefixLen]) $prefixLen++;
                                            $oldSuffixStart = count($oldWords);
                                            $newSuffixStart = count($newWords);
                                            while ($oldSuffixStart > $prefixLen && $newSuffixStart > $prefixLen &&
                                                   $oldWords[$oldSuffixStart - 1] === $newWords[$newSuffixStart - 1]) { $oldSuffixStart--; $newSuffixStart--; }
                                            $oldHtml = '';
                                            $newHtml = '';
                                            foreach ($oldWords as $i => $w) {
                                                $e = e($w);
                                                $oldHtml .= ($i >= $prefixLen && $i < $oldSuffixStart) ? '<mark class="bg-red-200 rounded-sm">' . $e . '</mark>' : $e;
                                            }
                                            foreach ($newWords as $i => $w) {
                                                $e = e($w);
                                                $newHtml .= ($i >= $prefixLen && $i < $newSuffixStart) ? '<mark class="bg-green-200 rounded-sm">' . $e . '</mark>' : $e;
                                            }
                                            return [$oldHtml, $newHtml];
                                        }
                                    }

                                    $highlighted = [];
                                    for ($i = 0; $i < count($rows); $i++) {
                                        if ($rows[$i]['type'] === 'removed' && isset($rows[$i + 1]) && $rows[$i + 1]['type'] === 'added') {
                                            [$oldHtml, $newHtml] = _highlightWordDiff($rows[$i]['text'], $rows[$i + 1]['text']);
                                            $highlighted[$i] = $oldHtml;
                                            $highlighted[$i + 1] = $newHtml;
                                        }
                                    }
                                @endphp

                                @if(empty($rows) || collect($rows)->every(fn($r) => $r['type'] === 'separator'))
                                    @if(!empty($file['metaChanges']))
                                        <div class="px-4 py-3 text-center text-sm text-gray-400">No content changes — only properties were updated.</div>
                                    @else
                                        <div class="px-4 py-3 text-center text-sm text-gray-400">No visible changes.</div>
                                    @endif
                                @else
                                <table class="w-full">
                                    @foreach($rows as $idx => $dl)
                                        @if($dl['type'] === 'separator')
                                            <tr><td colspan="3" class="py-1 px-3"><div class="border-t border-gray-200 border-dashed"></div></td></tr>
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
                                @endif
                            </div>
                        @else
                            @if(!empty($file['metaChanges']))
                                <div class="px-4 py-3 text-center text-sm text-gray-400">No content changes — only properties were updated.</div>
                            @else
                                <div class="px-4 py-4 text-center text-sm text-gray-500">No changes to display.</div>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
