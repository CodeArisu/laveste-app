<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AppointmentRequest;
use App\Models\Transactions\Appointment;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class AppointmentController
{
    public function __construct(protected AppointmentService $appointmentService) {}

    public function preindex()
    {
        return view('src.cashier.appointment');
    }

    public function storeAppointmentSession(Request $request)
    {
        $result = $this->appointmentService->storeSession($request);

        if ($result['success']) {
            return redirect()->route('cashier.appointment.process')->with($result['data']);
        } else {
            return $result['error']['redirect'];
        }
    }

    public function showProcess()
    {
        if (!session()->has('date_schedule') || !session()->has('appointment_time')) {
            return redirect()->back() // Specify a concrete route
                ->withErrors('Please select an appointment time first.');
        }

        return view('src.cashier.appointment');
    }

    public function storeSession(AppointmentRequest $request)
    {   
        $validated = $request->validated();
        // store session temporarily
        Session::put($validated);

        return redirect()->route('cashier.appointment.check');
    }

    public function index()
    {   
        if (!session()->has('appointment_date') || !session()->has('appointment_time') 
            && !session()->has('name') && !session()->has('address') && !session()->has('contact')) {
            return redirect()->back() // Specify a concrete route
                ->withErrors('Please select an appointment time first.');
        }

        return view('src.cashier.checkout3');
    }

    public function store(AppointmentRequest $request)
    {   
        $appointmentData = $this->appointmentService->createAppointment($request);
        // Clear the session data
        session()->forget(['date_schedule', 'appointment_time', 'name', 'address', 'contact']);

        return redirect()->route($appointmentData['route'], $appointmentData['appointment'])->with('success', $appointmentData['message']);
    }

    public function show(Appointment $appointment)
    {
        return view('src.cashier.receipt2', ['appointment' => $appointment]);
    }
}
