<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\ProjectMemberRepository;

class ProjectMemberController extends Controller {

    use ApiTrait;

   // public $permissions = ['store,update,destroy' => 'project.store'];

    protected $keys = [];
    protected $repo;

    public function __construct(ProjectMemberRepository $repo)
    {
        $this->repo = $repo;
    }

    public function destroy(Request $request, $id)
    {
        empty($id) && !empty($request->input('id')) && $id = $request->input('id');
        $ids = array_wrap($id);

        $this->repo->destroy($ids);
        return $this->success(null, true, ['id' => $ids]);
    }
}
