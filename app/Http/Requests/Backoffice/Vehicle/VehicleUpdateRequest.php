<?php

namespace App\Http\Requests\Backoffice\Vehicle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

public function rules(): array
{
    return [
        'vehicle_model_id'     => ['required', 'exists:vehicle_models,id'],
        'registration_number'  => ['required', 'string', 'max:50'],
        'vin'                  => ['nullable', 'string', 'max:50'],
        'current_mileage'      => ['nullable', 'integer', 'min:0'],
        'year'                 => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
        'color'                => ['required', 'string', 'max:50'],
        'status'               => ['required', 'in:available,booked,maintenance,unavailable'], // Changed from 'rented' to 'booked'
        'daily_rate'           => ['required', 'numeric', 'min:0'],
        'deposit_amount'       => ['nullable', 'numeric', 'min:0'],
        'fuel_policy'          => ['required', 'in:full_to_full,same_to_same'],
        'registration_city'    => ['nullable', 'string', 'max:100'],
        'has_gps'              => ['boolean'],
        'has_air_conditioning' => ['boolean'],
        'notes'                => ['nullable', 'string'],
        'featured_image'       => ['nullable', 'image', 'max:5120'],
    ];
}

}