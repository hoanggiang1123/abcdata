<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\FbLeague;
use Illuminate\Support\Facades\Validator;

class FbLeagueController extends Controller
{

    protected $model;

    public function __construct(FbLeague $fbLeague) {
        $this->model = $fbLeague;
    }

    public function index (Request $request) {
        $result = $this->model->listItems($request->all());

        if (count($result) > 0) return $result;

        return \response(['message' => 'Not Found'], 400);
    }

    public function update(Request $request, $id) {
        $league = $this->model->where('id', $id)->first();

        if ($league) {

            $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

            $update = $league->update($updateData);

            if ($update) return $update;

            return response(['message' => 'Unprocess Entiry'], 422);

        }

        return response(['message' => 'Unprocess Entiry'], 422);
    }

    public function destroy (Request $request) {
        $ids = $request->ids;

        $delete = $this->model->destroy($ids);

        if ($delete) {
            return $delete;
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=> 'bail|required',
            'slug' => 'bail|required'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }
        $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);


        $league = $this->model->create($updateData);

        if ($league) {
            return $league;
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }
}
