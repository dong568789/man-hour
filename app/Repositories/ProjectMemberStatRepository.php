<?php

namespace App\Repositories;

use DB;
use Illuminate\Http\Request;
use Addons\Core\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;

use App\Models\ProjectMemberStat;

class ProjectMemberStatRepository extends Repository {

	public function prePage()
	{
		return config('size.models.'.(new ProjectMemberStat)->getTable(), config('size.common'));
	}

	public function find($id, array $columns = ['*'])
	{
		return ProjectMemberStat::with([])->find($id, $columns);
	}

	public function findOrFail($id, array $columns = ['*'])
	{
		return ProjectMemberStat::with([])->findOrFail($id, $columns);
	}

	public function store(array $data)
	{
		return DB::transaction(function() use ($data) {
			$model = ProjectMemberStat::create($data);
			return $model;
		});
	}

	public function update(Model $model, array $data)
	{
		return DB::transaction(function() use ($model, $data){
			$model->update($data);
			return $model;
		});
	}

	public function destroy(array $ids)
	{
		DB::transaction(function() use ($ids) {
			ProjectMemberStat::destroy($ids);
		});
	}

	public function data(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new ProjectMemberStat;
		$builder = $model->newQuery()->with(['member', 'project']);

		$total = $this->_getCount($request, $builder, false);
		$data = $this->_getData($request, $builder, $callback, $columns);
		$data['recordsTotal'] = $total; //不带 f q 条件的总数
		$data['recordsFiltered'] = $data['total']; //带 f q 条件的总数

		return $data;
	}

	public function export(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new ProjectMemberStat;
		$builder = $model->newQuery()->with(['member', 'project']);
		$size = $request->input('size') ?: config('size.export', 1000);

		$data = $this->_getExport($request, $builder, $callback, $columns);

		return $data;
	}

    /**
     * 更新成员每日成本
     * @param int $pid 项目
     * @param int $uid 成员
     * @param int $hour
     * @param int $cost 成本
     */
    public function createOrUpdate(int $pid, int $uid, int $cost, int $hour = 1)
    {
        $pms = ProjectMemberStat::where('pid', $pid)->where('uid', $uid)->first();
        if (empty($pms)){
            $this->store(['pid' => $pid, 'uid' => $uid, 'cost' => $cost, 'hour' => $hour]);
        } else {
            $pms->increment('cost', $cost);
            $pms->increment('hour');
        }
    }

}
