<?php

namespace App\Http\Controllers\Admin;

use App\Tools\Helper;
use Auth;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\ProjectMemberStatRepository;

class ProjectMemberStatController extends Controller {

    use ApiTrait;

    public $permissions = ['project-member-stat'];

    protected $repo;

    protected $keys = [];

    public function __construct(ProjectMemberStatRepository $repo)
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

        $user = Auth::user();
        $tpl = Helper::getRoleTpl($user);

        return $this->view('admin.projectMemberStat.list-' . $tpl);
    }

    public function data(Request $request)
    {
        $this->parseRequest($request);
        $data = $this->repo->data($request);
        return $this->api($data);
    }

    public function export(Request $request)
    {
        $this->parseRequest($request);
        $data = $this->repo->export($request);

        $user = Auth::user();

        if (Helper::isPm($user)) {
            $exportData[] = ['项目名称', '成员', '总工时', '创建时间'];
        } elseif (Helper::isMember($user)) {
            $exportData[] = ['项目名称', '总工时', '创建时间'];
        } elseif (Helper::isFinance($user) || Helper::isSuper($user)) {
            $exportData[] = ['项目名称', '成员', '每日成本', '总工时', '总成本', '创建时间'];
        }

        foreach ($data as $key=>$item) {
            if ($key == 0 || $key == 1) continue;
            if (Helper::isPm($user)) {
                $exportData[] = [
                    '项目名称' => $item['project']['name'] ?? '-',
                    '成员' => $item['member']['realname'] ?? '-',
                    '总工时' => $item['hour'],
                    '创建时间' => $item['created_at']
                ];
            } elseif (Helper::isMember($user)) {
                $exportData[] = [
                    '项目名称' => $item['project']['name'] ?? '-',
                    '总工时' => $item['hour'],
                    '创建时间' => $item['created_at']
                ];
            } elseif (Helper::isFinance($user) || Helper::isSuper($user)) {
                $exportData[] = [
                    '项目名称' => $item['project']['name'] ?? '-',
                    '成员' => $item['member']['realname'] ?? '-',
                    '每日成本' => round($item['member']['cost'], 2),
                    '总工时' => $item['hour'],
                    '总成本' => round($item['cost'], 2),
                    '创建时间' => $item['created_at']
                ];
            }
        }
        return $this->office($exportData);
    }

    public function show(Request $request, $id)
    {
        $projectMemberStat = $this->repo->find($id);
        if (empty($projectMemberStat))
            return $this->failure_notexists();

        $this->_data = $projectMemberStat;

        return !$request->offsetExists('of') ? $this->view('admin.projectMemberStat.show') : $this->api($projectMemberStat->toArray());
    }

    public function create()
    {
        $this->_data = [];
        $this->_validates = $this->censorScripts('projectMemberStat.store', $this->keys);
        return $this->view('admin.projectMemberStat.create');
    }

    public function store(Request $request)
    {
        $data = $this->censor($request, 'projectMemberStat.store', $this->keys);

        $this->repo->store($data);

        return $this->success('', url('admin/project-member-stat'));
    }

    public function edit($id)
    {
        $projectMemberStat = $this->repo->find($id);
        if (empty($projectMemberStat))
            return $this->failure_notexists();

        $this->_validates = $this->censorScripts('projectMemberStat.store', $this->keys);
        $this->_data = $projectMemberStat;
        return $this->view('admin.projectMemberStat.edit');
    }

    public function update(Request $request, $id)
    {
        $projectMemberStat = $this->repo->find($id);
        if (empty($projectMemberStat))
            return $this->failure_notexists();

        $data = $this->censor($request, 'projectMemberStat.store', $this->keys, $projectMemberStat);
        $this->repo->update($projectMemberStat, $data);

        return $this->success();
    }

    public function destroy(Request $request, $id)
    {
        empty($id) && !empty($request->input('id')) && $id = $request->input('id');
        $ids = array_wrap($id);

        $this->repo->destroy($ids);
        return $this->success(null, true, ['id' => $ids]);
    }

    private function parseRequest(Request $request)
    {
        $f = $request->input('f', []);

        $user = Auth::user();

        $roleName = Helper::roleName($user);
        $params = [];
        switch ($roleName) {
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
