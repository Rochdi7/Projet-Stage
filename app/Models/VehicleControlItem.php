<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleControlItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vehicle_control_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_control_id',
        'item_key',
        'label',
        'status',
        'comment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * Get the control that owns the item.
     */
    public function vehicleControl()
    {
        return $this->belongsTo(VehicleControl::class, 'vehicle_control_id');
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'yes' => 'badge-success',
            'no' => 'badge-danger',
            default => 'badge-secondary',
        };
    }

    /**
     * Get status text.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'yes' => 'Oui',
            'no' => 'Non',
            default => 'N/A',
        };
    }
}