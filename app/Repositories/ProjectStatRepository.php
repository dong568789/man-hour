<?php

namespace App\Repositories;

use DB;
use Carbon\Carbon;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use Addons\Core\Contracts\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use App\Models\ProjectStat;

class ProjectStatRepository extends Repository {

    use ApiTrait;

	public function prePage()
	{
		return config('size.models.'.(new ProjectStat)->getTable(), config('size.common'));
	}

	public function find($id, array $columns = ['*'])
	{
		return ProjectStat::with([])->find($id, $columns);
	}

	public function findOrFail($id, array $columns = ['*'])
	{
		return ProjectStat::with([])->findOrFail($id, $columns);
	}

	public function store(array $data)
	{
		return DB::transaction(function() use ($data) {
			$model = ProjectStat::create($data);
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
			ProjectStat::destroy($ids);
		});
	}

	public function data(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new ProjectStat;
		$builder = $model->newQuery()->with(['project' => function($query){
		    $query->with(['pm']);
        }]);

        $newBuilder = clone $builder;
        $sum = $this->_getOther($request, $newBuilder->select([DB::raw('sum(day_cost) as sum_day_cost'), DB::raw('sum(cost) as sum_cost')]));

		$total = $this->_getCount($request, $builder, false);
		$data = $this->_getData($request, $builder, $callback, $columns);

        if (!empty($data['data'][0])) {
            $data['data'][0]['sum_day_cost'] = $sum->sum_day_cost;
            $data['data'][0]['sum_cost'] = $sum->sum_cost;
        }

		$data['recordsTotal'] = $total; //不带 f q 条件的总数
		$data['recordsFiltered'] = $data['total']; //带 f q 条件的总数

		return $data;
	}

	public function export(Request $request, callable $callback = null, array $columns = ['*'])
	{
		$model = new ProjectStat;
		$builder = $model->newQuery()->with(['project' => function($query){
            $query->with(['pm']);
        }, 'members' => function($query){
            $query->with(['member']);
        }]);
		$size = $request->input('size') ?: config('size.export', 1000);

		$data = $this->_getExport($request, $builder, $callback, $columns);

		return $data;
	}

	public function stat()
    {
        DB::transaction(function() {
            $pRepo = new ProjectRepository();
            $pmRepo = new ProjectMemberRepository();
            $pmsRepo = new ProjectMemberStatRepository();

            $projects = $pRepo->projects();

            foreach ($projects as $project) {
                $pmList = $pmRepo->countMemberHourByPid($project->id);
                $sumCost = $dayCost = 0;
                foreach ($pmList as $item) {
                    //更新成员统计
                    $memberCost = $item->hour * $item->member->cost;
                    $pmsRepo->createOrUpdate($project->id, $item->uid, $memberCost, $item->hour);
                    $sumCost += $memberCost;
                    $dayCost += $item->member->cost;
                }

                $avg = $sumCost > 0 ? $sumCost / $pmList->count() : 0;
                //更新项目统计
                $this->createOrUpdate($project->id, $sumCost, $avg, $dayCost);
            }
        });
    }

    /**
     * 更新每日成本
     * @param int $pid
     * @param int $cost 日总成本
     * @param Carbon $date
     * @return mixed
     */
    public function createOrUpdate(int $pid, float $cost, float $avg, float $dayCost)
    {
        return DB::transaction(function() use ($pid, $cost, $avg, $dayCost) {
            return ProjectStat::updateOrCreate(['pid' => $pid], ['cost' => $cost, 'avg_cost' => $avg, 'day_cost' => $dayCost]);
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
