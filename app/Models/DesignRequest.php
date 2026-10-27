<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['first_name', 'last_name', 'email', 'garment_type', 'fabrics', 'description', 'budget'])]
class DesignRequest extends Model
{
    /**
     * Follow-up stages shown in the admin, keyed by the stored value.
     *
     * @var array<string, string>
     */
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'in_progress' => 'In progress',
        'completed' => 'Completed',
        'declined' => 'Declined',
    ];

    protected function casts(): array
    {
        return ['fabrics' => 'array'];
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
