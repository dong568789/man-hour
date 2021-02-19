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
		});
	}

    public function audit(Model $model)
    {
        $pmRepo = new ProjectMemberRepository();
        $projectMember = $pmRepo->normalProjectByUid($model->uid);
        if (empty($projectMember) || $projectMember->pid != $model->to_pid) {
            return false;
        }

        $pRepo = new ProjectRepository();
        $project = $pRepo->find($model->pid);
        $pRepo->forceUpdateMember($project, ['member_ids' => [$model->uid]]);
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
     * 申请成员
     * @param array $data
     * @return bool
     */
    public function apply(array $data) {
        $pmRepo = new ProjectMemberRepository();
        $pRepo = new ProjectRepository();

        $user = Auth::user();
        $applyStatus = catalog_search('status.apply_status.applying', 'id');
        $normal = catalog_search('status.member_status.normal', 'id');
        foreach ($data['member_ids'] as $uid) {
            $projectMember = $pmRepo->normalProjectByUid($uid);

            $project = $pRepo->find($data['pid']);
            //用户无项目状态，直接转入
            if (empty($projectMember)){
                $pRepo->forceUpdateMember($project, ['member_ids' => [$uid]]);
                return true;
            }

            if ($data['pid'] == $projectMember->pid && $projectMember->member_status->id == $normal) continue;

            $message = $user->realname . ",申请" .
                $projectMember->member->realname . "[" . $projectMember->project->name  . "],加入" . $project->name;
            $this->store([
                'apply_uid' => $user->id,
                'pid' => $data['pid'],
                'uid' => $uid,
                'to_pid' =>$projectMember->pid,
                'apply_status' => $applyStatus,
                'message' => $message
            ]);
        }

        return true;
    }
}
