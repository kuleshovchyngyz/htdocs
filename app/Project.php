<?php

namespace App;

use Alexmg86\LaravelSubQuery\Traits\LaravelSubQueryTrait;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use LaravelSubQueryTrait;

    //
    protected $fillable = [
        'name', 'url', 'description', 'is_active', 'query_group_id', 'region_id', 'user_id', 'manager_id'
    ];

    public function queries()
    {
        return $this->hasManyThrough('App\Query', 'App\QueryGroup');
    }

    public function queryGroup()
    {
        // return $this->hasOne('App\Phone', 'foreign_key', 'local_key');
        // comment return $this->belongsTo('App\Post');
        return $this->hasOne('App\QueryGroup', 'id', 'query_group_id');
    }

    public function region()
    {
        // return $this->hasOne('App\Phone', 'foreign_key', 'local_key');
        // comment return $this->belongsTo('App\Post');
        return $this->hasOne('App\QueryGroup', 'id', 'region_id');
    }

    public function client()
    {
        // return $this->hasOne('App\Phone', 'foreign_key', 'local_key');
        // comment return $this->belongsTo('App\Post');
        return $this->hasOne('App\QueryGroup', 'id', 'client_id');
    }

    public function manager()
    {
        // return $this->hasOne('App\Phone', 'foreign_key', 'local_key');
        // comment return $this->belongsTo('App\Post');
        return $this->hasOne('App\QueryGroup', 'id', 'manager_id');
    }


    public function projectRegions()
    {
        return $this->hasMany('App\ProjectRegion');
    }

    public function positions()
    {
        return $this->hasMany('App\Position');
    }
    public function competitors()
    {
        return $this->hasMany(Competitor::class);
    }
    public function projectsubdomain($region_id)
    {
        foreach ($this->projectRegions as $p) {
            if ($p->region_id == $region_id) {
                return $p->url;
            }
        }
        return $this->name;
    }
    public function countregion($id)
    {
        $i = 0;
        $r = $this->competitors;
        foreach ($r as $n) {
            foreach ($n->competitorregions as $regions) {
                if ($regions->region_id == $id) {
                    $i++;
                }
            }
        }
        return $i;
    }
    public function client_($id)
    {

        $client = Client::find($id);
        $p = explode(',', $client->projects);
        //return $p ;
        if (in_array($this->attributes['id'], $p)) {
            return true;
        }
        return false;
    }
    public function has_client($id)
    {
        $client = Client::find($id);
        if ($client !== null) {
            $projects = explode(',', $client->projects);

            if (in_array($this->attributes['id'], $projects)) {
                return true;
            }
        }
        return false;
    }
}
