<?php

namespace App\Models;

use App\Enum\appointmentStatus;
use App\Models\Client;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'client_id',
        'start_time',
        'end_time',
        'status',
        'description'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'status' => appointmentStatus::class
    ];

    protected $appends = ['formatted_start_time', 'formatted_end_time'];

    public function client() : BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function formattedStartTime(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->start_time->format('Y-m-d H:i')
        );
    }

    public function formattedEndTime(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->end_time->format('Y-m-d H:i')
        );
    }
}
