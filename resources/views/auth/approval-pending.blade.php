<x-guest-layout>
    <div class="text-center">
        <div class="mx-auto w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center mb-4">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-gray-800 mb-2">Approval Pending</h2>

        <p class="text-sm text-gray-600 mb-6">
            Your account has been created but is not yet approved. An administrator will review and approve your account shortly.
        </p>

        <p class="text-xs text-gray-400 mb-6">
            Logged in as {{ Auth::user()->email }}
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                Log Out
            </button>
        </form>
    </div>
</x-guest-layout>
