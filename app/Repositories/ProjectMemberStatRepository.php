<?php

namespace App\Repositories;

use DB;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use Addons\Core\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\Models\ProjectMemberStat;

class ProjectMemberStatRepository extends Repository {

    use ApiTrait;

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

        $newBuilder = clone $builder;
        $sum = $this->_getOther($request, $newBuilder->select([DB::raw('sum(hour) as sum_hour'), DB::raw('sum(cost) as sum_cost')]));

		$total = $this->_getCount($request, $builder, false);
		$data = $this->_getData($request, $builder, $callback, $columns);

        if (!empty($data['data'][0])) {
            $data['data'][0]['sum_hour'] = $sum->sum_hour;
            $data['data'][0]['sum_cost'] = $sum->sum_cost;
        }

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
    public function createOrUpdate(int $pid, int $uid, float $cost, int $hour = 1)
    {
        return DB::transaction(function() use ($pid, $uid, $cost, $hour) {
            return ProjectMemberStat::updateOrCreate(['pid' => $pid, 'uid' => $uid], ['cost' => $cost, 'hour' => $hour]);
        });
    }

    public function _getOther(Request $request, Builder $builder)
    {
        $tables_columns = $this->_getColumns($builder);
        $this->_doFilters($request, $builder, $tables_columns);
        $this->_doQueries($request, $builder);

        $query = $builder->getQuery();

        if (!empty($query->groups)) //group by
        {

            return $query->getCountForPagination($query->groups);
            // or
            $query->columns = $query->groups;
            return DB::table( DB::raw("({$builder->toSql()}) as sub") )
                ->mergeBindings($builder->getQuery()) // you need to get underlying Query Builder
                ->first();
        } else
            //DB::connection()->enableQueryLog(); // 开启查询日志
            return $builder->first();;
        //$queries = DB::getQueryLog(); // 获取查询日志
        //print_r($builder->toSql());exit;
    }
}
