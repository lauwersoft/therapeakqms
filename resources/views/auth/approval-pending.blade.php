<x-guest-layout>
    <div class="text-center">
        <div class="mx-auto w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-gray-800 mb-2">Account Inactive</h2>

        <p class="text-sm text-gray-600 mb-6">
            Your account is currently inactive. Please contact an administrator to activate your account.
        </p>

        <p class="text-xs text-gray-400 mb-2">
            Logged in as {{ Auth::user()->email }}
        </p>
        <p class="text-xs text-gray-400 mb-6">
            Contact: <a href="mailto:info@therapeak.com" class="text-blue-500 hover:text-blue-700">info@therapeak.com</a>
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
