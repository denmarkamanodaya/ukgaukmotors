<?php

namespace App\Http\Requests\Members;

use App\Http\Requests\Request;
use Quantum\base\Traits\RequestResponse;

class AuctioneerSearchRequest extends Request
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
        return [
            'name' => 'nullable|alpha_num_spaces|min:3',
            'location' => 'alpha_dash',
            'auctioneer' => 'alpha-dash'
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        if(isset($input['name'])) $input['name'] = filter_var($input['name'], FILTER_SANITIZE_STRING);
        if(isset($input['location'])) $input['location'] = filter_var($input['location'], FILTER_SANITIZE_STRING);
        if(isset($input['auctioneer'])) $input['auctioneer'] = filter_var($input['auctioneer'], FILTER_SANITIZE_STRING);

        $this->replace($input);
    }
}
