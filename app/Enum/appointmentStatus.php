<?php

namespace App\Enum;

enum appointmentStatus: int
{
    case SCHEDULED = 1;
    case COMFIRMED = 2;
    case CANCELLED = 3;

    public function color(): string
    {
        return match ($this) {
            AppointmentStatus::SCHEDULED => 'primary',
            AppointmentStatus::COMFIRMED => 'success',
            AppointmentStatus::CANCELLED => 'danger',
        };
    }
}
