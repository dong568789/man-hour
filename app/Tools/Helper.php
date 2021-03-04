<?php
namespace App\Tools;

use App\User;

class Helper {

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
}

