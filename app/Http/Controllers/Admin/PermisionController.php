<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Permision;

class PermisionController extends Controller
{
    protected $model;

    public function __construct(Permision $permision)
    {
        $this->model = $permision;
    }

    public function index () {
        return $this->model->where('parent_id', 0)->with('permisionChildren')->get();
    }
}
