<?php

namespace App\Repositories;

use DB;
use Illuminate\Http\Request;
use Addons\Core\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;

use App\Models\ProjectMember;

class ProjectMemberRepository extends Repository {

	public function prePage()
	{
		return config('size.models.'.(new ProjectMember)->getTable(), config('size.common'));
	}

	public function find($id, array $columns = ['*'])
	{
		return ProjectMember::with([])->find($id, $columns);
	}

	public function findOrFail($id, array $columns = ['*'])
	{
		return ProjectMember::with([])->findOrFail($id, $columns);
	}

	public function store(array $data)
	{
		return DB::transaction(function() use ($data) {
			$model = ProjectMember::create($data);
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
            ProjectMember::destroy($ids);
		});
	}

	public function data(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new ProjectMember;
		$builder = $model->newQuery()->with(['project', 'member']);

		$total = $this->_getCount($request, $builder, false);
		$data = $this->_getData($request, $builder, $callback, $columns);
		$data['recordsTotal'] = $total; //不带 f q 条件的总数
		$data['recordsFiltered'] = $data['total']; //带 f q 条件的总数

		return $data;
	}

	public function export(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new ProjectMember;
		$builder = $model->newQuery()->with(['project', 'member']);
		$size = $request->input('size') ?: config('size.export', 1000);

		$data = $this->_getExport($request, $builder, $callback, $columns);

		return $data;
	}

    /**
     * 项目各成员 总工时
     * @param int $pid
     * @return mixed
     */
	public function countMemberHourByPid(int $pid)
    {
        return ProjectMember::with(['member'])->where('pid', $pid)->groupBy('uid')->select([DB::raw('count(id) as hour'), 'uid'])->get();
    }

    /**
     * 获取用户正常 的项目
     * @param int $uid
     * @return mixed
     */
	public function checkHour(int $uid, array $dates)
    {
        return ProjectMember::where('uid', $uid)
            ->whereIn('date', $dates)
            ->first();
    }

    /**
     * 写入工时
     * @param int $uid
     * @param int $pid
     * @param array $dates
     * @param float $cost
     * @return bool
     */
    public function updateMember(int $uid, int $pid, array $dates, float $cost = 0)
    {
        $dates = array_unique($dates);
        foreach ($dates as $date) {
            $this->store([
                'uid' => $uid,
                'pid' => $pid,
                'date' => $date,
                'cost' => $cost//当前成本
            ]);
        }
        return true;
    }


    public function createOrUpdate($data, $opt)
    {
        return DB::transaction(function() use ($data, $opt) {
            return ProjectMember::updateOrCreate($data,
                $opt);
        });
    }

    public function myProject(int $uid)
    {
        return ProjectMember::where('uid', $uid)->groupBy('pid')->select(['pid'])->get()->pluck('pid');
    }
}
