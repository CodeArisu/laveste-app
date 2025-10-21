<?php

namespace Database\Seeders;

use App\Enum\AppointmentStatus;
use App\Models\Statuses\AppointmentStatus as AppointmentStatusModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingStatus = AppointmentStatusModel::pluck('status_name')->toArray();
        $allStatus = array_map(fn($status) => $status->label(), AppointmentStatus::cases());

        if (count(array_diff($allStatus, $existingStatus))) {
            foreach (AppointmentStatus::cases() as $role) {
                DB::table('appointment_status')->insert(['status_name' => $role->label()]);
            }
        }
    }
}
