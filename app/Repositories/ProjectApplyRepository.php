<?php

namespace App\Repositories;

use DB,Auth;
use Illuminate\Http\Request;
use Addons\Core\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;

use App\Models\ProjectApply;

class ProjectApplyRepository extends Repository {

	public function prePage()
	{
		return config('size.models.'.(new ProjectApply)->getTable(), config('size.common'));
	}

	public function find($id, array $columns = ['*'])
	{
		return ProjectApply::with([])->find($id, $columns);
	}

	public function findOrFail($id, array $columns = ['*'])
	{
		return ProjectApply::with([])->findOrFail($id, $columns);
	}

	public function store(array $data)
	{
		return DB::transaction(function() use ($data) {
			$model = ProjectApply::create($data);
			return $model;
		});
	}

	public function update(Model $model, array $data)
	{
		return DB::transaction(function() use ($model, $data){
		    try {
                if ($data['status'] == 1) {
                    $status = catalog_search('status.apply_status.pass', 'id');
                    //审核通过流程
                    $this->audit($model);
                } else {
                    $status = catalog_search('status.apply_status.reject', 'id');
                }
                unset($data['status']);
                $data = $data +  ['apply_status' => $status];
                $model->update($data);
                return $model;
            } catch (\Exception $e) {
		        return $e->getMessage();
            }
		});
	}

    public function audit(Model $model)
    {
        if (empty($model->dates)){
            throw new \Exception("申报日期不能为空");
        }
        $pmRepo = new ProjectMemberRepository();
        $pm = $pmRepo->checkHour($model->uid, $model->dates);

        if (!empty($pm)) {
            throw new \Exception("重复提交的时间【" . $pm->date . "】");
        }
        $pmRepo = new ProjectMemberRepository();
        $pmRepo->updateMember($model->uid, $model->pid, $model->dates, $model->member->cost);
        return true;
    }

	public function destroy(array $ids)
	{
		DB::transaction(function() use ($ids) {
			ProjectApply::destroy($ids);
		});
	}

	public function data(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new ProjectApply;
		$builder = $model->newQuery()->with(['project']);

		$total = $this->_getCount($request, $builder, false);
		$data = $this->_getData($request, $builder, $callback, $columns);
		$data['recordsTotal'] = $total; //不带 f q 条件的总数
		$data['recordsFiltered'] = $data['total']; //带 f q 条件的总数

		return $data;
	}

	public function export(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new ProjectApply;
		$builder = $model->newQuery()->with([]);
		$size = $request->input('size') ?: config('size.export', 1000);

		$data = $this->_getExport($request, $builder, $callback, $columns);

		return $data;
	}

    /**
     * 工时申报
     * @param array $data
     * @return bool
     */
    public function apply(array $data) {
        $pRepo = new ProjectRepository();
        $pmRepo = new ProjectMemberRepository();

        $user = Auth::user();
        $applyStatus = catalog_search('status.apply_status.applying', 'id');

        $pm = $pmRepo->checkHour($user->id, $data['dates']);

        if (!empty($pm)) {
            return ['status' => false, "msg" => "重复提交的时间【" . $pm->date . "】"];
        }

        $project = $pRepo->find($data['pid']);
        $message = $user->realname . ",申请在[" . $project->name  . "]项目中进行工时申报。";

        $this->store([
            'uid' => $user->id,
            'pid' => $data['pid'],
            'apply_status' => $applyStatus,
            'message' => $message,
            'dates' => $data['dates']
        ]);

        return ['status' => true];
    }

    public function style(&$pas)
    {
        $applying = catalog_search('status.apply_status.applying', 'id');
        $pass = catalog_search('status.apply_status.pass', 'id');
        $reject = catalog_search('status.apply_status.reject', 'id');
        foreach ($pas as &$pa) {
            $this->setStyle($pa, $applying, $pass, $reject);
        }
    }

    public function setStyle(&$pa, ...$status)
    {
        if ($pa['apply_status']['id'] == $status[0]) {
            $pa['style'] = "label label-warning";
        } elseif ($pa['apply_status']['id'] == $status[1]) {
            $pa['style'] = "label label-danger";
        } elseif ($pa['apply_status']['id'] == $status[2]) {
            $pa['style'] = "label label-default";
        }
    }
}
