<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\SatiliteDomain;

class SatiliteDomainController extends Controller
{
    protected $model;

    public function __construct(SatiliteDomain $satiliteDomain) {
        $this->model = $satiliteDomain;
    }

    public function index (Request $request) {
        $result = $this->model->listItems($request->all());
        if (count($result) > 0) {
            return $result;
        }
        return \response(['message' => 'Not Found'], 404);
    }

    public function update (Request $request, $id) {
        $keyword = $this->model->where('id', $id)->first();

        if ($keyword) {

            $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

            $update = $keyword->update($updateData);

            if ($update) return $update;

            return response(['message' => 'Unprocess Entiry'], 422);

        }

        return response(['message' => 'Unprocess Entiry'], 422);
    }

    public function deleteMany (Request $request) {
        $ids = $request->ids;

        $delete = $this->model->destroy($ids);

        if ($delete) {
            return $delete;
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=> 'bail|required'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }

        $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

        $keyword = $this->model->create($updateData);

        if ($keyword) {
            return response($keyword);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function createMany (Request $request) {
        $params = $request->input();

        DB::beginTransaction();

        try {

            if ($params && count($params) > 0) {
                foreach ($params as $val) {
                    $item = prepareParams($val, $this->model->crudNotAccepted);
                    $this->model->create($item);
                }
            }

            DB::commit();

            return 1;

        } catch (\Exception $e) {

            DB::rollback();

            return response(['message' => 'Unprocess Entity'], 422);
        }
    }
}
