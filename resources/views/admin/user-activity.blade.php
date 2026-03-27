<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">User Activity</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($users->isEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center text-gray-400">
                    No user activity recorded yet.
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    @foreach($users as $user)
                        <a href="{{ route('activity.show', $user) }}" class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-b-0">
                            <div class="relative">
                                <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=80&d=mp" alt="{{ $user->name }}" class="w-10 h-10 rounded-full">
                                @if($user->last_active_at?->gt(now()->subMinutes(5)))
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-green-500 rounded-full border-2 border-white"></span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-800">{{ $user->name }}</span>
                                    <span class="text-[10px] font-medium px-1.5 py-0.5 rounded
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-600' : '' }}
                                        {{ $user->role === 'editor' ? 'bg-blue-100 text-blue-600' : '' }}
                                        {{ $user->role === 'auditor' ? 'bg-gray-100 text-gray-500' : '' }}">{{ ucfirst($user->role) }}</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $user->email }}</span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="text-xs {{ $user->last_active_at?->gt(now()->subMinutes(5)) ? 'text-green-600 font-medium' : 'text-gray-400' }}">
                                    {{ $user->last_active_at?->gt(now()->subMinutes(5)) ? 'Online now' : ($user->last_active_at ? usertime($user->last_active_at)->diffForHumans() : 'Never') }}
                                </span>
                            </div>
                            <svg class="w-4 h-4 text-gray-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
