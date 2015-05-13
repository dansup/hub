<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ApiUpdateNodeRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return (Request::input('addr') === Request::getClientIp());
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
		    'addr' => 'required|min:35|max:39',
		    'hostname' => 'required|min:3|max:50',
		    'ownername' => 'min:3|max:50',
		    'city' => 'min:3|max:50',
		    'province' => 'min:3|max:20',
		    'country' => 'min:3|max:20',
		    'lat' => 'min:2|max:7',
		    'lng' => 'min:2|max:7',
		];
	}

}
