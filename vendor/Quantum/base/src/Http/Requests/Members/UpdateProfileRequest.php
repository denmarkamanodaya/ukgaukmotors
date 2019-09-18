<?php

namespace Quantum\base\Http\Requests\Members;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class UpdateProfileRequest extends FormRequest
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
        $this->sanitize();
        $id = \Auth::user()->id;
        $rules =  [
            'profilePic' => 'image',
            'first_name' => 'required|alpha',
            'last_name' => 'required|alpha',
            'email' => 'required|email|unique:users,email,'.$id.',id',
            'password' => 'nullable|min:5|string|confirmed',
            'address' => 'nullable|string',
            'address2' => 'nullable|string',
            'city' => 'nullable|string',
            'county' => 'nullable|string',
            'postcode' => 'nullable|AlphaSpaces',
            'country_id' => 'nullable|numeric|exists:countries,id',
            'telephone' => 'nullable|Telephone'
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

    public function sanitize()
    {
        $input = $this->all();

        $input['first_name'] = filter_var($input['first_name'], FILTER_SANITIZE_STRING);
        $input['last_name'] = filter_var($input['last_name'], FILTER_SANITIZE_STRING);
        $input['email'] = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
        $input['address'] = filter_var($input['address'], FILTER_SANITIZE_STRING);
        $input['address2'] = filter_var($input['address2'], FILTER_SANITIZE_STRING);
        $input['city'] = filter_var($input['city'], FILTER_SANITIZE_STRING);
        $input['county'] = filter_var($input['county'], FILTER_SANITIZE_STRING);
        $input['postcode'] = filter_var($input['postcode'], FILTER_SANITIZE_STRING);
        $input['country_id'] = filter_var($input['country_id'], FILTER_SANITIZE_STRING);
        $input['telephone'] = filter_var($input['telephone'], FILTER_SANITIZE_STRING);

        if(isset($input['notification']))
        {
            foreach($input['notification'] as $key => $val)
            {
                if($key == 'email')
                {
                    $input['notification']['email'] = filter_var($val, FILTER_SANITIZE_EMAIL);
                } else {
                    $input['notification'][$val] = filter_var($val, FILTER_SANITIZE_STRING);
                }
            } 
        }
        

        $this->replace($input);
    }
}
