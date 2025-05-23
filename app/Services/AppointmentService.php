<?php

namespace App\Services;

use App\Enum\AppointmentStatus;
use App\Http\Requests\AppointmentRequest;
use App\Models\Transactions\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class AppointmentService
{
    public function __construct(protected CustomerDetailService $customerDetailService) {}

    public function storeSession(Request $request)
    {
        $request->merge([
            'date_schedule' => Carbon::parse($request->date_schedule)->format('Y-m-d'),
            'appointment_time' => date('H:i', strtotime($request->appointment_time))
        ]);

        try {
            $validated = Validator::make($request->all(), [
                'date_schedule' => 'required|date|after_or_equal:today',
                'appointment_time' => 'required|date_format:H:i'
            ])->validate();

            $this->checkIfAppointmentExists($validated);

            return [
                'success' => true,
                'data' => [
                    'date_schedule' => $validated['date_schedule'],
                    'appointment_time' => $validated['appointment_time']
                ]
            ];
        } catch (\RuntimeException $e) {
            return [
                'success' => false,
                'error' => [
                    'message' => $e->getMessage(),
                    'redirect' => redirect()->back()
                        ->withInput()
                        ->withErrors([
                            'internal_error' => 'Appointment Conflict',
                            'internal_error_description' => $e->getMessage(),
                        ])
                ]
            ];
        }
    }

    private function checkIfAppointmentExists($validated)
    {
        if (Appointment::where('appointment_date', $validated['date_schedule'])
            ->where('appointment_time', $validated['appointment_time'])
            ->exists()
        ) {
            throw new \RuntimeException('This time slot is already booked.');
        }
    }

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

            $appointment = $this->handleAppointment([
                'appointment_date' => $appointmentDetails['appointment_date'],
                'appointment_time' => $appointmentDetails['appointment_time']
            ], [
                'customer_details_id' => $customerDetails->id,
                'appointment_status_id' => AppointmentStatus::Scheduled->value,
            ]);

            return [
                'appointment' => $appointment,
                'message' => 'Appointment successfully added',
                'route' => 'cashier.appointment.show',
            ];
        } catch (\Exception $e) {
            dd($e->getMessage());
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
