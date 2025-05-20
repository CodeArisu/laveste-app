<?php

namespace App\Services;

use App\Enum\AppointmentStatus;
use App\Http\Requests\AppointmentRequest;
use App\Models\Transactions\Appointment;

class AppointmentService
{
    public function __construct(protected CustomerDetailService $customerDetailService) {}

    /**
     * Create a new appointment.
     *
     * @param \Illuminate\Http\Request $request
     * @return Appointment
     */
    public function createAppointment(AppointmentRequest $request)
    {   
        $validated = $request->validated();
        
        $customerDetails = $this->customerDetailService->requestUserDetails($validated);

        try {
            $appointmentDetails = $this->filterAppointmentData($validated);

            $this->handleAppointment([
                'appointment_date' => $appointmentDetails['appointment_date'],
                'appointment_time' => $appointmentDetails['appointment_time']
            ], [
                'customer_details_id' => $customerDetails->id,
                'appointment_status_id' => AppointmentStatus::Scheduled->value,
            ]);

            return [
                'message' => 'Appointment successfully added',
                'route' => 'appointment.receipt',
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function filterAppointmentData($request)
    {
        return [
            'appointment_date' => $request['appointment_date'],
            'appointment_time' => $request['appointment_time'],
        ];
    }

    /**
     * Handle the appointment creation.
     *
     * @param array $data
     * @param array $relations
     * @return Appointment
     */
    private function handleAppointment(array $data, array $relations): Appointment
    {
        return Appointment::create([
            'customer_details_id' => $relations['customer_details_id'],
            'appointment_date' => $data['appointment_date'],
            'appointment_time' => $data['appointment_time'],
            'appointment_status_id' => $relations['appointment_status_id'],
        ]);
    }
}
