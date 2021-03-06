<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\Message;
use App\Role;
use App\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\UserRepository;

class MemberController extends Controller
{
	public $permissions = ['member', 'cost' => 'finance.update'];

	protected $keys = ['username', 'password', 'nickname', 'realname', 'gender', 'email', 'phone', 'idcard', 'avatar_aid', 'role_ids', 'post'];
	protected $usernameKey = 'username';
	protected $passwordKey = 'password';
	protected $userRepo;

	public function __construct(UserRepository $userRepo)
	{
		$this->userRepo = $userRepo;
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        //dispatch((new Message(User::find(18))));

        $size = $request->input('size') ?: $this->userRepo->prePage();
		//view's variant
		$this->_size = $size;
		$this->_filters = $this->userRepo->_getFilters($request);
		$this->_queries = $this->userRepo->_getQueries($request);


        if($this->_queries['ofRole'] ==  \App\Role::searchRole('administrator.project-member', 'id')) {
            $tpl = "member";
        } elseif ($this->_queries['ofRole'] ==  \App\Role::searchRole('administrator.pm', 'id')) {
            $tpl = "pm";
        } else {
            $tpl = "list";
        }
		return $this->view('admin.member.' . $tpl);
	}

	public function data(Request $request)
	{
		$data = $this->userRepo->data($request);
		return $this->api($data);
	}

	public function export(Request $request)
	{
		$data = $this->userRepo->export($request);
		return $this->office($data);
	}

	public function show(Request $request, $id)
	{
		$user = $this->userRepo->find($id);
		if (empty($user))
			return $this->failure_notexists();

		$this->_data = $user;
		return !$request->offsetExists('of') ? $this->view('admin.member.show') : $this->api($user->toArray());
	}

	public function create(Request $request)
	{
		$this->_data = [];
        $this->_role =  $request->input('role');
		$this->_validates = $this->censorScripts('member.store', $this->keys);
		return $this->view('admin.member.create');
	}

	public function store(Request $request)
	{
		$data = $this->censor($request, 'member.store', $this->keys);

		$user = $this->userRepo->store($data);
		//注册通知
		//dispatch(new Message);
        return $this->success('', url('admin/member?q[ofRole]=' . current($data['role_ids'])));
	}

	public function edit(Request $request, $id)
	{
		$user = $this->userRepo->find($id);
		if (empty($user))
			return $this->failure_notexists();

		$keys = array_diff($this->keys, [$this->usernameKey, $this->passwordKey]); //except password

		$this->_validates = $this->censorScripts('member.store', $keys);
		$this->_data = $user;
        $this->_role = $request->input('role');
        return $this->view('admin.member.edit');
	}

	public function update(Request $request, $id)
	{
		$user = $this->userRepo->find($id);
		if (empty($user))
			return $this->failure_notexists();

		//modify the password
		if (!empty($request->input($this->passwordKey)))
		{
			$data = $this->censor($request, 'member.store', [$this->passwordKey]);
			$this->userRepo->updatePassword($user, $data['password']);
		}
		$keys = array_diff($this->keys, [$this->usernameKey, $this->passwordKey]); //except password, username
		$data = $this->censor($request, 'member.store', $keys, $user);

		$user = $this->userRepo->update($user, $data);
		return $this->success();
	}

	public function cost(Request $request, $id)
    {
        $user = $this->userRepo->find($id);
        if (empty($user))
            return $this->failure_notexists();

        $keys = ['cost'];
        $data = $this->censor($request, 'member.store', $keys, $user);

        $user = $this->userRepo->update($user, $data);
        return $this->success();
    }

	public function destroy(Request $request, $id)
	{
		empty($id) && !empty($request->input('id')) && $id = $request->input('id');
		$ids = Arr::wrap($id);

		$this->userRepo->destroy($ids);
		return $this->success(null, true, ['id' => $ids]);
	}
}
