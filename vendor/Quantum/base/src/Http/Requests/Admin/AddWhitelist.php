<?php namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;
use Laracasts\Flash\Flash;

/**
 * Class AddWhitelist
 * @package App\Http\Requests\Admin
 */
class AddWhitelist extends FormRequest {

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
			'ip_address' => 'required|ip|unique:whitelist,ip_address',
            'info' => 'required'
		];
	}

}
