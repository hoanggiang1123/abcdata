<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\LinkDirectory;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class LinkDirectoryController extends Controller
{

    protected $model;

    public function __construct(LinkDirectory $linkDirectory)
    {
        $this->model = $linkDirectory;
    }
    public function index (Request $request) {

        $category = $this->model->listItems($request->all());

        return response($category);
    }

    public function update (Request $request, LinkDirectory $linkDirectory) {

        $updateData = prepareParams($request->all(), $linkDirectory->crudNotAccepted);

        $update = $linkDirectory->update($updateData);

        if ($update) {

            return response($update);

        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=> 'bail|required'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }
        $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

        $updateData['script_id'] = hexdec(uniqid()).time();

        $linkDirectory = $this->model->create($updateData);

        if ($linkDirectory) {
            return response($linkDirectory);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function filter () {
        $description = $this->model->distinct('description')->pluck('description');
        $name = $this->model->distinct('name')->pluck('name');
        return response(['description' => $description, 'name' => $name]);
    }

    public function destroy (Request $request, $id) {
        $delete = $this->model->destroy($id);

        if ($delete) {
            return $delete;
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }
}
