<?php

namespace App\Http\Requests\Members;

use App\Http\Requests\Request;

class AjaxVehicleModelsRequest extends Request
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
            'vehicleModel' => 'string',
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['vehicleModel'] = filter_var($input['vehicleModel'], FILTER_SANITIZE_STRING);

        $this->replace($input);
    }
}
