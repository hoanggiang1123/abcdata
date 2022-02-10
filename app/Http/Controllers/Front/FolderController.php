<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Folder;

class FolderController extends Controller
{
    public function __construct(Folder $folder)
    {
        $this->model = $folder;
    }

    public function show ($script_id) {
        $item = $this->model->where('script_id', $script_id)->with('enterLinks')->first();

        if ($item) {

            return $item;
        }

        return response([], 404);
    }

    public function shortcode (Request $request) {
        $res = $this->model->listItemShortCode($request->all());
        return $res;
    }
}
