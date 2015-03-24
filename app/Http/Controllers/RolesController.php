<?php 
namespace App\Http\Controllers;
use Response;
use Request;
use Hash;
use Validator;
use Auth;
use App\Role;

class RolesController extends Controller {


	public function __construct()
	{
		$this->middleware('auth');
	}


	public function getData(){

		return Response::json(Role::get());
	}


	public function postCreate(){

		$data = Request::all();
		
		//Validation
		$rules 	= [ 
			'name' 				=> 'required|unique:roles|min:5'
		];

		$messages 	= [
			'name.required' 	=> trans('app.name_required'),
			'name.unique' 		=> trans('app.name_unique'),
			'name.min' 			=> trans('app.name_min')
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

		//Create new
		$data['permissions']	= json_encode(Request::get('permissions'));
		$rec 	= new Role;
		$rec->create($data);

		return Response::json(array(
	        $data),
	        200
	    );
	}


	public function getEdit($id){

		if ($rec = Role::find($id)){

			$rec['permissions'] = json_decode($rec['permissions']);
 			
			return Response::json(array(
		        $rec),
		        200
		    );

		} else {

			return Response::json([
	            'code' => 400 // bad request
		   	]);
		}
	}

	public function postEditsave($id){

		if ($rec = Role::find($id)){

			$data 				= Request::all();

			$data['permissions']= json_encode(Request::get('permissions'));
			
			if (Role::where('id','=',$id)->update($data)){

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

		if ($rec = Role::find($id)){

			$rec->delete();

		} else {

			$error = array(array(trans('app.record_not_found')));
			return Response::json([
	            'errors' => $error,
	            'code' => 400 // bad request
		   	]);
		}
	}

	public function getItem($id) {

		if ($rec = Role::find($id)){
			
			return Response::json(array(
		        $rec),
		        200
		    );

		} else {

			return Response::json([
	            'code' => 400 // bad request
		   	]);
		}
	}

	public function getAppRoles(){

		$user_rol	= Auth::user()->role;

		if ($roles = Role::find($user_rol)){
			$roles['permissions']	= json_decode($roles['permissions']);
			return Response::json(array($roles,200));	
		} else {
			$error = array(trans('app.roles_retrieve_fail'));
			return Response::json([
	            'errors' => $error,
	            'code' => 400 // bad request
		   	]);
		}

		
	}

}
