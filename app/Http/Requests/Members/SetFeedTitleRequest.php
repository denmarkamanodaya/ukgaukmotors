<?php

namespace App\Http\Requests\Members;

use App\Http\Requests\Request;

class SetFeedTitleRequest extends Request
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
            'id' => 'required|integer|exists:garage_feed',
            'title' => 'string'
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        if(isset($input['id'])) $input['id'] = filter_var($input['id'], FILTER_SANITIZE_STRING);
        if(isset($input['title'])) $input['title'] = filter_var($input['title'], FILTER_SANITIZE_STRING);

        $this->replace($input);
    }
}
