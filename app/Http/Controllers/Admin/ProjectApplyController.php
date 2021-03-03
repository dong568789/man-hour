<?php

namespace App\Http\Controllers\Admin;

use App\Tools\Helper;
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
        $tpl = Helper::getRoleTpl($user);

        //view's variant
        $this->_size = $size;
        $this->_filters = $this->repo->_getFilters($request);
        $this->_queries = $this->repo->_getQueries($request);

        return $this->view('admin.projectApply.list-' . $tpl);
    }

    public function data(Request $request)
    {
        $this->parseRequest($request);
        $data = $this->repo->data($request);

        return $this->api($data);
    }

    public function store(Request $request)
    {
        $keys = ['pid', 'dates'];
        $data = $this->censor($request, 'projectApply.apply', $keys);

        $data['dates'] = json_decode($data['dates'], true);

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
        $user = Auth::user();
        $tpl = Helper::getRoleTpl($user);
        $this->_data = $paArr;
        $this->_type = "audit";
        $this->_allMonth = json_encode(Helper::uniqueMonth($paArr['dates']));
        return !$request->offsetExists('of') ? $this->view('admin.projectApply.show-' . $tpl) : $this->api($paArr);
    }

    public function update(Request $request, $id)
    {
        $projectApply = $this->repo->find($id);
        if (empty($projectApply))
            return $this->failure_notexists();

        $keys = ['status', 'mark'];
        $data = $this->censor($request, 'projectApply.store', $keys, $projectApply);

        $data['operator_uid'] = Auth::user()->id;
        $return = $this->repo->update($projectApply, $data);
        if (is_string($return)) {
            return $this->error($return);
        }
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
