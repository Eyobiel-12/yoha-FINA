<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Client Details') }}: {{ $client->company_name }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    {{ __('Edit Client') }}
                </a>
                <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ __('Add Project') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Client Information Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Client Information') }}</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Company Name') }}</h4>
                            <p class="mt-1">{{ $client->company_name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Contact Person') }}</h4>
                            <p class="mt-1">{{ $client->contact_name }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Email') }}</h4>
                            <p class="mt-1">
                                <a href="mailto:{{ $client->email }}" class="text-blue-600 hover:text-blue-900">
                                    {{ $client->email }}
                                </a>
                            </p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Phone') }}</h4>
                            <p class="mt-1">{{ $client->phone }}</p>
                        </div>
                        
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-medium text-gray-500">{{ __('Address') }}</h4>
                            <p class="mt-1">{{ $client->address }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('KVK Number') }}</h4>
                            <p class="mt-1">{{ $client->kvk_number ?: 'Not provided' }}</p>
                        </div>
                        
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">{{ __('BTW Number') }}</h4>
                            <p class="mt-1">{{ $client->btw_number ?: 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Projects') }}</h3>
                        <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Add Project') }}
                        </a>
                    </div>
                    
                    @if($client->projects->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="py-3 px-4 text-left">{{ __('Project #') }}</th>
                                        <th class="py-3 px-4 text-left">{{ __('Name') }}</th>
                                        <th class="py-3 px-4 text-left">{{ __('Location') }}</th>
                                        <th class="py-3 px-4 text-left">{{ __('Start Date') }}</th>
                                        <th class="py-3 px-4 text-left">{{ __('Status') }}</th>
                                        <th class="py-3 px-4 text-right">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($client->projects as $project)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $project->project_number }}</td>
                                            <td class="py-3 px-4">
                                                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                                                    {{ $project->name }}
                                                </a>
                                            </td>
                                            <td class="py-3 px-4">{{ $project->location ?: 'N/A' }}</td>
                                            <td class="py-3 px-4">{{ $project->start_date->format('d-m-Y') }}</td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    {{ $project->end_date ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                    {{ $project->end_date ? 'Completed' : 'Active' }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-right space-x-2">
                                                <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-2 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                                    {{ __('View') }}
                                                </a>
                                                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-2 py-1 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                                                    {{ __('Edit') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        {{ __('No projects found for this client.') }}
                                        <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="font-medium underline">
                                            {{ __('Create a new project') }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Invoices Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">{{ __('Recent Invoices') }}</h3>
                        <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Create Invoice') }}
                        </a>
                    </div>
                    
                    @if($client->invoices->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg overflow-hidden">
                                <thead class="bg-gray-100 text-gray-700">
                                    <tr>
                                        <th class="py-3 px-4 text-left">{{ __('Invoice #') }}</th>
                                        <th class="py-3 px-4 text-left">{{ __('Date') }}</th>
                                        <th class="py-3 px-4 text-left">{{ __('Amount') }}</th>
                                        <th class="py-3 px-4 text-left">{{ __('Status') }}</th>
                                        <th class="py-3 px-4 text-right">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($client->invoices->take(5) as $invoice)
                                        <tr class="hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $invoice->invoice_number }}</td>
                                            <td class="py-3 px-4">{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                                            <td class="py-3 px-4">€ {{ number_format($invoice->total_incl_btw, 2) }}</td>
                                            <td class="py-3 px-4">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                                    {{ $invoice->status === 'unpaid' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                    {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}">
                                                    {{ ucfirst($invoice->status) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-right space-x-2">
                                                <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex items-center px-2 py-1 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                                    {{ __('View') }}
                                                </a>
                                                <a href="{{ route('invoices.pdf', $invoice) }}" class="inline-flex items-center px-2 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                                    {{ __('PDF') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($client->invoices->count() > 5)
                            <div class="mt-4 text-right">
                                <a href="{{ route('invoices.index', ['client_id' => $client->id]) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                    {{ __('View all invoices') }} →
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        {{ __('No invoices found for this client.') }}
                                        <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="font-medium underline">
                                            {{ __('Create a new invoice') }}
                                        </a>
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