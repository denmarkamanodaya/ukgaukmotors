<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : CreateShortcodeRequest.php
 **/

namespace Quantum\base\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class CreateShortcodeRequest extends FormRequest
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
            'title' => 'required|string',
            'name' => 'required|alpha_dash|unique:shortcodes,name',
            'replace' => 'required|string',
            'description' => 'required|string'
        ];
    }
}