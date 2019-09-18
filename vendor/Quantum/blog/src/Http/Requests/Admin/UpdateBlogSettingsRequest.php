<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : UpdateRegistrationFormRequest.php
 **/

namespace Quantum\blog\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class UpdateBlogSettingsRequest extends FormRequest
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
            'enable_blog' => 'boolean',
            'blog_link_structure' => 'required|integer',
        ];
    }
}