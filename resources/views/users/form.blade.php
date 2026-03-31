<x-app-layout>
    @section('page-title', $user ? $user->name . ' — Edit' : 'Add User')
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $user ? 'Edit: ' . $user->name : 'Add User' }}
            </h2>
            <a href="{{ route('users.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-600 text-xs rounded-md hover:bg-gray-200">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span class="hidden sm:inline">Back to users</span><span class="sm:hidden">Back</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700 mb-6">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="POST" action="{{ $user ? route('users.update', $user) : route('users.store') }}" autocomplete="off">
                    @csrf
                    @if($user)
                        @method('PUT')
                    @endif

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user?->name) }}" required autofocus
                                   autocomplete="off"
                                   class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Full name">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user?->email) }}" required
                                   autocomplete="new-email"
                                   class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="email@example.com">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Organisation <span class="font-normal text-gray-400">— optional</span></label>
                            <input type="text" name="organisation" value="{{ old('organisation', $user?->organisation) }}"
                                   class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g. Scarlet, Pander Consultancy">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Timezone</label>
                            <select name="timezone" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach(timezone_identifiers_list() as $tz)
                                    <option value="{{ $tz }}" {{ old('timezone', $user?->timezone ?? 'Europe/Amsterdam') === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                            <select name="role" class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                                @foreach($roles as $role)
                                    <option value="{{ $role }}" {{ old('role', $user?->role ?? 'auditor') === $role ? 'selected' : '' }}>
                                        {{ ucfirst($role) }}
                                        @if($role === 'admin')
                                            — Full access, can publish changes and manage users
                                        @elseif($role === 'editor')
                                            — Can edit and manage documents
                                        @elseif($role === 'auditor')
                                            — Read-only access to all documents
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Password
                                @if($user)
                                    <span class="font-normal text-gray-400">— leave empty to keep current</span>
                                @else
                                    <span class="font-normal text-gray-400">— leave empty to generate random</span>
                                @endif
                            </label>
                            <input type="password" name="password"
                                   autocomplete="new-password"
                                   class="w-full border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="{{ $user ? '••••••••' : 'Min 8 characters (or leave empty for random)' }}"
                                   minlength="8">
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="approved" value="0">
                                <input type="checkbox" name="approved" value="1"
                                       {{ old('approved', $user?->approved ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </label>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Active</span>
                                <p class="text-xs text-gray-500">Inactive users cannot access the platform</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="track_activity" value="0">
                                <input type="checkbox" name="track_activity" value="1"
                                       {{ old('track_activity', $user?->track_activity ?? true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </label>
                            <div>
                                <span class="text-sm font-medium text-gray-700">Track activity</span>
                                <p class="text-xs text-gray-500">Record page views, actions, and session details</p>
                            </div>
                        </div>

                        @php
                            $notifPrefs = old('notifications', $user?->notifications ?? []);
                            $notifTypes = [
                                'comments' => ['label' => 'Comments', 'desc' => 'New comments and replies on documents'],
                                'publications' => ['label' => 'Publications', 'desc' => 'When documents are published or updated'],
                            ];
                        @endphp
                        <div class="border-t border-gray-100 pt-5">
                            <span class="text-sm font-medium text-gray-700 block mb-3">Email Notifications</span>

                            <div class="space-y-3">
                                @foreach($notifTypes as $key => $info)
                                    <div class="flex items-center gap-3">
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="notifications[{{ $key }}]" value="0">
                                            <input type="checkbox" name="notifications[{{ $key }}]" value="1"
                                                   {{ ($notifPrefs[$key] ?? \App\Models\User::NOTIFICATION_DEFAULTS[$key] ?? false) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                        </label>
                                        <div>
                                            <span class="text-sm text-gray-700">{{ $info['label'] }}</span>
                                            <p class="text-xs text-gray-500">{{ $info['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-8 pt-5 border-t border-gray-100">
                        <a href="{{ route('users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                        <button type="submit"
                                class="px-5 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                            {{ $user ? 'Save Changes' : 'Create User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
