<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Enum\appointmentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;

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

    public function statusWithCount()
    {
        $cases = appointmentStatus::cases();
            return collect($cases)->map(fn ($case) => [
                'name'  => $case->name,
                'value' => $case->value,
                'count' => Appointment::where('status', $case->value)->count(),
                'color' => appointmentStatus::from($case->value)->color()
            ]);
    }

    public function clients()
    {
        return Client::latest()->get();
    }

    public function store()
    {
        $validated = request()->validate([
            'client_id'     => 'required',
            'title'         => 'required',
            'description'   => 'required',
            'start_time'    => 'required',
            'end_time'      => 'required',
        ], [
            'client_id.required' => 'The Client Name field is required...',
        ]);

        $appointment = Appointment::create([
            'title'         => $validated['title'],
            'client_id'     => $validated['client_id'],
            'start_time'    => $validated['start_time'],
            'end_time'      => $validated['end_time'],
            'status'        => appointmentStatus::SCHEDULED,
            'description'   => $validated['description'],
        ]);
            return response()->json([
                'message'   => 'Successfully created',
            ]);
    }

    public function edit(Appointment $appointment)
    {
        return $appointment;
    }

    public function update(Appointment $appointment)
    {
        $validated = request()->validate([
            'client_id'     => 'required',
            'title'         => 'required',
            'description'   => 'required',
            'start_time'    => 'required',
            'end_time'      => 'required',
        ], [
            'client_id.required' => 'The Client Name field is required...',
        ]);
        $appointment->update($validated);
            return response()->json([
                'message'   => 'Successfully updated',
            ]);
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
            return response()->json([
                'success'   => true,
                'message'   => 'Successfully deleted',
            ], 200);
    }

    public function appointmentsCount()
    {
        $totalAppointmentsCount = Appointment::query()
                ->when(request('status') === 'scheduled', function ($query) {
                    $query->where('status', appointmentStatus::SCHEDULED);
                })
                ->when(request('status') === 'confirmed', function ($query) {
                    $query->where('status', appointmentStatus::COMFIRMED);
                })
                ->when(request('status') === 'cancelled', function ($query) {
                    $query->where('status', appointmentStatus::CANCELLED);
                })
                ->count();
        return response()->json([
            'totalAppointmentsCout' => $totalAppointmentsCount
        ]);
    }
}