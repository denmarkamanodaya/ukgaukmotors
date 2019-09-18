<?php

namespace App\Http\Requests\Members;

use App\Http\Requests\Request;

class CarDataSearchRequest extends Request
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
        $this->sanitize();
        return [
            'vehicleMakeData' => 'alpha-dash',
            'vehicleModel' => 'alpha-dash'
        ];
    }
    public function sanitize()
    {
        $input = $this->all();
        if(isset($input['vehicleMakeData'])) $input['vehicleMakeData'] = filter_var($input['vehicleMakeData'], FILTER_SANITIZE_STRING);
        if(isset($input['vehicleModel'])) $input['vehicleModel'] = filter_var($input['vehicleModel'], FILTER_SANITIZE_STRING);
        $this->replace($input);
    }
}
