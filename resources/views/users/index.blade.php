<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Users</h2>
                <span class="text-sm text-gray-400">{{ $users->count() }}</span>
            </div>
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add user
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success') && str_contains(session('success'), 'Generated password:'))
                @php
                    $parts = explode('Generated password: ', session('success'));
                @endphp
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-green-700">{{ $parts[0] }}</p>
                    <div class="mt-2 flex items-center gap-3 bg-white rounded-md border border-green-200 px-3 py-2">
                        <span class="text-xs text-gray-500">Generated password:</span>
                        <code class="text-sm font-mono font-bold text-gray-800 select-all">{{ $parts[1] }}</code>
                        <span class="text-xs text-red-500 ml-auto">Save this — it won't be shown again</span>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                @foreach($users as $user)
                    <div class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                        {{-- Avatar --}}
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0
                                {{ $user->role === 'admin' ? 'bg-purple-100' : '' }}
                                {{ $user->role === 'editor' ? 'bg-blue-100' : '' }}
                                {{ $user->role === 'auditor' ? 'bg-gray-100' : '' }}">
                                <span class="text-sm font-semibold
                                    {{ $user->role === 'admin' ? 'text-purple-600' : '' }}
                                    {{ $user->role === 'editor' ? 'text-blue-600' : '' }}
                                    {{ $user->role === 'auditor' ? 'text-gray-500' : '' }}">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            @if($user->last_active_at?->gt(now()->subMinutes(5)))
                                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-medium text-gray-800">{{ $user->name }}</span>
                                <span class="text-[10px] font-medium px-1.5 py-0.5 rounded
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-600' : '' }}
                                    {{ $user->role === 'editor' ? 'bg-blue-100 text-blue-600' : '' }}
                                    {{ $user->role === 'auditor' ? 'bg-gray-100 text-gray-500' : '' }}">{{ ucfirst($user->role) }}</span>
                                @if(!$user->approved)
                                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-amber-100 text-amber-600">Pending</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="text-xs text-gray-400">{{ $user->email }}</span>
                                @if($user->organisation)
                                    <span class="text-[10px] text-gray-300">·</span>
                                    <span class="text-xs text-gray-400">{{ $user->organisation }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Last active --}}
                        <div class="text-right shrink-0 hidden sm:block">
                            <span class="text-xs {{ $user->last_active_at?->gt(now()->subMinutes(5)) ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                                @if($user->last_active_at?->gt(now()->subMinutes(5)))
                                    Online now
                                @elseif($user->last_active_at)
                                    {{ $user->last_active_at->diffForHumans() }}
                                @else
                                    Never
                                @endif
                            </span>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1 shrink-0">
                            @if(auth()->user()->isAdmin() && $user->id !== auth()->id())
                                <a href="{{ route('activity.show', $user) }}" class="p-1.5 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-600" title="Activity">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </a>
                            @endif
                            <a href="{{ route('users.edit', $user) }}" class="p-1.5 rounded hover:bg-gray-100 text-gray-400 hover:text-gray-600" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline"
                                      onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded hover:bg-red-50 text-gray-400 hover:text-red-600" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
