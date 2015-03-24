<?php 
namespace App\Http\Controllers;
use Response;
use Request;
use Hash;
use Validator;
use Auth;
use App\User;

class SettingsController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
	}

	public function postSave(){

		if (!Hash::check(Request::get('old'), Auth::user()->password)) {
		   return Response::json([
				'errors' => [
                	0 => array(trans('app.settings_wrond_pass'))
	            ],
	            'code' => 400 // bad request
		    ]);
		}

		$data 				= Request::all();

		$rules 	= [ 
			'old'				=> "required",
			'new'				=> 'required|min:6|confirmed',
			'new_confirmation'	=> 'required'
		];

		$messages 	= [
			'old.required'		=> trans('app.settings_old_req'),
			'new.required'		=> trans('app.settings_new_req'),
			'new.min'			=> trans('app.settings_new_min'),
			'new.confirmed'		=> trans('app.settings_new_confirmed'),
			'new_confirmation.required' => trans('app.settings_new_confirmation')
		];

		$validator = Validator::make($data, $rules, $messages);

		if ($validator->fails()) {
			return Response::json([
				'errors' => [
                	$validator->errors()->all()
	            ],
	            'code' => 400 // bad request
		    ]);
		}


		if ($user = User::find(Auth::user()->id)){

			$user->password = Hash::make(Request::get('new'));
			$user->save();
			return Response::json(array(),
		        200
		    );
		} else {
			return Response::json([
				'errors' => [
                	0 => array(trans('app.settings_error'))
	            ],
	            'code' => 400 // bad request
		    ]);
		}
	}

}
