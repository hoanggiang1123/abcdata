<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PluginRepo;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Storage;


class PluginRepoController extends Controller
{
    protected $model;

    public function __construct(PluginRepo $pluginRepo)
    {
        $this->model = $pluginRepo;
    }

    public function index () {
        return $this->model->all();
    }

    public function store (Request $request) {

        $validator = Validator::make($request->all(), [
            'name'=> 'bail|required',
            'slug' => 'bail|required',
            'version' => 'bail|required',
            'download_url' => 'bail|required',
            'requires' => 'bail|required'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }

        $insertData = prepareParams($request->all(), $this->model->crudNotAccepted);

        $pluginRepo = $this->model->create($insertData);

        if ($pluginRepo) return $pluginRepo;

        return response(['message' => 'Unprocess Entity'], 422);

    }

    public function uploadPlugin (Request $request) {
        if ($request->hasFile('plugin')) {

            $name = request()->file('plugin')->getClientOriginalName();

            $path = request()->file('plugin')->storeAs('public/PluginRepo', $name);

            if ($path) return Storage::url($path);

            return null;
        }
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name'=> 'bail|required',
            'slug' => 'bail|required',
            'version' => 'bail|required',
            'download_url' => 'bail|required',
            'requires' => 'bail|required'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }


        try {
            $insertData = prepareParams($request->all(), $this->model->crudNotAccepted);

            $update = $this->model->where('id', $id)->update($insertData);

            if ($update) return $update;

        } catch (\Exception $err) {

            return response(['message' => $err->getMessage()], 422);
        }
    }

    public function show($id) {
        $pluginRepo =  $this->model->where('id', $id)->first();

        if ($pluginRepo) {

            $pluginRepo->download_url = config('app.proxy_url').$pluginRepo->download_url . '?ver=' . $pluginRepo->version;

            return $pluginRepo;
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }
}
