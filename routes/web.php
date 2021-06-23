<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//$router->pattern('id', '[0-9]+'); //所有id都是数字

$router->resources([
	'member' => 'MemberController',
]);

$router->group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth:admin', 'role:administrator.**'
    ]], function($router) {

    $router->put('member/cost/{id}', 'MemberController@cost')->where('id', '[\d]+');
    $router->post('project/apply', 'ProjectController@apply');
    $router->post('project/date/{id}', 'ProjectController@date')->where('id', '[\d]+');
    $router->get('message/read', 'MessageController@read');
	$router->crud([
		'member' => 'MemberController',
		'project' => 'ProjectController',
		'project-member' => 'ProjectMemberController',
		'project-apply' => 'ProjectApplyController',
		'project-stat' => 'ProjectStatController',
		'project-member-stat' => 'ProjectMemberStatController',
		'message' => 'MessageController',
	]);
	$router->get('/', 'HomeController@index')->name('admin-index');
	$router->put('project-apply/recall/{id}', 'ProjectApplyController@recall');//撤销申报
	$router->post('project-stat/afresh', 'ProjectStatController@afresh');//重新统计

});

$router->get('/', function () {
    return redirect('admin');

})->name('index');
$router->get('auth/login', 'AuthController@login')->name('login');
$router->actions([
	'auth' => ['index', 'login', 'logout', 'authenticate-query'],
]);

$router->group(['namespace' => 'Admin', 'prefix' => 'admin'], function($router) {

	$router->get('auth/login', 'Admin\\AuthController@login')->name('admin-login');
	$router->actions([
		'auth' => ['index', 'login', 'logout', 'choose', 'authenticate-query'],
	]);

    $router->actions([
        'register' => ['index', 'store'],
    ]);
});
