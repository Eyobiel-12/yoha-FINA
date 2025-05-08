<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Invoice') }}: {{ $invoice->invoice_number }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    {{ __('Edit') }}
                </a>
                <a href="{{ route('invoices.pdf', $invoice) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    {{ __('Download PDF') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Invoice Information</h3>
                            <div class="mt-2 space-y-1">
                                <p><span class="font-semibold">Invoice Number:</span> {{ $invoice->invoice_number }}</p>
                                <p><span class="font-semibold">Invoice Date:</span> {{ $invoice->invoice_date->format('d-m-Y') }}</p>
                                <p><span class="font-semibold">Due Date:</span> {{ $invoice->getDueDateAttribute()->format('d-m-Y') }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 md:mt-0">
                            <h3 class="text-lg font-medium text-gray-900">Client</h3>
                            <div class="mt-2 space-y-1">
                                <p><span class="font-semibold">Name:</span> <a href="{{ route('clients.show', $invoice->client) }}" class="text-indigo-600 hover:text-indigo-900">{{ $invoice->client->company_name }}</a></p>
                                <p><span class="font-semibold">Address:</span> {{ $invoice->client->address }}</p>
                                <p><span class="font-semibold">Email:</span> {{ $invoice->client->email }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 md:mt-0">
                            <h3 class="text-lg font-medium text-gray-900">Status</h3>
                            <div class="mt-2">
                                <form action="{{ route('invoices.status', $invoice) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="flex items-center">
                                        <select name="status" class="rounded-l-md border-r-0 focus:ring-green-500 focus:border-green-500 w-40">
                                            <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                            <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                        </select>
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-r-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Update
                                        </button>
                                    </div>
                                </form>
                                
                                @if(!empty($invoice->client->email))
                                    <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 w-full bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            Send Email
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Invoice Items</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Rate</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($invoice->invoiceLines as $line)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $line->description }}
                                                @if($line->project)
                                                    <br><span class="text-xs text-gray-500">Project: <a href="{{ route('projects.show', $line->project) }}" class="text-indigo-600 hover:text-indigo-900">{{ $line->project->name }}</a></span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-900 text-right">{{ $line->hours }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 text-right">€{{ number_format($line->rate, 2) }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900 text-right">€{{ number_format($line->line_total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50">
                                        <th colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">Subtotal:</th>
                                        <td class="px-6 py-3 text-right text-sm text-gray-900">€{{ number_format($invoice->total_excl_btw, 2) }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <th colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-900">BTW ({{ $invoice->btw_percentage }}%):</th>
                                        <td class="px-6 py-3 text-right text-sm text-gray-900">€{{ number_format($invoice->btw_amount, 2) }}</td>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <th colspan="3" class="px-6 py-3 text-right text-sm font-bold text-gray-900">Total:</th>
                                        <td class="px-6 py-3 text-right text-sm font-bold text-gray-900">€{{ number_format($invoice->total_incl_btw, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between">
                <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Invoices
                </a>
            </div>
        </div>
    </div>
</x-app-layout> 