<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectRegion extends Model

{
    public $table = "project_regions";
    protected $fillable = ['project_id', 'region_id', 'url', 'is_active'];

    public function region()
    {
    	return $this->belongsTo('App\Region', 'region_id');
    }
    public function project()
    {
    	return $this->belongsTo('App\Project', 'project_id');
    }

}
