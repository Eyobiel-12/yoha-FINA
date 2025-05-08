<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PATCH')

                        <!-- Company Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Company Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="company_name" :value="__('Company Name')" />
                                    <x-text-input id="company_name" name="company_name" type="text" class="mt-1 block w-full" :value="$settings['company_name'] ?? ''" required />
                                </div>

                                <div>
                                    <x-input-label for="company_address" :value="__('Address')" />
                                    <x-text-input id="company_address" name="company_address" type="text" class="mt-1 block w-full" :value="$settings['company_address'] ?? ''" required />
                                </div>

                                <div>
                                    <x-input-label for="company_postal_city" :value="__('Postal Code & City')" />
                                    <x-text-input id="company_postal_city" name="company_postal_city" type="text" class="mt-1 block w-full" :value="$settings['company_postal_city'] ?? ''" required />
                                </div>

                                <div>
                                    <x-input-label for="company_phone" :value="__('Phone Number')" />
                                    <x-text-input id="company_phone" name="company_phone" type="text" class="mt-1 block w-full" :value="$settings['company_phone'] ?? ''" required />
                                </div>

                                <div>
                                    <x-input-label for="company_email" :value="__('Email')" />
                                    <x-text-input id="company_email" name="company_email" type="email" class="mt-1 block w-full" :value="$settings['company_email'] ?? ''" required />
                                </div>

                                <div>
                                    <x-input-label for="company_website" :value="__('Website')" />
                                    <x-text-input id="company_website" name="company_website" type="text" class="mt-1 block w-full" :value="$settings['company_website'] ?? ''" />
                                </div>

                                <div>
                                    <x-input-label for="company_kvk" :value="__('KVK Number')" />
                                    <x-text-input id="company_kvk" name="company_kvk" type="text" class="mt-1 block w-full" :value="$settings['company_kvk'] ?? ''" required />
                                </div>

                                <div>
                                    <x-input-label for="company_btw" :value="__('BTW Number')" />
                                    <x-text-input id="company_btw" name="company_btw" type="text" class="mt-1 block w-full" :value="$settings['company_btw'] ?? ''" required />
                                </div>

                                <div>
                                    <x-input-label for="logo" :value="__('Company Logo')" />
                                    <input id="logo" name="logo" type="file" class="mt-1 block w-full text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-gray-50 file:text-gray-700
                                        hover:file:bg-gray-100" accept="image/*" />
                                    @if(isset($settings['logo_path']))
                                        <div class="mt-2">
                                            <span class="text-xs text-gray-500">Current logo:</span>
                                            <img src="{{ asset($settings['logo_path']) }}" alt="Company Logo" class="h-12 mt-1 border rounded">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bank Information -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Bank Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="bank_name" :value="__('Bank Name')" />
                                    <x-text-input id="bank_name" name="bank_name" type="text" class="mt-1 block w-full" :value="$settings['bank_name'] ?? ''" required />
                                </div>

                                <div>
                                    <x-input-label for="bank_iban" :value="__('IBAN')" />
                                    <x-text-input id="bank_iban" name="bank_iban" type="text" class="mt-1 block w-full" :value="$settings['bank_iban'] ?? ''" required />
                                </div>
                            </div>
                        </div>

                        <!-- Invoice Settings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Invoice Settings</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="default_payment_days" :value="__('Default Payment Term (days)')" />
                                    <x-text-input id="default_payment_days" name="default_payment_days" type="number" min="1" max="90" class="mt-1 block w-full" :value="$settings['default_payment_days'] ?? 14" required />
                                </div>

                                <div>
                                    <x-input-label for="default_btw_percentage" :value="__('Default BTW Percentage')" />
                                    <x-text-input id="default_btw_percentage" name="default_btw_percentage" type="number" min="0" max="100" step="0.01" class="mt-1 block w-full" :value="$settings['default_btw_percentage'] ?? 21" required />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="bg-green-600 hover:bg-green-700">
                                {{ __('Save Settings') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 