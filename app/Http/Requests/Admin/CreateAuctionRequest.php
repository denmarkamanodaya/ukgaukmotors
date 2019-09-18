<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class CreateAuctionRequest extends Request
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
            'title' => 'required|string',
            'vehicleMake' => 'required|string',
            'vehicleModel' => 'required|string',
            'vehicleVarient' => 'nullable|string',
            'description' => 'nullable|string',
            'price' => 'nullable|regex:/^\d*(\.\d{1,2})?$/',
            'colour' => 'nullable|string',
            'mileage' => 'nullable|integer',
            'gearbox' => 'required|in:unlisted,automatic,manual,semi-automatic',
            'fuel' => 'required|in:unlisted,diesel,electric,hybrid,petrol',
            'registration' => 'nullable|string',
            'mot' => 'nullable|string',
            'website' => 'nullable|url',
        ];
    }
}
