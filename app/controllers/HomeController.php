<?php

use HireMe\Repositories\CandidateRepo;

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	protected $candidateRepo;

	public function __construct(CandidateRepo $candidateRepo){
		$this->candidateRepo = $candidateRepo;
	}

	public function index()
	{
		$lastest_candidates = $this->candidateRepo->findLastest();
		return View::make('home',compact('lastest_candidates'));
	}

}
