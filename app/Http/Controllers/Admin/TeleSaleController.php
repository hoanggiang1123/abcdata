<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
// use Illuminate\Support\Facades\DB;

use App\Models\TeleSale;
use App\Models\TeleSaleLine;
use App\Models\User;
use App\Models\TeleSaleAgent;

use App\Imports\TeleSaleImport;
use Maatwebsite\Excel\Facades\Excel;

class TeleSaleController extends Controller
{
    protected $model;
    protected $line;
    protected $user;

    public function __construct(TeleSale $teleSale, TeleSaleLine $teleSaleLine, User $user)
    {
        $this->model = $teleSale;
        $this->line = $teleSaleLine;
        $this->user = $user;
    }

    public function index (Request $request) {

        $check = false;

        $roles = $request->user()->roles;

        foreach ($roles as $role) {

            $permisions = $role->permisions;

            if ($permisions->contains('controller', 'TeleSaleController@index')) {
                $check = true;
                break;
            }
        }

        $params = $request->all();

        if ($check === false) $params['own_id'] = $request->user()->id;

        $result = $this->model->listItems($params);

        if ($result && count($result) > 0) return $result;

        return response(['message' => 'Not Found'], 404);
    }

    public function update(Request $request, $id) {

        $teleSale = $this->model->where('id', $id)->first();

        if ($teleSale) {

            $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

            $update = $teleSale->update($updateData);

            if ($update) return $update;

            return response(['message' => 'Unprocess Entiry'], 422);

        }

        return response(['message' => 'Unprocess Entiry'], 422);
    }

    public function users () {
        $user = $this->user->select('id', 'name')->get();
        return $user;
    }

    public function checkDuplicates () {

        $duplicate = $this->model->duplicateList();

        if ($duplicate) {
            return $duplicate;
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function updateAssign (Request $request) {
        $ids = $request->ids;

        $assignId = $request->assign_id;

        $update = $this->model->whereIn('id', $ids)->update(['assign_id' => $assignId]);

        if ($update) return $update;

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
            'full_name'=> 'bail|required',
            'phone' => 'bail|required|unique:tele_sales',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }

        $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

        $updateData['assign_id'] = auth()->user()->id;

        $teleSale = $this->model->create($updateData);

        if ($teleSale) {
            return response($teleSale);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function import (Request $request) {

        if ($request->hasFile('excel')) {

            try {

                Excel::import(new TeleSaleImport, $request->file('excel'));

                return 1;

            } catch (\InvalidArgumentException $error) {
                return response(['message' => $error->getMessage()], 422);
            }
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function teleLine (Request $request) {
        $result = $this->line->listItems($request->all());

        return $result;
    }

    public function updateLine (Request $request) {
        $id = $request->id;

        $lineId = $request->line_id;

        $teleSale = $this->model->where('id', $id)->first();

        if (!$lineId && $teleSale) {

            $update = $teleSale->update(['line_id' => null, 'agent_id' => null]);
        }
        else if ($lineId && $teleSale) {
            $update = $teleSale->update(['line_id' => $lineId]);
        }

        if ($update) return $update;

        return response(['message' => 'Unprocess Entiry'], 422);
    }

    public function updateAgent (Request $request) {
        $id = $request->id;

        $agentId = $request->agent_id;

        $teleSale = $this->model->where('id', $id)->first();

        $update = 0;

        if ($teleSale) $update = $teleSale->update(['agent_id' => $agentId]);

        if ($update) return $update;

        return response(['message' => 'Unprocess Entiry'], 422);
    }

    public function getAgent (Request $request) {

        $lineId = $request->id;

        if ($lineId) {
            $agent = TeleSaleAgent::select('id', 'name')->where('line_id', $lineId)->get();
        }
        else {
            $agent = TeleSaleAgent::select('id', 'name')->get();
        }

        if (count($agent) > 0) return \response($agent);

        return response(['message' => 'Unprocess Entiry'], 422);
    }

    public function bulkEdit (Request $request) {

        $ids = $request->ids;

        $line = $request->line;
        $agent = $request->agent;

        if ($line === 'null') {
            $update = $this->model->whereIn('id', $ids)->update(['line_id' => null, 'agent_id' => null]);
            return $update;
        }
        else {
            if ($agent === '' || $agent === 'null') {
                $update = $this->model->whereIn('id', $ids)->update(['line_id' => $line, 'agent_id' => null]);
                return $update;
            } else {
                $update = $this->model->whereIn('id', $ids)->update(['line_id' => $line, 'agent_id' => $agent]);
                return $update;
            }
        }

        return response(['message' => 'Unprocess Entiry'], 422);

    }

    public function bulkAction (Request $request) {
        $ids = $request->ids;
        $field = $request->update_field;
        $value = $request->value;
        if ($field) {
            $update = $this->model->whereIn('id', $ids)->update([$field => $value]);
            return $update;
        }
        return response(['message' => 'Unprocess Entiry'], 422);
    }

    public function listSource (Request $request) {
        $sources = $this->model->distinct('source')->pluck('source');

        if ($sources && count($sources) > 0) {

            $resp = [];

            foreach($sources as $source) {

                $parse = parse_url($source);

                if (isset($parse['host']) && $parse['host'] !== null) {

                    if (\in_array($parse['host'], $resp) === false) {

                        $resp[] = $parse['host'];
                    }


                } else if ($source !== '' && $source !== null) {

                    if (\in_array($source, $resp) === false) {

                        $resp[] = $source;
                    }

                }

            }
            return $resp;
        }
        return response(['message' => 'Not Found'], 404);
    }


}
