<?php
namespace Quantum\calendar\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class createCalendarEventRequest extends FormRequest
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

        $rules = [
            'title' => 'required|string',
            'category.*' => 'nullable|numeric',
            'description' => 'nullable|string',
            'all_day_event' => 'in:yes,no',
            'start_date' => 'date_format:"Y-m-d"',
            'start_time' => 'nullable|required_unless:all_day_event,yes|date_format:"H:i"',
            'end_date' => 'nullable|date_format:"Y-m-d"',
            'end_time' => 'nullable|date_format:"H:i"',
            'repeat_event' => 'in:yes,no',
            'repeat_type' => 'in:daily,weekly,monthly,yearly',
            'repeat_days.*' => 'nullable|in:1,2,3,4,5,6,0',
            'repeat_weeks.*' => 'nullable|in:1,2,3,4',
            'repeat_months.*' => 'nullable|in:01,02,03,04,05,06,07,08,09,10,11,12',
            'address' => 'nullable|string',
            'county' => 'nullable|string',
            'country_id' => 'nullable|numeric|exists:countries,id',
            'postcode' => 'nullable|string',
            'latitude' => 'nullable|string',
            'longitude' => 'nullable|string',
            'event_image' => 'nullable|string',
            'event_url' => 'nullable|string',
            'repeat_amount' => 'nullable|integer|min:0|max:999',
            'import' => 'nullable|exists:import,id',
            'extra_start_dates.*' => 'nullable|date_format:"Y-m-d'
        ];

        return $rules;
    }

    public function sanitize()
    {
        $input = $this->all();
        $input['address'] = filter_var($input['address'], FILTER_SANITIZE_STRING);
        $input['county'] = filter_var($input['county'], FILTER_SANITIZE_STRING);
        $input['country_id'] = filter_var($input['country_id'], FILTER_SANITIZE_STRING);
        $input['postcode'] = filter_var($input['postcode'], FILTER_SANITIZE_STRING);
        $input['latitude'] = filter_var($input['latitude'], FILTER_SANITIZE_STRING);
        $input['longitude'] = filter_var($input['longitude'], FILTER_SANITIZE_STRING);
        $input['event_image'] = filter_var($input['event_image'], FILTER_SANITIZE_STRING);
        $input['event_url'] = filter_var($input['event_url'], FILTER_SANITIZE_STRING);

        $this->replace($input);
    }
}