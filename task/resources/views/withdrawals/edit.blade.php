@extends('layouts.dashboard')

@section('title', 'Edit Withdrawal')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">Edit Withdrawal</h1>
                <a href="{{ route('withdrawals.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Back to List
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Please fix the following errors:</strong>
                <ul class="mt-2 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <form action="{{ route('withdrawals.update', $withdrawal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="date">
                        Date
                    </label>
                    <input type="date" name="date" id="date" value="{{ old('date', $withdrawal->date->format('Y-m-d')) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('date') border-red-500 @enderror"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="customer_name">
                        Customer Name
                    </label>
                    <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name', $withdrawal->customer_name) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('customer_name') border-red-500 @enderror"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="customer_phone">
                        Customer Phone
                    </label>
                    <input type="tel" name="customer_phone" id="customer_phone" value="{{ old('customer_phone', $withdrawal->customer_phone) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('customer_phone') border-red-500 @enderror"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="customer_email">
                        Customer Email
                    </label>
                    <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email', $withdrawal->customer_email) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('customer_email') border-red-500 @enderror">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="manager_id">
                        Manager ID
                    </label>
                    <input type="number" name="manager_id" id="manager_id" value="{{ old('manager_id', $withdrawal->manager_id) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('manager_id') border-red-500 @enderror"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="country_id">
                        Country
                    </label>
                    <select name="country_id" id="country_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('country_id') border-red-500 @enderror"
                        required>
                        <option value="">Select Country</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $withdrawal->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="amount">
                        Amount
                    </label>
                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $withdrawal->amount) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('amount') border-red-500 @enderror"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="usdt_rate">
                        USDT Rate
                    </label>
                    <input type="number" step="0.01" name="usdt_rate" id="usdt_rate" value="{{ old('usdt_rate', $withdrawal->usdt_rate) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('usdt_rate') border-red-500 @enderror"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="amount_in_usdt">
                        Amount in USDT
                    </label>
                    <input type="text" name="amount_in_usdt" id="amount_in_usdt" value="{{ old('amount_in_usdt', $withdrawal->amount_in_usdt) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-gray-50"
                        readonly>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="account_number">
                        Account Number
                    </label>
                    <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $withdrawal->account_number) }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('account_number') border-red-500 @enderror"
                        required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $withdrawal->description) }}</textarea>
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Withdrawal
                    </button>
                </div>
            </form>
        </div>
    </div>

    @section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const amountInput = document.getElementById('amount');
            const usdtRateInput = document.getElementById('usdt_rate');
            const amountInUsdtInput = document.getElementById('amount_in_usdt');

            function calculateUSDT() {
                const amount = parseFloat(amountInput.value) || 0;
                const rate = parseFloat(usdtRateInput.value) || 0;
                
                if (rate > 0) {
                    const usdt = amount / rate;
                    amountInUsdtInput.value = usdt.toFixed(2);
                } else {
                    amountInUsdtInput.value = '0.00';
                }
            }

            // Calculate initially
            calculateUSDT();

            // Add event listeners
            amountInput.addEventListener('input', calculateUSDT);
            usdtRateInput.addEventListener('input', calculateUSDT);
        });
    </script>
    @endsection
@endsection 