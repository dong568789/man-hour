<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\ProjectMemberRepository;

class ProjectMemberController extends Controller {

    use ApiTrait;

    public $permissions = ['project-member'];

    protected $keys = [];
    protected $repo;

    public function __construct(ProjectMemberRepository $repo)
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


        return $this->view('admin.projectMember.list');
    }

    public function data(Request $request)
    {
        $this->parseRequest($request);
        $data = $this->repo->data($request);
        return $this->api($data);
    }


    public function export(Request $request)
    {
        $data = $this->repo->export($request);
        $exportData[] = ['时间', '项目', '成员', '创建时间'];
        foreach ($data as $key=>$item) {
            if ($key == 0 || $key == 1) continue;
            $exportData[] = [
                '时间' => $item['date'],
                '项目' => $item['project']['name'] ?? '-',
                '成员' => $item['member']['realname'],
                '创建时间' => $item['created_at']
            ];
        }
        return $this->office($exportData);
    }

    public function show(Request $request, $id)
    {
        $projectMemberLog = $this->repo->find($id);
        if (empty($projectMemberLog))
            return $this->failure_notexists();

        $this->_data = $projectMemberLog;

        return !$request->offsetExists('of') ? $this->view('admin.projectMember.show') : $this->api($projectMemberLog->toArray());
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
