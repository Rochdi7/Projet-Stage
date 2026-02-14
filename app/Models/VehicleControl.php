<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleControl extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vehicle_controls';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'agency_id',
        'rental_contract_id',
        'control_number',
        'vehicle_id',
        'start_mileage',
        'end_mileage',
        'notes',
        'performed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_mileage' => 'integer',
        'end_mileage' => 'integer',
    ];

    /**
     * Get the agency that owns the control.
     */
    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    /**
     * Get the vehicle that owns the control.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the user who performed the control.
     */
    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Get the items for this control.
     */
    public function items()
    {
        return $this->hasMany(VehicleControlItem::class, 'vehicle_control_id');
    }

    /**
     * Get formatted control number.
     */
    public function getFormattedControlNumberAttribute(): string
    {
        return $this->control_number;
    }

    /**
     * Get formatted start mileage with km.
     */
    public function getFormattedStartMileageAttribute(): string
    {
        return number_format($this->start_mileage, 0, ',', ' ') . ' km';
    }

    /**
     * Get formatted end mileage with km.
     */
    public function getFormattedEndMileageAttribute(): string
    {
        if ($this->end_mileage) {
            return number_format($this->end_mileage, 0, ',', ' ') . ' km';
        }
        return '—';
    }

    /**
     * Get mileage difference.
     */
    public function getMileageDifferenceAttribute(): ?int
    {
        if ($this->end_mileage) {
            return $this->end_mileage - $this->start_mileage;
        }
        return null;
    }

    /**
     * Get formatted mileage difference.
     */
    public function getFormattedMileageDifferenceAttribute(): string
    {
        $diff = $this->mileage_difference;
        if ($diff !== null) {
            return number_format($diff, 0, ',', ' ') . ' km';
        }
        return '—';
    }

    /**
     * Get status based on end_mileage presence.
     */
    public function getStatusAttribute(): string
    {
        return $this->end_mileage ? 'Terminé' : 'En cours';
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->end_mileage ? 'bg-success' : 'bg-warning';
    }

    /**
     * Get items count.
     */
    public function getItemsCountAttribute(): int
    {
        return $this->items()->count();
    }

    /**
     * Get passed items count (status = 'yes').
     */
    public function getPassedItemsCountAttribute(): int
    {
        return $this->items()->where('status', 'yes')->count();
    }

    /**
     * Get failed items count (status = 'no').
     */
    public function getFailedItemsCountAttribute(): int
    {
        return $this->items()->where('status', 'no')->count();
    }
}