<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Models\LinkEmbeded;

class LinkEmbededController extends Controller
{

    protected $model;

    public function __construct(LinkEmbeded $linkEmbeded)
    {
        $this->model = $linkEmbeded;
    }

    public function index () {
        return $this->model->orderBy('created_at', 'desc')->get();
    }

    public function store (Request $request) {

        $validator = Validator::make($request->all(), [
            'link_ids'=> 'bail|required',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }

        $data = prepareParams($request->all(), $this->model->crudNotAccepted);

        $data['script_id'] = hexdec(uniqid()).time();

        $item = $this->model->create($data);

        if ($item) return ['script_url' => config('app.proxy_url') . '/api/link-embed/' . $item->script_id ];

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function update(Request $request, $id) {

        $data = prepareParams($request->all(), $this->model->crudNotAccepted);

        $update = $this->model->where('id', $id)->update($data);

        if ($update) {

            return response($update);

        }

        return response(['message' => 'Unprocess Entity'], 422);

    }

    public function destroy (Request $request, $id) {

        $result = $this->model->where('id', $id)->delete();

        if ($result) {

            return response($result);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function trash () {
        return $this->model->onlyTrashed()->orderBy('created_at', 'desc')->get();
    }

    public function trashRestore ($id) {

        $restore = $this->model->withTrashed()->where('id', $id)->restore();

        if ($restore) {

            return response(['message' => 'Restore Item Success']);
        }

        return response(['message' => 'Can not restore'], 401);
    }

    public function trashDestroy ($id) {
        $delete = $this->model->withTrashed()->where('id', $id)->forceDelete();

        if ($delete) {

            return response($delete);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }
}
