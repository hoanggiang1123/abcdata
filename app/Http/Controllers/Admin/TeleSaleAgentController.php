<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\TeleSaleAgent;

class TeleSaleAgentController extends Controller
{
    protected $model;

    public function __construct(TeleSaleAgent $teleSaleAgent) {
        $this->model = $teleSaleAgent;
    }

    public function index (Request $request) {

        $result = $this->model->listItems($request->all());

        return $result;
    }

    public function update (Request $request, $id) {
        $line = $this->model->where('id', $id)->first();

        if ($line) {

            $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

            $update = $line->update($updateData);

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

        $line = $this->model->create($updateData);

        if ($line) {
            return response($line);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }
}
