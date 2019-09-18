<?php

namespace App\Http\Requests\Members;

use App\Http\Requests\Request;

class AjaxVehicleType extends Request
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
            'vehicleType' => 'string|exists:vehicle_type,slug',
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['vehicleType'] = filter_var($input['vehicleType'], FILTER_SANITIZE_STRING);

        $this->replace($input);
    }
}
