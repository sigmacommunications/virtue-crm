<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function client()
    {
        return $this->hasOne(\App\Models\Client::class,'id','client_id');
    }

    public function ProjectDepartments()
    {
        return $this->hasMany(ProjectDepartment::class, 'project_id', 'id');
    }
    public function ProjectCurrentStatus()
    {
        return $this->hasMany(ProjectCurrentStatus::class, 'project_id', 'id');
    }
    public function Projectusers()
    {
        return $this->hasMany(UsersProject::class, 'project_id', 'id');
    }
    public function Notifications()
    {
        return $this->hasMany(Notification::class, 'project_id', 'id')->where("user_id",\Auth::id());
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'project_id', 'id');
    }
}
