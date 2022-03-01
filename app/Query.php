<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
	public $timestamps = true;

	protected $fillable = [
        'name', 'is_active', 'query_group_id', 'region_id'
    ];

    public function group()
    {
    	return $this->belongsTo('App\QueryGroup', 'query_group_id');
    }

    public function region()
    {
    	return $this->belongsTo('App\Region', 'region_id');
    }
    public function querygroup(){
        return $this->belongsTo(QueryGroup::class,'query_group_id','id');
    }
}
