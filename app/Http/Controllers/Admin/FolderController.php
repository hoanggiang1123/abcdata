<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Folder;
use App\Models\EnterLink;

class FolderController extends Controller
{

    protected $model;

    public function __construct(Folder $folder)
    {
        $this->model = $folder;
    }

    public function index(Request $request) {

        $folders = $this->model->listItems($request->all());

        return response($folders);
    }

    public function filter () {
        $description = $this->model->distinct('description')->pluck('description');
        $name = $this->model->distinct('name')->pluck('name');
        return response(['description' => $description, 'name' => $name]);
    }

    public function update (Request $request, Folder $folder) {

        $updateData = prepareParams($request->all(), $folder->crudNotAccepted);

        $update = $folder->update($updateData);

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

        $folder = $this->model->create($updateData);

        if ($folder) {
            return response($folder);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function deletes (Request $request) {

        $ids = $request->ids;

        $check = false;

        foreach ($ids as $id) {
            $enterLinks = EnterLink::where('folder_id', $id)->first();
            if ($enterLinks) {
                $check = true;
                break;
            }
        }

        if ($check === true) {
            return response(['message' => 'Pls remove all links in this folder before remove'], 422);
        }

        $delete = $this->model->destroy($ids);

        if ($delete) {
            return $delete;
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }
}
