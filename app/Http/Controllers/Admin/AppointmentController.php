<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::query()
                ->with('client:id,first_name,last_name')
                ->latest()
                ->paginate()
                ->through(fn ($appointment) => [
                    'id'            => $appointment->id,
                    'start_time'    => $appointment->start_time->format('Y-m-d H:i:s'),
                    'end_time'      => $appointment->end_time->format('Y-m-d H:i:s'),
                    'client'        => $appointment->client,
                    'status'        => [
                        'name'  => $appointment->status->name,
                        'color' => $appointment->status->color()
                    ]
                ]);
    }
}
