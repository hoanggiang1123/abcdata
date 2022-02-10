<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Link;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class LinkController extends Controller
{

    protected $model;

    public function __construct(Link $link)
    {
        $this->model = $link;
    }
    public function index (Request $request) {

        $links = $this->model->listItems($request->all());

        return response($links);
    }

    public function update (Request $request, Link $link) {

        $updateData = prepareParams($request->all(), $link->crudNotAccepted);

        $update = $link->update($updateData);

        if ($update) {

            return response($update);

        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'link'=> 'bail|required',
            'image' => 'bail|required',
            'link_directory_id' => 'bail|required'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }
        $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

        $updateData['slug'] = hexdec(uniqid()) . time();

        $link = $this->model->create($updateData);

        if ($link) {
            return response($link);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function show ($link) {
        $result = $this->model->where('id', $link)->with('linkHits')->first();

        if ($result) {
            return response($result);
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function upload() {

        $path = $this->model->upLoadFile();

        if ($path) {
            return response(['image' => $path]);
        }

        return response([], 404);
    }

    public function filter (Request $request) {
        if ($request->id) {
            $description = $this->model->where('link_directory_id', $request->id)->distinct('description')->pluck('description');
            $link = $this->model->where('link_directory_id', $request->id)->distinct('link')->pluck('link');
            $position = $this->model->where('link_directory_id', $request->id)->distinct('position')->pluck('position');
            $status = $this->model->where('link_directory_id', $request->id)->distinct('status')->pluck('status');
            $category = [];
        } else {
            $description = $this->model->distinct('description')->pluck('description');
            $link = $this->model->distinct('link')->pluck('link');
            $position = $this->model->distinct('position')->pluck('position');
            $status = $this->model->distinct('status')->pluck('status');
            $category = \App\Models\LinkDirectory::select('id', 'name')->distinct('name')->pluck('name', 'id');
        }

        return response(['description' => $description, 'link' => $link, 'position' => $position, 'status' => $status, 'category' => $category]);
    }

    public function createMany (Request $request) {

        $params = $request->input();

        DB::beginTransaction();

        try {

            if ($params && count($params) > 0) {
                foreach ($params as $val) {
                    $item = prepareParams($val, $this->model->crudNotAccepted);
                    $item['slug'] = hexdec(uniqid()) . time();
                    $item['hit'] = 0;
                    $this->model->create($item);
                }
            }

            DB::commit();

            return 1;

        } catch (\Exception $e) {

            DB::rollback();

            return response(['message', 'Unprocess Entity'], 422);
        }

    }

    public function destroy (Request $request, $id) {
        $delete = $this->model->destroy($id);

        if ($delete) {
            return $delete;
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

}
