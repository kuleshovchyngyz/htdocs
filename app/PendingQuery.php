<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PendingQuery extends Model
{
        protected $fillable = [
        'method', 'word', 'query_id', 'region_id', 'project_id', 'failed','region','task_table_id'
    ];
}
