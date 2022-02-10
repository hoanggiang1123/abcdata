<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\EnterLink;

class EnterLinkController extends Controller
{

    protected $model;

    public function __construct(EnterLink $enterLink)
    {
        $this->model = $enterLink;
    }
    public function index (Request $request) {
        $enterlinks = $this->model->listItems($request->all());

        return response($enterlinks);
    }

    public function filter (Request $request) {
        $folderId = $request->id;
        if ($folderId) {
            $name = $this->model->where('folder_id', $folderId)->distinct('name')->pluck('name');
            $link = $this->model->where('folder_id', $folderId)->distinct('link')->pluck('link');
            $folder = [];
        } else {
            $name = $this->model->distinct('name')->pluck('name');
            $link = $this->model->distinct('link')->pluck('link');
            $folder = \App\Models\Folder::select('id', 'name')->distinct('name')->pluck('name', 'id');
        }

        return response(['name' => $name, 'link' => $link, 'folder' => $folder]);
    }

    public function update (Request $request, EnterLink $enterLink) {
        $updateData = prepareParams($request->all(), $enterLink->crudNotAccepted);

        $update = $enterLink->update($updateData);

        if ($update) {

            return response($update);

        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'link'=> 'bail|required',
            'name' => 'bail|required',
            'folder_id' => 'bail|required'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }
        $updateData = prepareParams($request->all(), $this->model->crudNotAccepted);

        $updateData['slug'] = hexdec(uniqid()) . time();

        $enterLink = $this->model->create($updateData);

        if ($enterLink) {
            return response($enterLink);
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
                    $item['slug'] = hexdec(uniqid()) . time();
                    $item['hit'] = 0;
                    $this->model->create($item);
                }
            }

            DB::commit();

            return 1;

        } catch (\Exception $e) {

            DB::rollback();

            return response(['message', 'Unprocess Entity'], 422);
        }
    }

    public function show ($id) {

        $result = $this->model->where('id', $id)->with('enterLinkHits')->first();

        if ($result) {
            return response($result);
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function editVote (Request $request) {

        $validator = Validator::make($request->all(), [

            'id'=> 'bail|required',

            'init_vote_count' => 'bail|required|integer'
        ]);

        if ($validator->fails()) {

            return response([

                'message' => $validator->messages()->first()

            ], 422);
        }

        $enterLink = $this->model->where('id', $request->id)->first();

        if ($enterLink) {

            DB::beginTransaction();

            try {

                $initVoteCountChange = ( (int) $request->init_vote_count ) - ($enterLink->init_vote_count);


                $enterLink->update(['init_vote_count' => (int) $request->init_vote_count]);

                $totalVote = $enterLink->folder->total_vote;

                $totalVoteChange = $totalVote + $initVoteCountChange;

                $enterLink->folder()->update(['total_vote' => $totalVoteChange]);

                DB::commit();

                return 1;

            } catch (\Exception $e) {

                DB::rollback();

                return response(['message' => 'Unprocess Entity'], 422);
            }

        }

        return response(['message' => 'Not Found'], 404);
    }

    public function deleteMany (Request $request) {
        $ids = $request->ids;

        $delete = $this->model->destroy($ids);

        if ($delete) {
            return $delete;
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function destroy ($id) {
        $delete = $this->model->destroy($id);

        if ($delete) {
            return $delete;
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }
}
