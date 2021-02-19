<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\ProjectApplyRepository;

class ProjectApplyController extends Controller {

    use ApiTrait;

    public $permissions = ['store,update' => 'project-apply.update'];

    protected $keys = [];
    protected $repo;

    public function __construct(ProjectApplyRepository $repo)
    {
        $this->repo = $repo;
    }

    public function store(Request $request)
    {
        $keys = ['pid', 'member_ids'];
        $data = $this->censor($request, 'projectApply.apply', $keys);

        $this->repo->apply($data);

        return $this->success();
    }

    public function update(Request $request, $id)
    {
        $projectApply = $this->repo->find($id);
        if (empty($projectApply))
            return $this->failure_notexists();

        $keys = ['status'];
        $data = $this->censor($request, 'projectApply.store', $keys, $projectApply);

        $data['operator_uid'] = Auth::user()->id;
        $project = $this->repo->update($projectApply, $data);

        return $this->success();
    }
}
