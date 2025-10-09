@extends('layouts.dashboard')

@section('title', 'View Withdrawal')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-semibold text-gray-900">View Withdrawal</h1>
                <div class="flex space-x-4">
                    <a href="{{ route('withdrawals.edit', $withdrawal->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                    <a href="{{ route('withdrawals.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        Back to List
                    </a>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Customer Name</label>
                            <p class="text-gray-900">{{ $withdrawal->customer_name }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Customer Phone</label>
                            <p class="text-gray-900">{{ $withdrawal->customer_phone }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Customer Email</label>
                            <p class="text-gray-900">{{ $withdrawal->customer_email ?: 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Transaction Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Date</label>
                            <p class="text-gray-900">{{ $withdrawal->date->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Manager ID</label>
                            <p class="text-gray-900">{{ $withdrawal->manager_id }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Country</label>
                            <p class="text-gray-900">{{ $withdrawal->country->name }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Amount Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Amount</label>
                            <p class="text-gray-900">{{ number_format($withdrawal->amount, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">USDT Rate</label>
                            <p class="text-gray-900">{{ number_format($withdrawal->usdt_rate, 2) }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Amount in USDT</label>
                            <p class="text-gray-900">{{ number_format($withdrawal->amount_in_usdt, 2) }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Account Number</label>
                            <p class="text-gray-900">{{ $withdrawal->account_number }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Description</label>
                            <p class="text-gray-900">{{ $withdrawal->description ?: 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-1">Created By</label>
                            <p class="text-gray-900">{{ $withdrawal->created_by }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 border-t pt-6">
                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-600">
                        <p>Created: {{ $withdrawal->created_at->format('Y-m-d H:i:s') }}</p>
                        <p>Last Updated: {{ $withdrawal->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                    <form action="{{ route('withdrawals.destroy', $withdrawal->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this withdrawal?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Delete Withdrawal
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection 