<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EnterLinkHit;

class EnterLinkHitController extends Controller
{
    protected $model;

    public function __construct(EnterLinkHit $enterLinkHit)
    {
        $this->model = $enterLinkHit;
    }
    public function getEnterLinkHitById (Request $request, $id) {
        return $this->model->listItems($request->all(), $id);
    }
}
