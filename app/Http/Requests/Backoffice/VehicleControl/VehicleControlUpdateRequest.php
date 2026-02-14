<?php

namespace App\Http\Requests\Backoffice\VehicleControl;

use Illuminate\Foundation\Http\FormRequest;

class VehicleControlUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $controlId = $this->route('control') ? $this->route('control')->id : null;
        
        return [
            'agency_id' => [
                'required',
                'integer',
                'exists:agencies,id',
            ],
            'control_number' => [
                'required',
                'string',
                'max:50',
                'unique:vehicle_controls,control_number,' . $controlId,
            ],
            'vehicle_id' => [
                'required',
                'integer',
                'exists:vehicles,id',
            ],
            'start_mileage' => [
                'required',
                'integer',
                'min:0',
                'max:9999999',
            ],
            'end_mileage' => [
                'nullable',
                'integer',
                'min:0',
                'max:9999999',
                'gte:start_mileage',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'performed_by' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'agency_id.required' => 'L\'agence est obligatoire.',
            'agency_id.exists' => 'L\'agence sélectionnée n\'existe pas.',
            
            'control_number.required' => 'Le numéro de contrôle est obligatoire.',
            'control_number.unique' => 'Ce numéro de contrôle existe déjà.',
            
            'vehicle_id.required' => 'Le véhicule est obligatoire.',
            'vehicle_id.exists' => 'Le véhicule sélectionné n\'existe pas.',
            
            'start_mileage.required' => 'Le kilométrage de départ est obligatoire.',
            'start_mileage.integer' => 'Le kilométrage doit être un nombre entier.',
            
            'end_mileage.integer' => 'Le kilométrage doit être un nombre entier.',
            'end_mileage.gte' => 'Le kilométrage de fin doit être supérieur ou égal au kilométrage de départ.',
            
            'notes.max' => 'Les notes ne peuvent pas dépasser 1000 caractères.',
            
            'performed_by.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
        ];
    }
}