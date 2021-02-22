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
        }, 'members' => function($query){
            $query->with(['member']);
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

	public function stat(Carbon $date = null)
    {
        DB::transaction(function() use ($date) {
            $pRepo = new ProjectRepository();
            $pmlRepo = new ProjectMemberLogRepository();
            $pmsRepo = new ProjectMemberStatRepository();

            $projects = $pRepo->projects();
            $normal = catalog_search('status.member_status.normal', 'id');

            foreach ($projects as $project) {
                $pid = $project->id;
                $members = $project->members()->where('member_status', $normal)->get();
                if ($members->isEmpty()){
                    $this->createOrUpdate($pid, 0);
                    continue;
                }
                $sumCost = $count = 0;
                foreach ($members as $member) {
                    $sumCost += $member->cost;
                    $count++;

                    //更新成员统计
                    $pmsRepo->createOrUpdate($pid, $member->id, $member->cost);

                    //添加每日记录
                    $pmlRepo->store(['date' => $date, 'pid' => $pid, 'uid' => $member->id, 'day_cost' =>
                        $member->cost]);
                }
                //更新项目统计
                $this->createOrUpdate($pid, $sumCost);
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
    public function createOrUpdate(int $pid, int $cost)
    {
        $ps = ProjectStat::where('pid', $pid)->first();
        if (empty($ps)){
            $this->store(['pid' => $pid, 'cost' => $cost, 'day_cost' => $cost]);
        } else {
            $ps->increment('cost', $cost, ['day_cost' => $cost]);
        }
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
