<?php

namespace App\Http\Requests\Members;

use App\Http\Requests\Request;

class VehicleSearchRequest extends Request
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
            'search' => 'nullable|AlphaSpaces|between:3,30',
            'auctioneer' => 'alpha-dash',
            'location' => 'AlphaSpaces',
            'vehicleMake' => 'alpha-dash',
            'vehicleModel' => 'numeric',
            'auctionDay' => 'alpha-dash',
            'listingType' => 'array'
        ];
    }

    public function sanitize()
    {
        $input = $this->all();

        if(isset($input['search'])) $input['search'] = filter_var($input['search'], FILTER_SANITIZE_STRING);
        if(isset($input['auctioneer'])) $input['auctioneer'] = filter_var($input['auctioneer'], FILTER_SANITIZE_STRING);
        if(isset($input['location'])) $input['location'] = filter_var($input['location'], FILTER_SANITIZE_STRING);
        if(isset($input['vehicleMake'])) $input['vehicleMake'] = filter_var($input['vehicleMake'], FILTER_SANITIZE_STRING);
        if(isset($input['vehicleModel'])) $input['vehicleModel'] = filter_var($input['vehicleModel'], FILTER_SANITIZE_STRING);
        if(isset($input['auctionDay'])) $input['auctionDay'] = filter_var($input['auctionDay'], FILTER_SANITIZE_STRING);

        if(isset($input['listingType']) && is_array($input['listingType']))
            foreach ($input['listingType'] as $key => $value)
            {
                $input['listingType'][$key] = filter_var($value, FILTER_SANITIZE_STRING);
            }
        $this->replace($input);
    }
}
