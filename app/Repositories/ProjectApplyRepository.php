<?php

namespace App\Repositories;

use App\Jobs\StatHour;
use DB,Auth;
use Carbon\Carbon;
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

                    //审核通过，更新统计数据
                    //dispatch((new StatHour($model->pid)));
                } elseif ($data['status'] == 2) {
                    $status = catalog_search('status.apply_status.recall', 'id');
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
		$builder = $model->newQuery()->with(['project', 'member']);

		$total = $this->_getCount($request, $builder, false);
		$data = $this->_getData($request, $builder, $callback, $columns);

        $this->style($data['data']);

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

        $max = max($data['dates']);

        if ($max > date("Y-m-d")) {
            return ['status' => false, "msg" => "申报时间不能是未来时间【" . $max . "】"];
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
        $recall = catalog_search('status.apply_status.recall', 'id');
        foreach ($pas as &$pa) {
            $this->setStyle($pa, $applying, $pass, $reject, $recall);
        }
    }

    public function setStyle(&$pa, ...$status)
    {
        if ($pa['apply_status']['id'] == $status[0]) {
            $pa['style'] = "label label-default";
        } elseif ($pa['apply_status']['id'] == $status[1]) {
            $pa['style'] = "label label-success";
        } elseif ($pa['apply_status']['id'] == $status[2]) {
            $pa['style'] = "label label-warning";
        } elseif ($pa['apply_status']['id'] == $status[3]) {
            $pa['style'] = "label label-danger";
        }
    }

    /**
     * 最近两月，我的申报
     * @param int $uid
     * @param int $pid
     * @return mixed
     */
    public function myApply(int $uid, int $pid)
    {
        $at = Carbon::now();
        $start = $at->copy()->subMonth(1);
        $status = catalog_search('status.apply_status.applying', 'id');
        $pas = ProjectApply::where('pid', $pid)
            ->where('uid', $uid)
            ->where('apply_status', $status)
            ->whereBetween('created_at', [$start, $at])
            ->get()->pluck('dates');

        return $pas->collapse()->unique()->values()->toArray();
    }
}
