<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Popup;

class PopupController extends Controller
{
    protected $model;

    public function __construct(Popup $popUp)
    {
        $this->model = $popUp;
    }

    public function index (Request $request) {
        return $this->model->listItems($request->all());
    }

    public function update (Request $request, $id) {
        $pop = $this->model->where('id', $id)->first();

        if ($pop) {

            $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

            $update = $pop->update($updateData);

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
            'domain'=> 'bail|required',
            'content' => 'bail|required',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }

        $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

        $pop = $this->model->create($updateData);

        if ($pop) {
            return response($pop);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function getContent (Request $request) {
        $host = $request->headers->get('referer');

        $pop = $this->model->where('domain', $host)->first();

        if ($pop) return $pop;

        return response(['message' => 'Not Found'], 404);
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

    public function updateAll(Request $request) {
        $content = $request->content ? $request->content : '';
        $name = $request->name ? $request->name : '';

        if ($content === '' || $name === '') return \response(['message' => 'Unprocess entity'], 422);

        $affected = DB::table('popups')->update(['content' => $content, 'name' => $name]);

        if ($affected) return $affected;

        return \response(['message' => 'Unprocess entity'], 422);
    }

    public function updateDataTele (Request $request) {
        // $host = $request->headers->get('referer');
        $full_name = $request->full_name ? $request->full_name : '';
        $phone = $request->phone ? $request->phone : '';
        $email = $request->email ? $request->email : '';
        $source = $request->source ? $request->source : '';
        $category = 6;

        $validator = Validator::make($request->all(), [
            'full_name'=> 'bail|required',
            'phone' => 'bail|required|unique:tele_sales',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }

        $affected = DB::table('tele_sales')->insert([
            'full_name' => $full_name,
            'phone' => $phone,
            'email' => $email,
            'category_id' => $category,
            'source' => $source,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($affected) return $affected;

        return \response(['message' => 'Unprocess entity'], 422);

    }
}
