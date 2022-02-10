<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TeleSaleRecord;

use Illuminate\Support\Facades\Validator;

class TeleSaleRecordController extends Controller
{
    protected $model;

    public function __construct(TeleSaleRecord $teleSaleRecord) {
        $this->model = $teleSaleRecord;
    }

    public function index (Request $request) {

        $result = $this->model->listItems($request->all());

        return $result;

        if (count($result) > 0) {
            return $result;
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function update (Request $request, $id) {
        $item = $this->model->where('id', $id)->first();

        if ($item) {

            $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

            $update = $item->update($updateData);

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

        $item = $this->model->create($updateData);

        if ($item) {
            return response($item);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function statisticCall (Request $request) {
        $items = $this->model->statisticCall($request->all());
        return $items;
        if (count($items) > 0) {
            return $items;
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function getFilter () {
        $items = $this->model->getFilter();
        return $items;
    }
}
