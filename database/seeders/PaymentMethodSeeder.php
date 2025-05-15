<?php

namespace Database\Seeders;

use App\Enum\PaymentMethods;
use App\Models\Transactions\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingMethods = array_map('strtolower', PaymentMethod::pluck('method_name')->toArray());
        $allMethod = array_map(fn($status) => $status->label(), PaymentMethods::cases());

        if (count(array_diff($allMethod, $existingMethods))) {
            foreach (PaymentMethods::cases() as $status) {
                // Truncate to 50 characters if needed
                DB::table('payment_methods')->insert(['method_name' => $status->label()]);
            }
        }

    }
}
