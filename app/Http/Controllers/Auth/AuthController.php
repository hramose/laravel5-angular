<?php namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Validator;

class AuthController extends Controller {

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard  $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar  $registrar
	 * @return void
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;

		$this->middleware('guest', ['except' => 'getLogout']);
	}

	public function getLogin(){

		return view('auth.login');
	}

	public function postLogin(Request $request)
	{
		//VALIDATION
		$rules = array(
	        'username'          => 'required',
	        'password'         	=> 'required'
	    );

	    $messages = array(
	    	'username.required'		=> trans('auth.usernameRequired'),
	    	'password.required'		=> trans('auth.passwordRequired')
    	);

    	$validator = Validator::make($request->all(), $rules, $messages);

    	if ($validator->fails()) { 
    		return redirect('login')->withErrors($validator);
    	}

		$credentials = $request->only('username', 'password');

		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
			return redirect('/');
		}

		return redirect('login')
					->withInput($request->only('username', 'remember'))
					->withErrors([
						'username' => trans('auth.failedLogin'),
					]);
	}

	public function getLogout()
	{
		$this->auth->logout();

		return redirect('/');
	}

}
