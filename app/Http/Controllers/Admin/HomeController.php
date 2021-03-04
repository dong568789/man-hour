<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\ProjectMemberRepository;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

use App\Repositories\ProjectRepository;
use App\Repositories\ProjectApplyRepository;

class HomeController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
        $user = Auth::user();
        $role = $user->roles[0];
        $pmUid = 0;
        switch ($role->name){
            case 'super':
                $this->super($request);
                $tpl = "pm";
                break;
            case 'pm':
                $this->pm($request, $user);
                $tpl = "pm";
                $pmUid = Auth::user()->id;
                break;
            case 'project-member':
                $this->member($request, $user);
                $tpl = "member";
                break;
            case 'finance':
                $this->finance($request, $user);
                $tpl = "finance";
                break;
            default :
                $tpl = "";
        }

	    $repo = new ProjectRepository();

        $projects = $repo->projects($pmUid)->toArray();

        $repo->style($projects);

        $this->_projects = $projects;
        $this->_tpl = $tpl;
		return $this->view('admin.dashboard');
	}

    private function super(Request $request)
    {
        $applying = catalog_search('status.apply_status.applying', 'id');
        $paRepo = new ProjectApplyRepository();
        $request->offsetSet('f', ['apply_status' => $applying]);
        $data = $paRepo->data($request);

        $this->_messages = $data['data'];
    }

    private function pm(Request $request, User $user)
    {
        if ($user->project->isEmpty()){
            return [];
        }

        $applying = catalog_search('status.apply_status.applying', 'id');
        $paRepo = new ProjectApplyRepository();
        $pids = $user->project->modelKeys();

        $request->offsetSet('f', ['pid' => ['in' => $pids], 'apply_status' => $applying]);
        $data = $paRepo->data($request);

        $this->_messages = $data['data'];
    }

    private function member(Request $request, User $user)
    {
        $paRepo = new ProjectApplyRepository();

        $request->offsetSet('f', ['uid' => $user->id]);
        $request->offsetSet('size', 20);
        $data = $paRepo->data($request);
        $this->_myStat = (new ProjectMemberRepository)->countMemberHourByUid($user->id);
        $this->_messages = $data['data'];
    }

    private function finance(Request $request, User $user)
    {
        $this->_messages = [];
    }
}
