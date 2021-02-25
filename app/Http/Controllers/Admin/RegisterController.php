<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\UserRepository;

class RegisterController extends Controller
{
	protected $repo;

	public function __construct(UserRepository $repo)
	{
		$this->middleware('auth')->except(['index', 'store']);

		$this->repo = $repo;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        Auth::guard()->logout();
        $keys = ['username', 'password', 'avatar_aid', 'accept_license', 'post', 'realname'];
        $this->_validates = $this->censorScripts('member.store', $keys);
        return $this->view('admin/register');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  Request  $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$keys = ['username', 'password', 'avatar_aid', 'accept_license', 'post', 'realname'];
		$data = $this->censor($request, 'member.store', $keys);

		unset($data['accept_license']);
		$user = $this->repo->store($data, 'project-member');
		return $this->success(NULL, 'admin/auth/login', $user->toArray());
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  Request  $request
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		//
	}

}
