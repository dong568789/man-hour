<?php
namespace App\Tools;

use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\User;

class Helper {

    use ApiTrait;

    public static function isSuper(User $user)
    {
        return static::roleName($user) == 'super';
    }

    public static function isPm(User $user)
    {
        return static::roleName($user) == 'pm';
    }

    public static function isMember(User $user)
    {
        return static::roleName($user) == 'project-member';
    }

    public static function isFinance(User $user)
    {
        return static::roleName($user) == 'finance';
    }

    public static function roleName(User $user)
    {
        $role = $user->roles->first();
        return array_get($role, 'name');
    }

    public static function getRoleTpl(User $user)
    {
        $roleName = static::roleName($user);
        switch ($roleName) {
            case 'pm':
                $tpl = "pm";
                break;
            case 'super':
                $tpl = "pm";
                break;
            case 'finance':
                $tpl = "finance";
                break;
            case 'project-member':
                $tpl = "member";
                break;
            default:
                $tpl = "";
        }

        return $tpl;
    }

    public static function uniqueMonth(array $dates)
    {
        $dates = array_unique(array_map(function ($date) {
            return substr($date, 0, 7);
        }, $dates));

        sort($dates);

        return $dates;
    }


    public function _getOther(Request $request, Builder $builder)
    {
        $tables_columns = $this->_getColumns($builder);
        $this->_doFilters($request, $builder, $tables_columns);
        $this->_doQueries($request, $builder);

        $query = $builder->getQuery();

        if (!empty($query->groups)) //group by
        {

            //return $query->getCountForPagination($query->groups);
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

