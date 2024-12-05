<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Hash;
use Auth;
use Illuminate\Support\Arr;

class UserController extends AdminBaseController
{
    public function __construct()
    {
        $this->middleware('permission:user-list')->only('index');
        $this->middleware('permission:user-create')->only('create', 'store');
        $this->middleware('permission:user-view')->only('show');
        $this->middleware('permission:user-edit')->only('edit', 'update');
        $this->middleware('permission:user-delete')->only('destroy');
        $this->middleware('permission:user-permission')->only('user_permission','user_permission_update');
    }
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->is_admin) {
            $data = User::orderBy('id', 'DESC')->orderBy('id','DESC')->get();
        }
        else {
            // If regular user, get only their data
            $user->UserCompanies->pluck("company_id");

            $data = User::whereHas('UserCompanies', function ($query) use ($user) {
                $query->whereIn('company_id',$user->UserCompanies->pluck("company_id"));
            })->orderBy('id','DESC')->get();
        }
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    public function create()
    {

        if (Auth::user()->is_admin) {
           $roles = Role::select(['id','name'])->get();
           $companies = Company::where("is_default","0")->get();
        }
        else
        {
           $roles = Role::select(['id','name'])->where("name","!=","Admin")->get();
           $companies = Company::where("status","A")->where("is_default","0")->whereIn("id",Auth::user()->UserCompanies->pluck("company_id"))->get();
        }
        $departments = Department::get();
        return view('users.create',compact('roles','departments','companies'));
    }
    public function store(Request $request)
    {
       $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
        $input = $request->except("company_id");
        $input['password'] = Hash::make($input['password']);
        $input['created_by'] = Auth::user()->id;
        $input['company_id'] = 0;
        $user = User::create($input);

        foreach ($request->departments as $departmentId) {
            if($departmentId != 'all'){
                $user->UserDepartments()->create(['department_id' => $departmentId]);
            }
        }
        $default_companies = Company::where("is_default","1")->get()->pluck("id");
        foreach ($default_companies as $dcompany) {
            if($dcompany != 'all'){
                $user->UserCompanies()->create(['company_id' => $dcompany]);
            }
        }
        foreach ($request->company_id as $company) {
            if($company != 'all'){
                $user->UserCompanies()->create(['company_id' => $company]);
            }
        }
        $user->assignRole($request->input('roles'));
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }
    public function assign_user(Request $request)
    {
        $user = User::get();
        return response()->json(['success'=>'success','users'=>$user]);
    }
    public function edit($id)
    {
        $data['departments'] = Department::all();
        $data['user'] = User::find($id);
        $data['roles'] = Role::select('id','name')->get();
        $data['userRole'] = $data['user']->roles->pluck('name','name')->all();
        if (Auth::user()->is_admin) {
            $data['companies'] = Company::where("is_default","0")->get();
         }
         else
         {
            $data['companies'] = Company::where("status","A")->where("is_default","0")->whereIn("id",Auth::user()->UserCompanies->pluck("company_id"))->get();
         }
        return view('users.edit',$data);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'company_id' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'roles' => 'required'
        ]);
        $input = $request->except("department_id","company_id");
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }
        $user = User::find($id);
        $user->update($input);
        $user->UserDepartments()->delete();
        foreach ($request->department_id as $departmentId) {
            if($departmentId != 'all'){
                $user->UserDepartments()->create(['department_id' => $departmentId]);
            }
        }
        $user->UserCompanies()->delete();
        $default_companies = Company::where("is_default","1")->get()->pluck("id");
        foreach ($default_companies as $dcompany) {
            if($dcompany != 'all'){
                $user->UserCompanies()->create(['company_id' => $dcompany]);
            }
        }
        foreach ($request->company_id as $company) {
            if($company != 'all'){
                $user->UserCompanies()->create(['company_id' => $company]);
            }
        }
        DB::table('model_has_roles')->where('model_id',$id)->delete();
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')->with('success','User deleted successfully');
    }
    public function user_permission($id){

        // Get the authenticated user
        $user = User::find($id);
        $roles = $user->roles;
        $firstRole = $roles->first();

        $userPermissions = $user ? $user->permissions->pluck('name')->toArray() : [];
        $permission = Permission::get();
        return view('users.user_permission',compact('user','permission','userPermissions','roles'));
    }

    public function user_permission_update(Request $request, $id)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);

        if (!$user) {
            abort(404); // or handle it in a way that makes sense for your application
        }

        // Revoke existing permissions
        $user->revokePermissionTo($user->permissions);

        // Get the new permissions from the request
        $permissions = $request->permission;

        // Grant new permissions
        if(isset($permissions)){
            foreach ($permissions as $permission) {
                $user->givePermissionTo($permission);
            }
        }

        return redirect()->route('users.index')
                         ->with('success', 'User updated permissions assigned successfully');
    }
}
