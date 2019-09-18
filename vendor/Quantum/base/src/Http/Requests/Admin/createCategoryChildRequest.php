<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : AddUserMembershipRequest.php
 **/

namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class createCategoryChildRequest extends FormRequest {

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
            'child_name' => 'required|string',
            'child_slug' => 'required|alpha_dash|unique:site_categories,slug,NULL,id,user_id,0',
            'child_description' => 'nullable|string',
            'child_icon' => 'nullable|string',
            'parent_id' => 'required|exists:site_categories,id'
        ];
    }

}