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
            ProjectMember::where('id', $ids)->update(['member_status'=> catalog_search('status.member_status.deleted', 'id')]);
		});
	}

	public function data(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new ProjectMember;
		$builder = $model->newQuery()->with([]);

		$total = $this->_getCount($request, $builder, false);
		$data = $this->_getData($request, $builder, $callback, $columns);
		$data['recordsTotal'] = $total; //不带 f q 条件的总数
		$data['recordsFiltered'] = $data['total']; //带 f q 条件的总数

		return $data;
	}

	public function export(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new ProjectMember;
		$builder = $model->newQuery()->with([]);
		$size = $request->input('size') ?: config('size.export', 1000);

		$data = $this->_getExport($request, $builder, $callback, $columns);

		return $data;
	}

    /**
     * 获取用户正常 的项目
     * @param int $uid
     * @return mixed
     */
	public function normalProjectByUid(int $uid)
    {
	    $normal = catalog_search('status.member_status.normal', 'id');
        return ProjectMember::with(['member', 'project'])->where('uid', $uid)
            ->where('member_status', $normal)
            ->first();
    }

    public function updateStatus(array $user_ids)
    {
        $going = catalog_search('status.member_status.going', 'id');
        $normal = catalog_search('status.member_status.normal', 'id');
        return ProjectMember::whereIn('uid', $user_ids)->where('member_status', $normal)->update(['member_status' =>
            $going]);
    }

    public function updateMember(int $pid, array $member_ids)
    {
        $uids = ProjectMember::where('pid', $pid)->get()->pluck('uid')->toArray();
        $diff = array_diff($uids, $member_ids);
        $this->deleteMember($pid, $diff);

        if (empty($member_ids)) return false;
        $paRepo = new ProjectApplyRepository();
        $paRepo->apply(['pid' => $pid, 'member_ids' => $member_ids]);
        return true;
    }

    public function deleteMember(int $pid, array $member_ids)
    {
        return ProjectMember::whereIn('uid', $member_ids)->where('pid', $pid)->delete();
    }

    public function createOrUpdate($data, $opt)
    {
        return DB::transaction(function() use ($data, $opt) {
            return ProjectMember::updateOrCreate($data,
                $opt);
        });
    }

    public function incrementHour(int $pid)
    {
        $normal = catalog_search('status.member_status.normal', 'id');
        return ProjectMember::where('pid', $pid)
            ->where('member_status', $normal)
            ->increment('hour');
    }
}
