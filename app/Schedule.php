<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ["project_id","type","date","status","time","project_id","task_id",'name'];
    public function task()
    {
        return $this->belongsTo(Task::class,'task_id','id');
    }
    public function project(){
        return $this->belongsTo(Project::class,"project_id","id");
    }
}
