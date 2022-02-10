<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;

class RoleController extends Controller
{
    protected $model;

    public function __construct(Role $role)
    {
        $this->model = $role;
    }

    public function index () {
        return $this->model->with('permisions')->get();
    }

    public function create (Request $request) {
        $insertRoleData = ['name' => $request->name];
        if ($request->description !== '') $insertRoleData['description'] = $request->description;

        $role = $this->model->create($insertRoleData);

        if (count($request->permision_ids) > 0) {
            $role->permisions()->attach($request->permision_ids);
        }

        return 1;
    }

    public function update (Request $request, $id) {

        $role = $this->model->where('id', $id)->first();

        if ($role) {

            if ($request->name || $request->description) {

                $updateData = [];

                if ($request->name) $updateData['name'] = $request->name;

                if ($request->description) $updateData['description'] = $request->description;

                $update = $role->update($updateData);
            }
            if (count($request->permision_ids) > 0) {
                $role->permisions()->sync($request->permision_ids);
            }
            return 1;
        }
        return response(['message' => 'Unprocess Entity'], 422);

    }
}
