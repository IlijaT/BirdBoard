<?php

namespace App\Http\Controllers;

use App\Task;
use App\Project;
use Illuminate\Http\Request;

class ProjectTasksController extends Controller
{
    public function store(Project $project)
    {
        request()->validate(
            ['body' => 'required|max:190']
        );
        $project->addTask(request('body'));

        return redirect($project->path())->with('flash', [
            'message' => 'A new task has been created!',
            'color' => 'green'
            ]);
    }



    public function update(Project $project, Task $task)
    {
        $attributes = request()->validate(
            ['body' => 'required|max:190']
        );
        
        $task->update($attributes);

        if (request('completed')) {
            $task->complete();
        } else {
            $task->incomplete();
        }

        return redirect($project->path())->with('flash', [
            'message' => 'The task has been updated!', 
            'color' => 'green'
            ]);
    }
}
