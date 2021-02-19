<?php

namespace App\Repositories;

use DB,Auth;
use Illuminate\Http\Request;
use Addons\Core\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;

use App\Models\Project;

class ProjectRepository extends Repository {

	public function prePage()
	{
		return config('size.models.'.(new Project)->getTable(), config('size.common'));
	}

	public function find($id, array $columns = ['*'])
	{
		return Project::with(['members'])->find($id, $columns);
	}

	public function findOrFail($id, array $columns = ['*'])
	{
		return Project::with([])->findOrFail($id, $columns);
	}

	public function store(array $data)
	{
		return DB::transaction(function() use ($data) {
			$model = Project::create($data);
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

    public function forceUpdateMember(Model $model, array $data)
    {
        $pmRepo = new ProjectMemberRepository();
        return DB::transaction(function() use ($model, $data, $pmRepo){
            $normal = catalog_search('status.member_status.normal', 'id');

            if (empty($data['member_ids'])) return false;

            $pmRepo->updateStatus($data['member_ids']);

            foreach ($data['member_ids'] as $uid) {
                $pmRepo->createOrUpdate(['uid' => $uid, 'pid' => $model->id], ['member_status' =>
                    $normal]);
            }
            return true;
        });
    }

	public function destroy(array $ids)
	{
		DB::transaction(function() use ($ids) {
			Project::destroy($ids);
		});
	}

	public function data(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new Project;
		$builder = $model->newQuery()->with(['members', 'pm']);

		$total = $this->_getCount($request, $builder, false);
		$data = $this->_getData($request, $builder, $callback, $columns);
        $this->style($data['data']);


		$data['recordsTotal'] = $total; //不带 f q 条件的总数
		$data['recordsFiltered'] = $data['total']; //带 f q 条件的总数

		return $data;
	}

	public function export(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new Project;
		$builder = $model->newQuery()->with([]);
		$size = $request->input('size') ?: config('size.export', 1000);

		$data = $this->_getExport($request, $builder, $callback, $columns);

		return $data;
	}

	public function projects(int $pm_uid = 0)
    {
        //$progress = catalog_search('status.project_status.progress', 'id');
        $builder = Project::with(['members', 'pm']);
        !empty($pm_uid) && $builder->where('pm_uid', $pm_uid);
        return $builder->orderBy('id', 'desc')
            ->get();
    }

    public function style(&$items)
    {
        $normal = catalog_search('status.member_status.normal', 'id');
        $going = catalog_search('status.member_status.going', 'id');
        $deleted = catalog_search('status.member_status.normal', 'id');
        foreach ($items as &$item) {
            foreach ($item['members'] as &$member) {
                if ($member['pivot']['member_status'] == $normal) {
                    $member['style'] = "label label-success";
                } elseif ($member['pivot']['member_status'] == $going) {
                    $member['style'] = "label label-warning";
                } elseif ($member['pivot']['member_status'] == $deleted) {
                    $member['style'] = "label label-default";
                }
            }
        }
    }
}
