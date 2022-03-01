<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'yandex_position', 'yandex_date', 'google_position', 'google_date', 'full_url', 'project_id', 'region_id', 'query_id','checked','progress'
    ];
    public function region()
    {
        return $this->belongsTo('App\Region', 'region_id');
    }
    public function project()
    {
        return $this->belongsTo('App\Project', 'project_id');
    }
    public function projection()
    {
        return $this->belongsToMany ('App\ProjectRegion', 'project_id');
    }
    public function searchQuery()
    {
        return $this->belongsTo('App\Query', 'query_id');
    }
    public function competitorpositions()
    {
        return $this->hasMany(CompetitorPosition::class);
    }
    public function diff(){

        if($this->attributes['yandex_date']==null){
            $p = Position::where('region_id',$this->attributes['region_id'])
                ->where('method',$this->attributes['method'])
                ->where('query_id',$this->attributes['query_id'])
                ->where('project_id',$this->attributes['project_id'])
                ->where('yandex_date',null)
                ->where('status','complete')
                ->where('google_date','<',$this->attributes['google_date'])
                ->orderBy('google_date','DESC')
                ->first();


            if($p){
                $p = ($p->google_position < 0 ) ? 100 : $p->google_position;
            }else{
                $p = ($p) ? $p->google_position : $this->attributes['google_position'];
            }
            $cur_pos = ($this->attributes['google_position'] < 0 ) ? 100 : $this->attributes['google_position'];
            $this->progress = $p - $cur_pos;

            $this->save();
            return $p - $cur_pos;
        }

        if($this->attributes['google_date']==null){
            $p = Position::where('region_id',$this->attributes['region_id'])
                ->where('method',$this->attributes['method'])
                ->where('query_id',$this->attributes['query_id'])
                ->where('project_id',$this->attributes['project_id'])
                ->where('google_date',null)
                ->where('status','complete')
                ->where('yandex_date','<',$this->attributes['yandex_date'])
                ->orderBy('yandex_date','DESC')
                ->first();
            if($p){
                $p = ($p->yandex_position < 0 ) ? 100 : $p->yandex_position;
            }else{
                $p = ($p) ? $p->yandex_position : $this->attributes['yandex_position'];
            }
            $cur_pos = ($this->attributes['yandex_position'] < 0 ) ? 100 : $this->attributes['yandex_position'];

            $this->progress = $p - $cur_pos;
            $this->save();
            return $p - $cur_pos;
        }
        return 0;
    }


}
