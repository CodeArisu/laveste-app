<?php

namespace Database\Seeders;

use App\Enum\RentStatus;
use App\Models\Statuses\ProductRentedStatus as StatusesProductRentedStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductRentedStatus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingStatus = StatusesProductRentedStatus::pluck('status_name')->toArray();
        $allStatus = array_map(fn($status) => $status->label(), RentStatus::cases());

        if (count(array_diff($allStatus, $existingStatus))) {
            foreach (RentStatus::cases() as $role) {
                DB::table('product_rented_status')->insert(['status_name' => $role->label()]);
            }
        }
    }
}
