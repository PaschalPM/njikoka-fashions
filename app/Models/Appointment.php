<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['first_name', 'last_name', 'email', 'service', 'preferred_date', 'time_slot', 'notes'])]
class Appointment extends Model
{
    /**
     * Follow-up stages shown in the admin, keyed by the stored value.
     *
     * @var array<string, string>
     */
    public const STATUSES = [
        'new' => 'New',
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ];

    protected function casts(): array
    {
        return ['preferred_date' => 'date'];
    }

    public function fullName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }
}
