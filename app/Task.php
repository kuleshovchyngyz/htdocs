<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['project_id','name',];
    public function schedules()
    {
        return $this->hasMany(Schedule::class,'task_id');
    }
    public function scheduled_positions()
    {
        return $this->hasMany(Scheduled_positions::class,'task_id');
    }
    public function ready_scheduled_position()
    {
        return $this->hasOne(ReadyScheduledPosition::class,'task_id');
    }
    public function project(){
        return $this->belongsTo(Project::class,'project_id','id');
    }
}

