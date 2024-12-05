<?php

namespace App\Http\Livewire;

use App\Models\Project;
use Livewire\Component;
use App\Models\Notification;

class CommentForm extends Component
{


    public $project;
    public $comment;
    public $department = 0;
    public $project_id  = 0;
    public $users;

    public function render()
    {
        return view('livewire.comment-form');
    }

    protected function rules()
    {
        return [
            'comment' => 'nullable',
        ];
    }
    public function saveContact()
    {
        $validateData = $this->validate();
        $project = Project::find($this->project_id);
        $AssignUser = $project->Projectusers;
        $commentresp = \Auth::user()->comments()->create(
            [
                "comment" => $validateData["comment"],
                "project_id" => $this->project_id,
                "department_id" => $this->department,
            ]
        );
        foreach ($AssignUser as  $val) {
            Notification::create([
                "comment_id" => $commentresp->id,
                "project_id" => $this->project_id,
                "department_id" => $this->department,
                "is_comment" => true,
                "user_id" => $val->user_id
            ]);
        }
        $this->emit('updateComponentB');
    }

    public function mount($project,$department,$users)
    {
         $this->project_id = $project->id;
         $this->department = $department->id;
         $this->users = $users;
    }

    public function refreshComments()
    {
        // dd("asdasads");
        $this->emit('updateComponentB');
    }
}
