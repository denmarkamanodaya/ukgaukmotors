<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Quantum\base\Traits\RequestResponse;

class EditVehicleTypeRequest extends Request
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
            'name' => 'required|unique:vehicle_type,name,'.$slug.',slug'
        ];
    }
}
