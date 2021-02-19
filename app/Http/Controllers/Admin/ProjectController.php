<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

use App\Repositories\ProjectRepository;
use App\Repositories\ProjectMemberRepository;

class ProjectController extends Controller {

    use ApiTrait;

    public $permissions = ['store,update,destroy' => 'project.create'];

    protected $repo;

    protected $keys = ['detail', 'name', 'cover_id', 'project_status', 'end_at', 'pm_uid', 'member_ids'];

    public function __construct(ProjectRepository $repo)
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
        //view's variant
        $this->_size = $size;
        $this->_filters = $this->repo->_getFilters($request);
        $this->_queries = $this->repo->_getQueries($request);

        return $this->view('admin.project.list');
    }

    public function data(Request $request)
    {
        $this->parseRequest($request);
        $data = $this->repo->data($request);
        return $this->api($data);
    }

    public function export(Request $request)
    {
        $request = $this->parseRequest($request);
        $data = $this->repo->export($request);

        return $this->office($data);
    }

    public function show(Request $request, $id)
    {
        $project = $this->repo->find($id);
        if (empty($project))
            return $this->failure_notexists();
        $projects = [$project];
        $this->repo->style($projects);
        $this->_data = $projects[0];

        return !$request->offsetExists('of') ? $this->view('admin.project.show') : $this->api($project->toArray());
    }

    public function create(Request $request)
    {
        $this->_data = [];
        $this->_validates = $this->censorScripts('project.store', $this->keys);
        return $this->view('admin.project.create');
    }

    public function store(Request $request)
    {
        $data = $this->censor($request, 'project.store', $this->keys);

        $extraFiled = ['member_ids'];
        $extra = array_only($data, $extraFiled);
        $data = array_except($data, $extraFiled);
        $user = Auth::user();
        $this->isPm($user) && $data['pm_uid'] = $user->id;
        $project = $this->repo->store($data);
        !empty($extra['member_ids']) && (new ProjectMemberRepository)->updateMember($project->id, $extra['member_ids']);

        return $this->success('', url('admin/project'));
    }

    public function edit($id)
    {
        $project = $this->repo->find($id);
        if (empty($project))
            return $this->failure_notexists();

        $this->_validates = $this->censorScripts('project.store', $this->keys);
        $this->_data = $project;
        return $this->view('admin.project.edit');
    }

    public function update(Request $request, $id)
    {
        $project = $this->repo->find($id);
        if (empty($project))
            return $this->failure_notexists();

        $data = $this->censor($request, 'project.store', $this->keys, $project);
        $extraFiled = ['member_ids'];
        $extra = array_only($data, $extraFiled);
        $data = array_except($data, $extraFiled);

        $this->repo->update($project, $data);
        $pmRepo = new ProjectMemberRepository();

        $extra['member_ids'] = $extra['member_ids'] ?? [];

        //项目完结，更新成员状态
        if ($data['project_status'] == catalog_search('status.project_status.ending', 'id')) {
            $pmRepo->updateMember($project->id, []);
        }else{
            $pmRepo->updateMember($project->id, $extra['member_ids']);
        }
        return $this->success();
    }

    public function destroy(Request $request, $id)
    {
        empty($id) && !empty($request->input('id')) && $id = $request->input('id');
        $ids = array_wrap($id);

        $this->repo->destroy($ids);
        return $this->success(null, true, ['id' => $ids]);
    }

    private function isPm(User $user)
    {
        return $this->roleName($user) == 'pm';
    }

    private function parseRequest(Request $request)
    {
        $f = $request->input('f', []);
        $user = Auth::user();
        $this->isPm($user) && $f['pm_uid'] = $user->id;

        $request->offsetSet('f', $f);
    }

    private function roleName(User $user)
    {
        $role = $user->roles->first();
        return array_get($role, 'name');
    }
}
