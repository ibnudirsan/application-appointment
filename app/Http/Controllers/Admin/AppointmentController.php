<?php

namespace App\Http\Controllers\Admin;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Enum\appointmentStatus;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    public function index()
    {
        return Appointment::query()
                ->with('client:id,first_name,last_name')
                ->when(request('status'), function ($query) {
                    return $query->where('status', appointmentStatus::from(request('status')));
                })
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
