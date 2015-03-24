<?php 
namespace App\Http\Controllers;
use JavaScript;
use Lang;
use Response;
use Auth;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		/*
		| Pass all language as array to the view
		| So we can use this in our javascript files
		| Take a look at HomeCtrl in app.js 
		*/
		JavaScript::put([
	        'lang' => Lang::get('app'),
	        'siteUrl' => config('app.url'),
	        'logedin' => Auth::user()->username
	    ]);

		return view('app');
	}

}
