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

class UpdateCategoryRequest extends FormRequest {

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
        $id = $segments[2];
        return [
            'name' => 'required|string',
            'slug' => 'required|alpha_dash|unique:site_categories,slug,'.$id.',slug,user_id,null',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'parent_id' => 'nullable|numeric|exists:site_categories,id'
        ];
    }

}