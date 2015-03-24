<?php 
namespace App\Http\Controllers;
use Response;
use Request;
use Hash;
use Validator;
use Auth;
use App\User;

class UsersController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
	}


	public function getData(){

		return Response::json(User::with('role')->get());
	}


	public function postCreate(){

		$data = Request::all();
		
		//Validation
		$rules 	= [ 
			'username' 	=> 'required|unique:users|min:5',
			'email'		=> 'email',
			'password'	=> 'required|min:6'
		];

		$messages 	= [
			'username.required' 	=> trans('app.username_required'),
			'username.unique' 		=> trans('app.username_unique'),
			'username.min' 			=> trans('app.username_min'),
			'email.email'			=> trans('app.valid_email'),
			'password.required'		=> trans('app.password_required'),
			'password.min'			=> trans('app.password_min')
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

		//Create new user
		$data['password']	= Hash::make(Request::get('password'));
		$user 	= new User;
		$user->create($data);

		return Response::json(array(
	        $data),
	        200
	    );
	}


	public function getEdit($id){

		if ($user = User::find($id)){
			
			return Response::json(array(
		        $user),
		        200
		    );

		} else {

			return Response::json([
	            'code' => 400 // bad request
		   	]);
		}
	}

	public function postEditsave($id){

		if ($user = User::find($id)){

			$data 				= Request::all();
			//Validation
			$rules 	= [ 
				'email'		=> 'email'
			];

			$messages 	= [
				'email.email'			=> trans('app.valid_email')
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
			
			if (User::where('id','=',$id)->update($data)){

				return Response::json(array(),
			        200
			    );

			} else {

				return Response::json([
		            'errors' => trans('app.edit_save_error'),
		            'code' => 400 // bad request
			   	]);
			}
		} else {

			$error = array(array(trans('app.record_not_found')));
			return Response::json([
	            'errors' => $error,
	            'code' => 400 // bad request
		   	]);
		}

	}

	public function postDelete($id){

		if ($user = User::find($id)){

			$user->delete();


		} else {

			$error = array(array(trans('app.record_not_found')));
			return Response::json([
	            'errors' => $error,
	            'code' => 400 // bad request
		   	]);
		}
	}

	public function getLogedin(){

		return Response::json(Auth::user()->username);
	}

}
