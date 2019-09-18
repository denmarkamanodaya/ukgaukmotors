<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CreateClassifiedRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vehicleType' => 'required|string',
            'title' => 'required|string',
            'vehicleMake' => 'required|string',
            'vehicleModel' => 'required|string',
            'vehicleVarient' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'image' => 'nullable|image',
            'features' => 'nullable|array',
            'vehicle_body_type_id' => 'required|exists:vehicle_body_type,id',
            'colour' => 'nullable|string',
            'mileage' => 'nullable|integer',
            'gearbox' => 'required|in:automatic,manual,semi-automatic',
            'fuel' => 'required|in:diesel,electric,hybrid,petrol',
            'vehicle_engine_size_id' => 'required|exists:vehicle_engine_size,id',
            'registration' => 'nullable|string',
            'mot' => 'nullable|string',
            'service_history' => 'required|string',
            'address' => 'required|string',
            'address2' => 'nullable|string',
            'city' => 'required|string',
            'county' => 'required|string',
            'postcode' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'required|email',
            'website' => 'nullable|url',
        ];
    }
}
