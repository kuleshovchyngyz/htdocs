<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitorPosition extends Model
{
    protected $fillable = [
        'competitor_id', 'position_id','query_id','region_id','google_position','google_date','yandex_position','yandex_date','full_url','task_id','method','project_id'
    ];
    public function competitor()
    {

        return $this->belongsTo(Competitor::class);
    }
    public function position()
    {
        return $this->belongsTo(Position::class,'position_id');
    }
}
