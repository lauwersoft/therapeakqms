<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Unpublished Changes</h2>
            <a href="{{ route('documents.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Back to Documents</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
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
                {{-- Changed files list --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="font-semibold text-gray-800">{{ count($changedFiles) }} changed {{ Str::plural('file', count($changedFiles)) }}</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($changedFiles as $path => $status)
                            <div class="flex items-center justify-between px-4 py-3">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="shrink-0 text-xs font-medium px-2 py-0.5 rounded-full
                                        {{ $status === 'new' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $status === 'modified' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $status === 'deleted' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $status === 'added' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                    <span class="text-sm text-gray-700 truncate">{{ $path }}</span>
                                </div>
                                <form method="POST" action="{{ route('documents.discard') }}" class="shrink-0 ml-2">
                                    @csrf
                                    <input type="hidden" name="path" value="{{ $path }}">
                                    <button type="submit" class="text-xs text-gray-500 hover:text-red-600 px-2 py-1 rounded hover:bg-gray-100"
                                            onclick="return confirm('Discard changes to {{ $path }}?')">
                                        Discard
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Change log (who did what) --}}
                @if($changeLog->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                        <div class="p-4 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-800">Activity log</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach($changeLog as $change)
                                <div class="px-4 py-3 flex items-center gap-3 text-sm">
                                    <span class="font-medium text-gray-900">{{ $change->user->name }}</span>
                                    <span class="text-gray-500">{{ $change->action }}</span>
                                    <span class="text-gray-700">{{ $change->path }}</span>
                                    <span class="text-gray-400 ml-auto shrink-0">{{ $change->created_at->diffForHumans() }}</span>
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
                                <form method="POST" action="{{ route('documents.discard-all') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 px-3 py-1.5 rounded hover:bg-red-50"
                                            onclick="return confirm('Discard ALL unpublished changes? This cannot be undone.')">
                                        Discard all changes
                                    </button>
                                </form>
                                <button type="submit" class="px-5 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                    Publish {{ count($changedFiles) }} {{ Str::plural('change', count($changedFiles)) }}
                                </button>
                            </div>
                        </form>
                    @else
                        <p class="text-sm text-gray-600">Only admins can publish changes. Ask an admin to review and publish.</p>
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
