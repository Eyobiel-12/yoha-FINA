<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Clients') }}
            </h2>
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Client
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('clients.index') }}" class="mb-6">
                        <div class="flex gap-4">
                            <div class="flex-1">
                                <x-text-input
                                    id="search"
                                    name="search"
                                    type="text"
                                    class="w-full"
                                    placeholder="Search client name, contact, or email..."
                                    value="{{ request('search') }}"
                                />
                            </div>
                            <x-primary-button type="submit">
                                {{ __('Search') }}
                            </x-primary-button>
                            @if(request('search'))
                                <a href="{{ route('clients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300">
                                    {{ __('Clear') }}
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Clients Table -->
                    @if($clients->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="py-3 px-4 text-left">Company</th>
                                        <th class="py-3 px-4 text-left">Contact</th>
                                        <th class="py-3 px-4 text-left">Email</th>
                                        <th class="py-3 px-4 text-left">Phone</th>
                                        <th class="py-3 px-4 text-left">Projects</th>
                                        <th class="py-3 px-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($clients as $client)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4">
                                                <a href="{{ route('clients.show', $client) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                                    {{ $client->company_name }}
                                                </a>
                                            </td>
                                            <td class="py-3 px-4">{{ $client->contact_name }}</td>
                                            <td class="py-3 px-4">
                                                <a href="mailto:{{ $client->email }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $client->email }}
                                                </a>
                                            </td>
                                            <td class="py-3 px-4">{{ $client->phone }}</td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('projects.index', ['client_id' => $client->id]) }}" class="text-blue-600 hover:text-blue-900">
                                                    {{ $client->projects_count ?? $client->projects->count() }} projects
                                                </a>
                                            </td>
                                            <td class="py-3 px-4 text-right space-x-2">
                                                <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-3 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                                                    Edit
                                                </a>
                                                <form action="{{ route('clients.destroy', $client) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $clients->links() }}
                        </div>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        No clients found. Please create a new client.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 