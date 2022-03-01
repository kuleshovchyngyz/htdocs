<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReadyScheduledPosition extends Model
{
    protected $fillable = ['action','filter','google','query_group','yandex','project_id','task_id'];
    public function get_region(){


        $arr = array();
        $project_regions = array();
        if($this->attributes['action']=='all'){
            ProjectRegion::where('project_id',$this->attributes['project_id'])->pluck('region_id')->all();
            $project_regions = ProjectRegion::where('project_id',$this->attributes['project_id'])->pluck('region_id')->all();
            foreach ($project_regions as $region){
                $arr[] = array('google'=>Region::where('id',$region)->first());
            }
            foreach ($project_regions as $region){
                $arr[] = array('yandex'=>Region::where('id',$region)->first());
            }
        }
        if($this->attributes['action']=='selected'){
            if($this->attributes['google']!=''){
                foreach (explode(",", $this->attributes['google']) as $region){
                    $arr[] = array('google'=>Region::where('id',$region)->first());
                }
            }
            if($this->attributes['yandex']!=''){
                foreach (explode(",", $this->attributes['yandex']) as $region){
                    $arr[] = array('yandex'=>Region::where('id',$region)->first());
                }
            }
        }


        return $arr;
        //return $this->belongsTo(Region::class,"region_id","id");
    }
    public function task()
    {
        return $this->belongsTo(Task::class,'task_id','id');
    }
}
