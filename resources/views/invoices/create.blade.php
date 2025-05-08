<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Invoice') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('invoices.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- Invoice Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Invoice Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="client_id" :value="__('Client')" />
                                    <select id="client_id" name="client_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" required>
                                        <option value="">Select a client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="project_id" :value="__('Project (Optional)')" />
                                    <select id="project_id" name="project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500">
                                        <option value="">Select a project</option>
                                        @foreach($projects as $project)
                                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                                {{ $project->name }} ({{ $project->project_number }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <x-input-label for="invoice_number" :value="__('Invoice Number')" />
                                    <x-text-input id="invoice_number" name="invoice_number" type="text" class="mt-1 block w-full" :value="old('invoice_number')" required />
                                </div>

                                <div>
                                    <x-input-label for="invoice_date" :value="__('Invoice Date')" />
                                    <x-text-input id="invoice_date" name="invoice_date" type="date" class="mt-1 block w-full" :value="old('invoice_date', date('Y-m-d'))" required />
                                </div>

                                <div>
                                    <x-input-label for="payment_days" :value="__('Payment Term (days)')" />
                                    <x-text-input id="payment_days" name="payment_days" type="number" min="1" max="90" class="mt-1 block w-full" :value="old('payment_days', 14)" required />
                                </div>

                                <div>
                                    <x-input-label for="btw_percentage" :value="__('BTW Percentage')" />
                                    <x-text-input id="btw_percentage" name="btw_percentage" type="number" min="0" max="100" step="0.01" class="mt-1 block w-full" :value="old('btw_percentage', 21)" required />
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Items -->
                        <div>
                            <div class="flex justify-between items-center border-b pb-2 mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Invoice Items</h3>
                                <button type="button" id="add-item" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Add Item
                                </button>
                            </div>
                            
                            <div id="invoice-items" class="space-y-4">
                                <div class="invoice-item border rounded-md p-4 bg-gray-50">
                                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                                        <div class="md:col-span-3">
                                            <x-input-label for="items[0][description]" :value="__('Description')" />
                                            <x-text-input id="items[0][description]" name="items[0][description]" type="text" class="mt-1 block w-full" required />
                                        </div>
                                        <div>
                                            <x-input-label for="items[0][quantity]" :value="__('Quantity')" />
                                            <x-text-input id="items[0][quantity]" name="items[0][quantity]" type="number" min="0.01" step="0.01" class="mt-1 block w-full" value="1" required />
                                        </div>
                                        <div>
                                            <x-input-label for="items[0][price]" :value="__('Price')" />
                                            <x-text-input id="items[0][price]" name="items[0][price]" type="number" min="0.01" step="0.01" class="mt-1 block w-full" required />
                                        </div>
                                        <div class="flex items-end">
                                            <button type="button" class="remove-item text-red-600 hover:text-red-900 mt-1 invisible">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                                Cancel
                            </a>
                            <x-primary-button class="bg-green-600 hover:bg-green-700">
                                {{ __('Create Invoice') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let itemCount = 1;
            
            document.getElementById('add-item').addEventListener('click', function() {
                const itemTemplate = `
                    <div class="invoice-item border rounded-md p-4 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <div class="md:col-span-3">
                                <x-input-label for="items[${itemCount}][description]" :value="__('Description')" />
                                <input id="items[${itemCount}][description]" name="items[${itemCount}][description]" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" required />
                            </div>
                            <div>
                                <x-input-label for="items[${itemCount}][quantity]" :value="__('Quantity')" />
                                <input id="items[${itemCount}][quantity]" name="items[${itemCount}][quantity]" type="number" min="0.01" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" value="1" required />
                            </div>
                            <div>
                                <x-input-label for="items[${itemCount}][price]" :value="__('Price')" />
                                <input id="items[${itemCount}][price]" name="items[${itemCount}][price]" type="number" min="0.01" step="0.01" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" required />
                            </div>
                            <div class="flex items-end">
                                <button type="button" class="remove-item text-red-600 hover:text-red-900 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                const invoiceItems = document.getElementById('invoice-items');
                const newItem = document.createElement('div');
                newItem.innerHTML = itemTemplate;
                invoiceItems.appendChild(newItem.firstElementChild);
                
                itemCount++;
                
                // Add event listeners to new remove buttons
                document.querySelectorAll('.remove-item').forEach(button => {
                    button.addEventListener('click', function() {
                        if (document.querySelectorAll('.invoice-item').length > 1) {
                            this.closest('.invoice-item').remove();
                        }
                    });
                });
                
                // Make all remove buttons visible if we have more than one item
                if (document.querySelectorAll('.invoice-item').length > 1) {
                    document.querySelectorAll('.remove-item').forEach(button => {
                        button.classList.remove('invisible');
                    });
                }
            });
        });
    </script>
</x-app-layout> 