<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Quantum\base\Traits\RequestResponse;


class EditVehicleMakeRequest extends Request
{
    use RequestResponse;
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
        $segments = $this->segments();
        $slug = $segments[2];

        return [
            'name' => 'required|unique:vehicle_make,name,'.$slug.',slug',
            'logo' => 'nullable|image',
            'country_id' => 'exists:countries,id'
        ];
    }
}
