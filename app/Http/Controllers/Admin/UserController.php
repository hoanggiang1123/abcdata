<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function index () {
        return $this->model->with('roles.permisions')->get();
    }

    public function update (Request $request, $id) {

        $updateData = [];

        if ($request->email) {
            if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
                return response(['message' => 'Email Not Valid'], 401);
            }
            else {
                $updateData['email'] = $request->email;
            }
        }
        if ($request->password) {
            if (strlen($request->password) < 6) {
                return response(['message' => 'Password too short'], 401);
            }
            else {
                $updateData['password'] = $request->password;
            }
        }

        if ($request->name) {
            if (strlen($request->name) < 3) {
                return response(['message' => 'Name too short'], 401);
            }
            else {
                $updateData['name'] = $request->name;
            }
        }

        $user = $this->model->where('id', $id)->first();

        if ($user) {
            if ($request->role_ids) {
                $user->roles()->sync($request->role_ids);
                return 1;
            }
            else {
                if (count($updateData) > 0) {
                    $update = $user->update($updateData);
                    return $update;
                }
                return response(['message' => 'Unprocess Entity'], 422);
            }

        }
        return response(['message' => 'Unprocess Entity'], 422);
    }

    public function store (Request $request) {
        $validator = Validator::make($request->all(), [
            'username'=> 'bail|required|unique:users',
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6'
        ]);

        if ($validator->fails()) {
            return response([
                'message' => $validator->messages()->first()
            ], 422);
        }

        $insertData = prepareParams($request->all(), $this->model->crudNotAccepted);

        $user = $this->model->create($insertData);

        if ($request->role_ids) {
            $user->roles()->attach($request->role_ids);
        }

        return $user;
    }

}
