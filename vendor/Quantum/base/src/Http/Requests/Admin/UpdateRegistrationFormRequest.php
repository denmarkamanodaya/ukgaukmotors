<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : UpdateRegistrationFormRequest.php
 **/

namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class UpdateRegistrationFormRequest extends FormRequest
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
        return [
            'register_first_name' => 'boolean',
            'register_last_name' => 'boolean',
            'register_address' => 'boolean',
            'register_address2' => 'boolean',
            'register_city' => 'boolean',
            'register_county' => 'boolean',
            'register_postcode' => 'boolean',
            'register_country' => 'boolean',
        ];
    }
}