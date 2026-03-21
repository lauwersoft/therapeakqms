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
            @if(session('success'))
                @if(str_contains(session('success'), 'Generated password:'))
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
                @else
                    <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-green-700 mb-6">
                        {{ session('success') }}
                    </div>
                @endif
            @endif

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="text-left text-xs font-medium text-gray-500 px-5 py-3">Name</th>
                            <th class="text-left text-xs font-medium text-gray-500 px-5 py-3">Email</th>
                            <th class="text-left text-xs font-medium text-gray-500 px-5 py-3">Role</th>
                            <th class="text-left text-xs font-medium text-gray-500 px-5 py-3">Status</th>
                            <th class="text-left text-xs font-medium text-gray-500 px-5 py-3">Created</th>
                            <th class="text-right text-xs font-medium text-gray-500 px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                                            {{ $user->role === 'admin' ? 'bg-purple-100' : ($user->role === 'editor' ? 'bg-blue-100' : 'bg-gray-100') }}">
                                            <span class="text-xs font-semibold
                                                {{ $user->role === 'admin' ? 'text-purple-600' : ($user->role === 'editor' ? 'text-blue-600' : 'text-gray-500') }}">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                        <span class="text-sm font-medium text-gray-800">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : '' }}
                                        {{ $user->role === 'editor' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $user->role === 'auditor' ? 'bg-gray-100 text-gray-600' : '' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    @if($user->approved)
                                        <span class="inline-flex items-center gap-1 text-xs text-green-600">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs text-amber-600">
                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.828a1 1 0 101.415-1.414L11 9.586V6z" clip-rule="evenodd"/></svg>
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-xs text-gray-400">{{ $user->created_at->format('M j, Y') }}</td>
                                <td class="px-5 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="text-xs text-gray-500 hover:text-blue-600 px-2 py-1 rounded hover:bg-gray-100">
                                            Edit
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline"
                                                  onsubmit="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-gray-500 hover:text-red-600 px-2 py-1 rounded hover:bg-gray-100">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
