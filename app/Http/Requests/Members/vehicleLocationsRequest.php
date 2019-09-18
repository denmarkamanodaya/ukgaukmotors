<?php

namespace App\Http\Requests\Members;

use App\Http\Requests\Request;

class vehicleLocationsRequest extends Request
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
            'auctions' => 'in:true,false',
            'classifieds' => 'in:true,false',
        ];
    }
}
