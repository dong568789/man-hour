<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\ProjectMemberLogRepository;

class ProjectMemberLogController extends Controller {

    use ApiTrait;

    public $permissions = ['store,update,destroy' => 'finance.update'];

    protected $repo;

    protected $keys = [];

    public function __construct(ProjectMemberLogRepository $repo)
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


        return $this->view('admin.projectMemberLog.list');
    }

    public function data(Request $request)
    {
        $data = $this->repo->data($request);
        return $this->api($data);
    }


    public function export(Request $request)
    {
        $data = $this->repo->export($request);
        $exportData[] = ['项目名称', '成员', '每日成本', '总工时', '总成本', '创建时间'];
        foreach ($data as $key=>$item) {
            if ($key == 0 || $key == 1) continue;
            $exportData[] = [
                '项目名称' => $item['project']['name'] ?? '-',
                '成员' => $item['member']['realname'] ?? '-',
                '每日成本' => round($item['member']['cost'], 2),
                '总工时' => $item['hour'],
                '总成本' => round($item['cost'], 2),
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

        return !$request->offsetExists('of') ? $this->view('admin.projectMemberLog.show') : $this->api($projectMemberLog->toArray());
    }

    public function destroy(Request $request, $id)
    {
        empty($id) && !empty($request->input('id')) && $id = $request->input('id');
        $ids = array_wrap($id);

        $this->repo->destroy($ids);
        return $this->success(null, true, ['id' => $ids]);
    }
}
