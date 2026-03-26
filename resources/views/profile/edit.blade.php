<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
            <span class="text-xs font-medium px-1.5 py-0.5 rounded
                {{ Auth::user()->role === 'admin' ? 'bg-purple-100 text-purple-600' : '' }}
                {{ Auth::user()->role === 'editor' ? 'bg-blue-100 text-blue-600' : '' }}
                {{ Auth::user()->role === 'auditor' ? 'bg-gray-100 text-gray-500' : '' }}">{{ ucfirst(Auth::user()->role) }}</span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                @include('profile.partials.update-password-form')
            </div>
        </div>
    </div>
</x-app-layout>
