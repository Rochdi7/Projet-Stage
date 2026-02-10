<?php

namespace App\Http\Requests\Backoffice\Vehicle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VehicleStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

public function rules(): array
{
    return [
        'vehicle_model_id' => ['required', 'exists:vehicle_models,id'],
        'registration_number' => ['required', 'string', 'max:50'],
        'registration_city' => ['nullable', 'string', 'max:100'],
        'vin' => ['nullable', 'string', 'max:50'],

'color' => ['required', 'string', 'max:50'],
'year' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],

        'status' => ['required', 'in:available,unavailable,maintenance,sold'],
        'current_mileage' => ['nullable', 'integer', 'min:0'],

'daily_rate' => ['required', 'numeric', 'min:0'],

        'deposit_amount' => ['nullable', 'numeric', 'min:0'],

        'has_gps' => ['boolean'],
        'has_air_conditioning' => ['boolean'],

        'fuel_policy' => ['nullable', 'in:full_to_full,same_to_same,other'],
'fuel_level_out' => ['nullable', 'numeric', 'between:0,1'],
'fuel_level_in' => ['nullable', 'numeric', 'between:0,1'],
    ];
}
}
