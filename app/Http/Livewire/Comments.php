<?php

namespace App\Http\Livewire;

use App\Models\Comment;
use App\Models\Notification;
use Livewire\Component;

class Comments extends Component
{

    public $project;
    public $comment;
    public $department;
    public $project_id;
    public $perPage = 5;
    public $showMoreButton = true;


    protected $listeners = ['updateComponentB', 'commentUpdated' => 'commentUpdated',];


    public function commentUpdated($comment)
    {
        $this->comment = $comment;
    }

    public function render()
    {
            $comments = Comment::where("project_id", $this->project_id)->where("department_id", $this->department)->orderBy("id", "desc")->paginate($this->perPage);
        return view('livewire.comments', compact("comments"));
    }
    public function mount($project, $department)
    {
        $this->project_id = $project->id;
        $this->department = $department->id;
        Notification::where("project_id",$project->id)->where("department_id",$department->id)->where("user_id",\Auth::id())->update(["read"=>true]);
    }
    public function updateComponentB()
    {
        $comments = \Auth::user()->comments()->where("project_id", $this->project_id)->where("department_id", $this->department)->get();
        $this->emit('$refresh');
    }

    public function loadMore()
    {
        $this->perPage += 5; // Increase the number of comments to load
        $comments = Comment::where("project_id", $this->project_id)->where("department_id", $this->department)->orderBy('id', 'desc')->paginate($this->perPage);
        $this->showMoreButton = $comments->hasMorePages(); // Show the "Load More" button again
    }

    public function loadAll()
    {
        $this->perPage = Comment::count(); // Set perPage to total comment count
        $this->showMoreButton = false; // Hide the "Load More" button
    }

    public function deleteConversation($id)
    {
        $Comment = Comment::find($id);
        if ($Comment) {
            $Comment->delete();
            // Optionally, you can emit an event to notify other components
        }
    }
}
