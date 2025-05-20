<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AppointmentRequest;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

class AppointmentController
{
    public function __construct(protected AppointmentService $appointmentService) {}

    public function preindex()
    {
        return view('src.cashier.appointment');
    }

    public function storeAppointmentSession(Request $request)
    {
        $request->merge([
            'date_schedule' => Carbon::parse($request->date_schedule)->format('Y-m-d'),
            'appointment_time' => date('H:i', strtotime($request->appointment_time))
        ]);

        $validated = Validator::make($request->all(), [
            'date_schedule' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i'
        ])->validate();

        return redirect()->route('cashier.appointment.checkout')->with([
            'date_schedule' => $validated['date_schedule'],
            'appointment_time' => $validated['appointment_time'],
        ]);
    }

    public function index() 
    {   
        if (!session()->has('date_schedule') || !session()->has('appointment_time')) {
            return redirect()->back()->withErrors('Appointment schedule not set');
        }

        return view('src.cashier.appointment');
    }

    public function store(AppointmentRequest $request)
    {
        $appointmentData = $this->appointmentService->createAppointment($request);
        
        return redirect()->route($appointmentData['route'])->with('success', $appointmentData['message']);
    }
}
