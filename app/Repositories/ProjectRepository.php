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
		return Project::with([])->find($id, $columns);
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

	public function destroy(array $ids)
	{
		DB::transaction(function() use ($ids) {
			Project::destroy($ids);
		});
	}

	public function data(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new Project;
		$builder = $model->newQuery()->with(['pm']);

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
        $builder = Project::with(['pm']);
        !empty($pm_uid) && $builder->where('pm_uid', $pm_uid);
        return $builder->orderBy('id', 'desc')
            ->get();
    }

    public function style(&$projects)
    {
        $progress = catalog_search('status.project_status.progress', 'id');
        foreach ($projects as &$project) {

            $this->setStyle($project, $progress);
        }
    }

    public function setStyle(&$project, $status)
    {
        if ($project['project_status']['id'] == $status) {
            $project['style'] = "label label-danger";
        } else {
            $project['style'] = "label label-default";
        }
    }
}
