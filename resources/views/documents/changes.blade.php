<x-app-layout>
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
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-green-700 mb-6">
                    {{ session('success') }}
                </div>
            @endif

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
                <div class="flex items-center gap-4 mb-6 text-sm">
                    <span class="text-gray-600">
                        <span class="font-semibold text-gray-900">{{ count($changedFiles) }}</span> changed {{ Str::plural('file', count($changedFiles)) }}
                    </span>
                    @php
                        $added = count(array_filter($changedFiles, fn($s) => in_array($s, ['new', 'added'])));
                        $modified = count(array_filter($changedFiles, fn($s) => $s === 'modified'));
                        $deleted = count(array_filter($changedFiles, fn($s) => $s === 'deleted'));
                    @endphp
                    @if($added)
                        <span class="text-green-700">+{{ $added }} added</span>
                    @endif
                    @if($modified)
                        <span class="text-amber-700">~{{ $modified }} modified</span>
                    @endif
                    @if($deleted)
                        <span class="text-red-700">-{{ $deleted }} deleted</span>
                    @endif
                </div>

                {{-- File diffs --}}
                @foreach($changedFiles as $path => $status)
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
                                    {{ $status === 'deleted' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($status) }}
                                </span>
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

                        {{-- Diff content --}}
                        <div x-show="open">
                            @if($status === 'deleted')
                                <div class="px-4 py-6 text-center text-sm text-gray-500">
                                    This file has been deleted.
                                </div>
                            @elseif(isset($diffs[$path]) && !empty(trim($diffs[$path])) && $status === 'modified')
                                <div class="overflow-x-auto">
                                    @php
                                        $diffLines = explode("\n", $diffs[$path]);
                                        $oldLineNum = 0;
                                        $newLineNum = 0;
                                        $changes = [];

                                        foreach ($diffLines as $line) {
                                            if (str_starts_with($line, 'diff ') || str_starts_with($line, 'index ') ||
                                                str_starts_with($line, '---') || str_starts_with($line, '+++')) {
                                                continue;
                                            }

                                            if (str_starts_with($line, '@@')) {
                                                preg_match('/@@ -(\d+),?\d* \+(\d+)/', $line, $m);
                                                $oldLineNum = (int)($m[1] ?? 0);
                                                $newLineNum = (int)($m[2] ?? 0);
                                                continue;
                                            }

                                            if (str_starts_with($line, '-')) {
                                                $changes[] = ['type' => 'removed', 'text' => substr($line, 1), 'line' => $oldLineNum];
                                                $oldLineNum++;
                                            } elseif (str_starts_with($line, '+')) {
                                                $changes[] = ['type' => 'added', 'text' => substr($line, 1), 'line' => $newLineNum];
                                                $newLineNum++;
                                            } else {
                                                $oldLineNum++;
                                                $newLineNum++;
                                            }
                                        }
                                    @endphp

                                    <table class="w-full">
                                        @foreach($changes as $dl)
                                            <tr class="{{ $dl['type'] === 'removed' ? 'bg-red-50' : 'bg-green-50' }}">
                                                <td class="diff-line text-right pr-2 pl-3 py-0 text-gray-400 select-none w-8 align-top">{{ $dl['line'] }}</td>
                                                <td class="diff-line py-0 px-1 w-4 text-center select-none {{ $dl['type'] === 'removed' ? 'text-red-400' : 'text-green-400' }}">{{ $dl['type'] === 'removed' ? '−' : '+' }}</td>
                                                <td class="diff-line py-0 pr-3 {{ $dl['type'] === 'removed' ? 'text-red-700' : 'text-green-700' }}">{{ $dl['text'] ?: ' ' }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @elseif(isset($diffs[$path]) && in_array($status, ['new', 'added']))
                                <div class="overflow-x-auto">
                                    <div class="px-4 py-2 bg-gray-50 text-xs text-gray-500 font-medium">New file</div>
                                    <table class="w-full">
                                        @foreach(explode("\n", $diffs[$path]) as $i => $line)
                                            <tr class="bg-green-50">
                                                <td class="diff-line text-right px-3 py-0.5 text-gray-300 select-none w-10 align-top border-r border-gray-100">{{ $i + 1 }}</td>
                                                <td class="diff-line px-4 py-0.5 text-green-700">{{ $line ?: ' ' }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            @else
                                <div class="px-4 py-6 text-center text-sm text-gray-500">
                                    No diff available.
                                </div>
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
                                    <span class="text-gray-400 ml-auto shrink-0 text-xs">{{ $change->created_at->diffForHumans() }}</span>
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
