<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Media;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{

    protected $model;

    public function __construct(Media $media)
    {
        $this->model = $media;
    }

    public function index (Request $request) {

        $params = $request->all();

        $medias = $this->model->listItem($params);

        if (count($medias) > 0) {
            return response($medias);
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function store (Request $request) {
        $path = $this->model->upLoadFile();

        $media = $this->model->create(['path' => $path]);

        if ($media) {
            return response($media);
        }

        return response([], 404);
    }

    public function update (Request $request, Media $id) {
        $media = $this->model->find($id)->first();

        if ($media) {
            $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

            $update = $media->update($updateData);

            if ($update) {
                $mediaUpdate = $this->model->find($id)->first();
                return response($mediaUpdate);
            }
            return response(['message' => 'Unprocess Entity'], 422);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function destroy ($id) {

        $result = $this->model->where('id', $id)->first();


        if ($result) {
            $path = str_replace('storage', 'public', $result->path);
            Storage::delete($path);
            $res = $result->delete();
            return response($res);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }
}
