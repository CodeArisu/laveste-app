<?php

namespace App\Services;

use App\Models\Transactions\Appointment;

class AppointmentService
{
    public function __construct(){}

    /**
     * Create a new appointment.
     *
     * @param \Illuminate\Http\Request $request
     * @return Appointment
     */
    private function createAppointment($request)
    {
        $data = $request->safe();
        $relations = [
            'customer_details_id' => $data['customer_details_id'],
            'appointment_status_id' => $data['appointment_status_id'],
        ];

        return $this->handleAppointment([
            'appointment_date' => $data['appointment_date'],
        ], $relations);
    }

    /**
     * Handle the appointment creation.
     *
     * @param array $data
     * @param array $relations
     * @return Appointment
     */
    private function handleAppointment(array $data, $relations) : Appointment
    {
        return Appointment::create([
            'customer_details_id' => $relations['customer_details_id'],
            'appointment_date' => $data['appointment_date'],
            'appointment_status_id' => $relations['appointment_status_id'],
        ]);
    }
}
