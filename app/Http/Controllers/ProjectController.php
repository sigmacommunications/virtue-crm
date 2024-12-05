<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Project;
use App\Models\Client;
use App\Models\Leads;
use App\Models\ProjectCurrentStatus;
use App\Models\ProjectDepartment;
use App\Models\ProjectStatus;
use App\Models\User;
use App\Models\UsersProject;
use Illuminate\Http\Request;

class ProjectController extends Controller
{


    public function __construct()
    {
        $this->middleware('permission:project-list')->only('index');
        $this->middleware('permission:project-create')->only('create', 'store');
        $this->middleware('permission:project-view')->only('show');
        $this->middleware('permission:project-edit')->only('edit', 'update');
        $this->middleware('permission:project-delete')->only('destroy');
        $this->middleware('permission:project-conversation')->only('kanban','projectConversationb');
        $this->middleware('permission:project-assign')->only('assign_project','assign_project_update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['projects'] = Project::whereHas('Projectusers', function ($query) {
            $query->whereIn('user_id',[\Auth::id()]);
        })->orderBy('id','DESC')->get();
        return view('project.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = \Auth::user();
        $departments = Department::get();
        $projectstatus = ProjectStatus::get();
        $clients = Client::whereIn("company_id", $user->UserCompanies->pluck("company_id"))->get();
        $users = User::whereHas('UserCompanies', function ($query) use ($user) {
            $query->whereIn('company_id',$user->UserCompanies->pluck("company_id"));
        })->orderBy('id','DESC')->get();
        return view('project.create', compact('departments', 'clients', 'projectstatus','users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate(
                request(),
                [
                    'client_id' => 'required',
                    'name' => 'required',
                    'start_date' => 'required',
                    'deadline' => 'required',
                    "client_id" => "required|integer",
                    "status" => "required",
                    "description" => "required"
                ]
            );
            $project = Project::create([
                'client_id' => $request->client_id,
                'name' => $request->name,
                'start_date' => $request->start_date,
                'deadline' => $request->deadline,
                'description' => $request->description,
                "status" => $request->status,
            ]);
            foreach ($request->departments as $department) {
                if ($department != 'all') {
                    $project->ProjectDepartments()->create(['department_id' => $department]);
                    $project->ProjectCurrentStatus()->create(['department_id' => $department, "user_id" => \Auth::id(), "status_id" => 1]);
                }
            }
            foreach($request->users as $user)
            {
                $UsersProject = new UsersProject();
                $UsersProject->project_id = $project->id;
                $UsersProject->user_id = $user;
                $project->Projectusers()->save($UsersProject);
            }
            return redirect('/project')->with(['success' => 'Project Create Successfull']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $data['project'] = $project;
        $data['leads'] = Leads::get();
        $data['clients'] = Client::get();
        $data['departments'] = Department::get();
        $data['projectstatus'] = ProjectStatus::get();
        $data['users'] = User::where("company_id", \Auth::user()->company_id)->get();
        return view('project.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */

    public function assign_project($id = 0)
    {
        $users = User::where("company_id", \Auth::user()->company_id)->get();
        $project = Project::find($id);
        return view('project.assign_project',compact("project","users"));
    }

    public function assign_project_update(request $request, Project $project)
    {
        $project = Project::find($request->project_id);
        $project->Projectusers()->whereIn('user_id', $project->Projectusers->pluck('user_id')->toArray())->delete();
        foreach($request->users as $user)
        {
            $UsersProject = new UsersProject();
            $UsersProject->project_id = $project->id;
            $UsersProject->user_id = $user;
            $project->Projectusers()->save($UsersProject);
        }
        return redirect('/project')->with(['success' => 'Project Update Successfull']);

    }
    public function update(Request $request, Project $project)
    {
        try {
            $this->validate(request(), [
                'client_id' => 'required',
                'name' => 'required',
                'start_date' => 'required',
                'deadline' => 'required',
                "client_id" => "required|integer",
                "description" => "required"
            ]);
            $project->update([
                'client_id' => $request->client_id,
                'name' => $request->name,
                'start_date' => $request->start_date,
                'deadline' => $request->deadline,
                'description' => $request->description,
                "status" => $request->status,
            ]);

            $project->ProjectDepartments()->whereIn('department_id', $project->ProjectDepartments->pluck('department_id')->toArray())->delete();

            // Then, attach selected departments
            foreach ($request->departments as $departmentId) {
                $projectDepartment = new ProjectDepartment();
                $projectDepartment->department_id = $departmentId;
                $project->ProjectDepartments()->save($projectDepartment);
            }


            $project->Projectusers()->whereIn('user_id', $project->Projectusers->pluck('user_id')->toArray())->delete();
            foreach($request->users as $user)
            {
                $UsersProject = new UsersProject();
                $UsersProject->project_id = $project->id;
                $UsersProject->user_id = $user;
                $project->Projectusers()->save($UsersProject);
            }
            return redirect('/project')->with(['success' => 'Project Update Successfull']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('project.index')->with('success', 'Project deleted successfully');

    }





















    public function kanban($id)
    {
       $user = \Auth::user();
       $projectstatus = ProjectStatus::get();
       $project = Project::find($id);
       $projectdeparts =  $project->ProjectDepartments->pluck("department_id");
       $user_depart = $user->UserDepartments->whereIn("department_id",$projectdeparts);
       return view("project.kanban", compact("project", "projectstatus","user_depart"));
    }
    public function projectConversation($id,$department_id)
    {
        $project = Project::findOrfail($id);
        $department = Department::findOrfail($department_id);
        $users = User::all()->pluck("email");
        return view("project.conversation", compact("project","department","users"));
    }


    public function StatusUpdate(request $request)
    {
        //    $user = Auth::user();
        $row = ProjectCurrentStatus::where("department_id", $request->department)
            ->where("project_id", $request->project)->latest()->first();
        if (!$row) {
            ProjectCurrentStatus::create(
                [
                    "status_id" => $request->status,
                    "department_id" => $request->department,
                    "project_id" => $request->project,
                    "user_id" => \Auth::id()
                ]
            );
        } else {
            $row->update([
                "status_id" => $request->status,
                "user_id" => \Auth::id()
            ]);
        }
        return response()->json(["status" => true, "message" => "project Moved Successfully"], 200);
    }





}
