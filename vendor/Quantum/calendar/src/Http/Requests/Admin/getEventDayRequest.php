<?php
namespace Quantum\calendar\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class getEventDayRequest extends FormRequest
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

        return [
            'caldate' => 'date_format:"Y-n-j"',
            'filters.*' => 'nullable|numeric'
        ];
    }
}