<?php
namespace App\Tools;

use App\User;

class Helper {

    public static function getRoleTpl(User $user)
    {
        $role = $user->roles()->first();
        switch ($role->name) {
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

