<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreCommentRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return false;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		    return [
		        'caid' => 'required|min:39|max:42',
		        'ct' => 'required',
		        'cid' => 'required|min:10|max:60',
		        'body' => 'required|min:5|max:140',
		    ];
	}

}
