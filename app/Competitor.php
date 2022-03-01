<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competitor extends Model
{
    protected $fillable = [
        'name', 'project_id','url','is_active'
    ];
    public function competitorregions()
    {
        return $this->hasMany(CompetitorRegion::class)->orderBy('region_id', 'asc');;
    }
    public function competitorpositions()
    {
        return $this->hasMany(CompetitorPosition::class);
    }
    public function hasdate($date){
        $d = $this->competitorpositions()->whereDate('created_at',$date)->first();
        if($d){
            return true;
        }
        return false;
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id');
    }
    public function hasregion($id)
    {
        $regions = $this->competitorregions;
        foreach($regions as $region){
            if($region->region_id==$id){
                return true;
            }
        }
        return false;

    }
}
