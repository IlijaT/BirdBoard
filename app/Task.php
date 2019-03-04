<?php

namespace App;

use App\Project;
use App\Activity;
use App\RecordActivity;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $touches = ['project'];

    protected $casts = ['completed' => 'boolean'];

    public function getDates()
    {
        return ['start', 'end', 'created_at', 'updated_at'];
    }

    protected static $recordableEvents = ['created', 'deleted'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }



    public function path()
    {
        return "/projects/{$this->project->id}/tasks/{$this->id}";
    }


    public function complete()
    {
        $this->update(['completed' => true]);

        $this->recordActivity('completed_task');
    }

    public function incomplete()
    {
        $this->update(['completed' => false]);

        $this->recordActivity('incompleted_task');
    }
}
