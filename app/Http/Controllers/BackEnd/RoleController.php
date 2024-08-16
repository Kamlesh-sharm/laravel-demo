<?php

namespace App\Http\Controllers\BackEnd;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
  public function index()
  {
    $data['roles'] = Role::all();
    return view('backend.role.index', $data);
  }

  public function store(Request $request)
  {

    $rules = [
      'name' => 'required|max:255',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $role = new Role;
    $role->name = $request->name;
    $role->save();

    Session::flash('success', 'Role added successfully!');
    return "success";
  }

  public function update(Request $request)
  {
    $rules = [
      'name' => 'required|max:255',
    ];

    $validator = Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Response()->json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $role = Role::where('id', $request->role_id)->first();
    $role->name = $request->name;
    $role->save();

    Session::flash('success', 'Role updated successfully!');
    return "success";
  }

  public function delete(Request $request)
  {

    $role = Role::where('id', $request->role_id)->first();
    if ($role->admins()->count() > 0) {
      Session::flash('warning', 'Please delete the users under this role first.');
      return back();
    }
    $role->delete();

    Session::flash('success', 'Role deleted successfully!');
    return back();
  }

  public function managePermissions($id)
  {
    $data['role'] = Role::where('id', $id)->firstOrFail();
    return view('backend.role.permission.manage', $data);
  }

  public function updatePermissions(Request $request)
  {
    $permissions = json_encode($request->permissions);
    $role = Role::where('id', $request->role_id)->first();
    $role->permissions = $permissions;
    $role->save();

    Session::flash('success', "Permissions updated successfully for '$role->name' role");
    return back();
  }
}
