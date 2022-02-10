<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\Post;

class PostController extends Controller
{

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function index (Request $request) {
        return $this->post->listItems($request->all());
    }

    public function update (Request $request, $id) {
        $post = $this->post->where('id', $id)->first();

        if ($post) {

            $updateData = prepareParams($request->all(), $this->post->crudNotAccepted);

            $update = $post->update($updateData);

            if ($update) return $update;

            return response(['message' => 'Unprocess Entiry'], 422);

        }

        return response(['message' => 'Unprocess Entiry'], 422);
    }

    public function deleteMany (Request $request) {
        $ids = $request->ids;

        $delete = $this->post->destroy($ids);

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

        $updateData = prepareParams($request->all(), $this->post->crudNotAccepted);

        $post = $this->post->create($updateData);

        if ($post) {
            return response($post);
        }

        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function getContent (Request $request) {
        $host = $request->headers->get('referer');

        $post = $this->post->where('domain', $host)->get();

        if ($post && count($post) > 0) {

            $result = [];

            foreach ($post as $item) {
                $result[$item->position] = $item;
            }

            return $result;
        }

        return response(['message' => 'Not Found'], 404);
    }

    public function createMany (Request $request) {
        $params = $request->input();

        DB::beginTransaction();

        try {

            if ($params && count($params) > 0) {
                foreach ($params as $val) {
                    $item = prepareParams($val, $this->post->crudNotAccepted);
                    $this->post->create($item);
                }
            }

            DB::commit();

            return 1;

        } catch (\Exception $e) {

            DB::rollback();

            return response(['message' => 'Unprocess Entity'], 422);
        }
    }

    public function updateAllPostContent (Request $request) {
        $content = $request->content ? $request->content : '';

        if ($content === '') return \response(['message' => 'Unprocess entity'], 422);

        $affected = DB::table('posts')->update(['content' => $content]);

        if ($affected) return $affected;

        return \response(['message' => 'Unprocess entity'], 422);
    }
}
