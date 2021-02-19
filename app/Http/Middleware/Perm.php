<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

use App\User;

class Perm
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{
		$user = (new User())->forceFill(Auth::guard($guard)->user()->attributesToArray());
		Auth::setUser($user);  // set a new user model

		$route = $request->route();
		$role = $user->roles->first();

	    if (strpos($route->action['uses'], '@data') !== false || strpos($route->action['uses'], '@export') !== false)
		{
			$q = $request->input('q', []);
			$q['ofPm'] = [];
			$q['ofFinance'] = [];

			$roleName = array_get($role, 'name');
			if(!in_array($roleName, ['pm'])){
				unset($q['ofPm']);
			}

			if(!in_array($roleName, ['finance'])){
				unset($q['ofFinance']);
			}

			if (isset($q['ofPm']))
				$q['ofPm'] = $user->id;

			if (isset($q['ofFinance']))
				$q['ofFinance'] = $user->id;

			$request->offsetSet('q', $q);
		}

		return $next($request);
	}
}
