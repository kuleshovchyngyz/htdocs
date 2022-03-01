<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Charts extends Model
{

    protected $fillable = [
        'summary_project_id', 'summary_start_date', 'summary_end_date',  'summary_type_widget', 'summary_search_engine', 'summary_region_id', 'summary_region_name', 'summary_date_get', 'summary_result'
    ];
    public function region()
    {
        return $this->belongsToMany(Region::class, 'chart_region', 'chart_id', 'region_id');
    }
}
