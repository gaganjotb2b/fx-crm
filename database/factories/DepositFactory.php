<?php

namespace Database\Factories;

use App\Models\User;
use App\Services\common\UniqueIdGenService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deposit>
 */
class DepositFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $users = User::where('type', 0)->first();
        return [
            'user_id' => $users->id,
            'transaction_id' => UniqueIdGenService::payment_ref_no($users->id),
            'transaction_type' => 'crypto',
            'amount' => rand(100, 999),
            'approved_status' => 'A',
            'approved_date' => now(),
        ];
    }
}
