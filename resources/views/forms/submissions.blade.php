<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Submissions</h2>
                <span class="text-sm font-mono text-gray-400">{{ $formId }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($submissions->isEmpty())
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                    <p class="text-gray-500">No submissions yet for this form.</p>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-left text-xs font-medium text-gray-500 px-5 py-3">Title</th>
                                <th class="text-left text-xs font-medium text-gray-500 px-5 py-3">Submitted by</th>
                                <th class="text-left text-xs font-medium text-gray-500 px-5 py-3">Date</th>
                                <th class="text-left text-xs font-medium text-gray-500 px-5 py-3">Status</th>
                                <th class="text-right text-xs font-medium text-gray-500 px-5 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($submissions as $sub)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="px-5 py-3 text-sm text-gray-800">{{ $sub->title }}</td>
                                    <td class="px-5 py-3 text-sm text-gray-600">{{ $sub->user->name }}</td>
                                    <td class="px-5 py-3 text-xs text-gray-400">{{ $sub->created_at->format('M j, Y H:i') }}</td>
                                    <td class="px-5 py-3">
                                        <span class="text-xs font-medium px-1.5 py-0.5 rounded
                                            {{ $sub->status === 'draft' ? 'bg-gray-100 text-gray-500' : '' }}
                                            {{ $sub->status === 'submitted' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $sub->status === 'approved' ? 'bg-green-100 text-green-700' : '' }}">
                                            {{ ucfirst($sub->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <a href="{{ route('forms.submission', $sub) }}" class="text-xs text-blue-600 hover:text-blue-800">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
