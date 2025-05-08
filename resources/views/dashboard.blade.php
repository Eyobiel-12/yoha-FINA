<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Clients Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                                <svg class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="font-semibold text-xl">{{ $stats['clients_count'] ?? 0 }}</h2>
                                <p class="text-gray-500">Clients</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Projects Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                                <svg class="h-8 w-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="font-semibold text-xl">{{ $stats['projects_count'] ?? 0 }}</h2>
                                <p class="text-gray-500">Projects</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Unpaid Invoices Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                                <svg class="h-8 w-8 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="font-semibold text-xl">{{ $stats['unpaid_invoices'] ?? 0 }}</h2>
                                <p class="text-gray-500">Unpaid Invoices</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Value Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                                <svg class="h-8 w-8 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h2 class="font-semibold text-xl">€{{ number_format($financials['total_unpaid'] ?? 0, 2) }}</h2>
                                <p class="text-gray-500">Outstanding Amount</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart and Recent Invoices -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-8">
                <!-- Revenue Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Monthly Revenue</h3>
                        <canvas id="revenueChart" height="200"></canvas>
                    </div>
                </div>

                <!-- Recent Invoices -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-semibold mb-4">Recent Invoices</h3>
                        
                        @if(isset($recentInvoices) && $recentInvoices->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentInvoices as $invoice)
                                    <div class="border-b pb-2">
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                                {{ $invoice->invoice_number }}
                                            </a>
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $invoice->status === 'unpaid' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}
                                            ">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $invoice->client->company_name }}</div>
                                        <div class="flex justify-between text-sm">
                                            <span>{{ $invoice->invoice_date->format('d M Y') }}</span>
                                            <span class="font-medium">€{{ number_format($invoice->total_incl_btw, 2) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-right">
                                <a href="{{ route('invoices.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View all invoices →</a>
                            </div>
                        @else
                            <p class="text-gray-500">No recent invoices found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');

            // Get the chart data from the PHP variable
            const chartData = @json($chartData ?? []);
            
            // Prepare the data for the chart
            const labels = [
                'January', 'February', 'March', 'April', 'May', 'June', 
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            
            const data = Object.entries(chartData).sort((a, b) => a[0] - b[0]).map(item => item[1]);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Monthly Revenue (€)',
                        data: data,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgb(59, 130, 246)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '€' + value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
