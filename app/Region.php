<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'api', 'name', 'country', 'region', 'yandex_index', 'google_index'
    ];
    public function competitorregion()
    {
        return $this->belongsTo(CompetitorRegion::class);
    }
    public function charts()
    {
        return $this->belongsToMany(Charts::class, 'chart_region', 'region_id', 'chart_id');
    }
}
