<?php

namespace App\Http\Requests\Members;

use App\Http\Requests\Request;

class AjaxVehicleModelSearch extends Request
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
            'vehicleMake' => 'alpha-dash',
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        $input['vehicleMake'] = filter_var($input['vehicleMake'], FILTER_SANITIZE_STRING);

        $this->replace($input);
    }
}
