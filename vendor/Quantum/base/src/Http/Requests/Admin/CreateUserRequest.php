<?php

namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class CreateUserRequest extends FormRequest
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
        $rules =  [
            'profilePic' => 'image',
            'username' => 'required|alpha_dash|unique:users,username',
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|unique:users,email',
            'password' => 'min:5|string|confirmed',
            'address' => 'nullable|string',
            'address2' => 'nullable|string',
            'city' => 'nullable|string',
            'county' => 'nullable|string',
            'postcode' => 'nullable|AlphaSpaces',
            'country_id' => 'nullable|numeric|exists:countries,id',
            'telephone' => 'nullable|Telephone',
            'status' => 'required|in:active,banned,inactive',
            'roles' => 'array'
        ];

        if(isset($input['notification']))
        {
            foreach($this->request->get('notification') as $key => $val)
            {
                if($key == 'email')
                {
                    $rules['notification.'.$key] = 'email';
                } else {
                    $rules['notification.'.$key] = 'string';
                }
            }
        }

        return $rules;
    }
}
