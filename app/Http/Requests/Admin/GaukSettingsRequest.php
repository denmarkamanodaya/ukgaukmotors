<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use Quantum\base\Traits\RequestResponse;

class GaukSettingsRequest extends Request
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
            'gauk_import_api_key' => 'required|string',
            'gauk_import_api_status' => 'required|in:live,test',
            'google_map_api_key' => 'required|string',
            'main_content_role' => 'required|exists:roles,name',
        ];
    }
}
