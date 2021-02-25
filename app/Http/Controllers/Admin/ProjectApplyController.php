<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\ProjectApplyRepository;

class ProjectApplyController extends Controller {

    use ApiTrait;

    public $permissions = ['project-apply'];

    protected $keys = [];
    protected $repo;

    public function __construct(ProjectApplyRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $size = $request->input('size') ?: $this->repo->prePage();

        $user = Auth::user();
        $role = $user->roles()->first();
        switch ($role->name) {
            case 'pm':
                $tpl = "list";
                break;
            case 'super':
                $tpl = "list";
                break;
            case 'finance':
                $tpl = "member";
                break;
            case 'project-member':
                $tpl = "member";
                break;
            default:
        }

        //view's variant
        $this->_size = $size;
        $this->_filters = $this->repo->_getFilters($request);
        $this->_queries = $this->repo->_getQueries($request);

        return $this->view('admin.projectApply.' . $tpl);
    }

    public function data(Request $request)
    {
        $this->parseRequest($request);
        $data = $this->repo->data($request);

        $this->repo->style($data['data']);
        return $this->api($data);
    }

    public function store(Request $request)
    {
        $keys = ['pid', 'dates'];
        $data = $this->censor($request, 'projectApply.apply', $keys);

        $result = $this->repo->apply($data);

        if (!$result['status']) {
            return $this->error($result['msg']);
        }
        return $this->success();
    }

    public function show(Request $request, $id)
    {
        $pa = $this->repo->find($id);
        if (empty($pa))
            return $this->failure_notexists();

        $paArr = $pa->toArray();

        $this->repo->setStyle($paArr, catalog_search('status.apply_status.applying', 'id'), catalog_search('status.apply_status.pass', 'id'), catalog_search('status.apply_status.reject', 'id'));
        $this->_data = $paArr;
        return !$request->offsetExists('of') ? $this->view('admin.projectApply.show') : $this->api($paArr);
    }

    public function update(Request $request, $id)
    {
        $projectApply = $this->repo->find($id);
        if (empty($projectApply))
            return $this->failure_notexists();

        $keys = ['status', 'mark'];
        $data = $this->censor($request, 'projectApply.store', $keys, $projectApply);

        $data['operator_uid'] = Auth::user()->id;
        $project = $this->repo->update($projectApply, $data);

        return $this->success();
    }

    private function parseRequest(Request $request)
    {
        $f = $request->input('f', []);

        $user = Auth::user();
        $role = $user->roles()->first();
        $params = [];
        switch ($role->name) {
            case 'pm':
                $params = ['pid' => ['in' => $user->project->modelKeys()]];
                break;
            case 'project-member':
                $params = ['uid' => $user->id];
                break;
            default:
        }
        $f = array_merge($params, $f);
        $request->offsetSet('f', $f);
    }

}
