<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CompetitorRegion extends Model
{
    protected $fillable = [
        'region_id', 'competitor_id'
    ];
    public function competitor()
    {
        return $this->belongsTo(Competitor::class);
    }
    public function region()
    {
        return $this->belongsTo(Region::class,"region_id");
    }
}
