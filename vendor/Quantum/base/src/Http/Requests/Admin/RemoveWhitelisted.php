<?php namespace Quantum\base\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

/**
 * Class RemoveWhitelisted
 * @package App\Http\Requests\Admin
 */
class RemoveWhitelisted extends FormRequest {

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
            'id' =>'required|exists:whitelist'
		];
	}

}
