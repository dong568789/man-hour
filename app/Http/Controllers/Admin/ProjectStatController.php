<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\ProjectRepository;
use App\Repositories\ProjectStatRepository;
use App\Repositories\ProjectMemberRepository;

class ProjectStatController extends Controller {

    use ApiTrait;

    public $permissions = ['store,update,destroy' => 'finance.update'];

    protected $repo;

    protected $keys = [];

    public function __construct(ProjectStatRepository $repo)
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


        return $this->view('admin.projectStat.list');
    }

    public function data(Request $request)
    {
        $pRepo = new ProjectRepository();
        $progress = catalog_search('status.project_status.progress', 'id');
        $data = (new ProjectMemberRepository)->stat($request, function ($items) use($pRepo, $progress) {
            foreach ($items as $item) {
                $pRepo->setStyle($item['project'], $progress);
            }
        });

        return $this->api($data);
    }

    public function export(Request $request)
    {
        $data =  (new ProjectMemberRepository)->stat($request);
        $exportData[] = ['项目名称', 'PM', '总成本', '每日成本', '状态'];
        foreach ($data['data'] as $key=>$item) {
            $exportData[] = [
                '项目名称' => $item['project']['name'] ?? '-',
                'PM' => $item['project']['pm']['realname'] ?? '-',
                '总成本' => round($item['cost'], 2),
                '每日成本' => round($item['day_cost'], 2),
                '状态' => $item['project']['project_status']['title'] ?? '',
            ];
        }

        return $this->office($exportData);
    }

    public function show(Request $request, $id)
    {
        $projectStat = $this->repo->find($id);
        if (empty($projectStat))
            return $this->failure_notexists();

        $this->_data = $projectStat;

        return !$request->offsetExists('of') ? $this->view('admin.projectStat.show') : $this->api($projectStat->toArray());
    }

    public function create()
    {
        $this->_data = [];
        $this->_validates = $this->censorScripts('projectStat.store', $this->keys);
        return $this->view('admin.projectStat.create');
    }

    public function store(Request $request)
    {
        $data = $this->censor($request, 'projectStat.store', $this->keys);

        $projectStat = $this->repo->store($data);

        return $this->success('', url('admin/project-stat'));
    }

    public function edit($id)
    {
        $projectStat = $this->repo->find($id);
        if (empty($projectStat))
            return $this->failure_notexists();

        $this->_validates = $this->censorScripts('projectStat.store', $this->keys);
        $this->_data = $projectStat;
        return $this->view('admin.projectStat.edit');
    }

    public function update(Request $request, $id)
    {
        $projectStat = $this->repo->find($id);
        if (empty($projectStat))
            return $this->failure_notexists();

        $data = $this->censor($request, 'projectStat.store', $this->keys, $projectStat);
        $this->repo->update($projectStat, $data);

        return $this->success();
    }

    public function destroy(Request $request, $id)
    {
        empty($id) && !empty($request->input('id')) && $id = $request->input('id');
        $ids = array_wrap($id);

        $this->repo->destroy($ids);
        return $this->success(null, true, ['id' => $ids]);
    }
}
