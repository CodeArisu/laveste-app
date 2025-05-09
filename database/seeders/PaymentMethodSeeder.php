<?php

namespace Database\Seeders;

use App\Enum\PaymentMethods;
use App\Models\Transactions\PaymentMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingMethods = array_map('strtolower', PaymentMethod::pluck('method_name')->toArray());
        $allMethod = array_map(fn($status) => strtolower($status->label()), PaymentMethods::cases());

        if (count(array_diff($allMethod, $existingMethods))) {
            foreach (PaymentMethods::cases() as $status) {
                PaymentMethod::updateOrCreate(['id' => $status->value, 'method_name' => $status->label()]);
            }
        }
    }
}
