<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Addons\Core\ApiTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;

use App\Repositories\MessageRepository;

class MessageController extends Controller {

    use ApiTrait;

    protected $repo;

    public function __construct(MessageRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $size = $request->input('size') ?: $this->repo->prePage();
        //view's variant
        $this->_size = $size;
        $this->_filters = $this->repo->_getFilters($request);
        $this->_queries = $this->repo->_getQueries($request);

        return $this->view('admin.message.list');
    }

    public function data(Request $request)
    {
        $data = $this->repo->data($request);
        return $this->api($data);
    }

    public function export(Request $request)
    {
        $request = $this->parseRequest($request);
        $data = $this->repo->export($request);

        return $this->office($data);
    }

    public function show(Request $request, $id)
    {
        $message = $this->repo->find($id);
        if (empty($message))
            return $this->failure_notexists();

        $this->_data = $message;
        return !$request->offsetExists('of') ? $this->view('admin.message.show') : $this->api($message->toArray());
    }

    public function create(Request $request)
    {
        $this->_data = [];
        $this->_validates = $this->censorScripts('message.store', $this->keys);
        return $this->view('admin.message.create');
    }

    public function store(Request $request)
    {
        $data = $this->censor($request, 'message.store', $this->keys);

        $message = $this->repo->store($data);

        return $this->success('', url('admin/message'));
    }

    public function edit($id)
    {
        $message = $this->repo->find($id);
        if (empty($message))
            return $this->failure_notexists();

        $this->_validates = $this->censorScripts('message.store', $this->keys);
        $this->_data = $message;
        return $this->view('admin.message.edit');
    }

    public function update(Request $request, $id)
    {
        $message = $this->repo->find($id);
        if (empty($message))
            return $this->failure_notexists();

        $data = $this->censor($request, 'message.store', $this->keys, $message);

        $this->repo->update($message, $data);

        return $this->success();
    }


    public function destroy(Request $request, $id)
    {
        empty($id) && !empty($request->input('id')) && $id = $request->input('id');
        $ids = array_wrap($id);

        $this->repo->destroy($ids);
        return $this->success(null, true, ['id' => $ids]);
    }

    public function read(Request $request)
    {
        $input = $request->all();

        $url = "";
        if ($input['type'] == 'register') {

            $message = $this->repo->findByCode($input['code']);
            $message->read = true;
            $message->save();

            $url = url('admin/member') . "?q[ofRole]={$input['role']}";
        }

        if (empty($url)) {
            return $this->error("未知错误，请联系管理员");
        }

        return redirect($url);

    }

}
