<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scheduled_positions extends Model
{
    protected $fillable = ['project_id','method','query_id','region','task_id','region_id','word'];
    public function task()
    {
        return $this->belongsTo(Task::class,'task_id','id');
    }
    public function get_region(){
        return $this->belongsTo(Region::class,"region_id","id");
    }
}
