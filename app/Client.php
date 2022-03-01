<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable =[ 'user_id', 'projects' ];
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function project_names(){
        $p_ids = $this->attributes['projects'];
        $p_ids = explode(',', $p_ids);
        $names = "";
        foreach ($p_ids as $p_id){
            $project = Project::find($p_id);
            if ($project){
                $names = $names.$project->name.',';
            }
         }
        return rtrim($names, ", ");

    }
}
