<?php

namespace App\Jobs;

use App\Interfaces\ApiInterface;
use App\Position;
use App\ProjectRegion;
use App\ReadyScheduledPosition;
use App\Region;
use App\Schedule;
use App\Test;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use function GuzzleHttp\Promise\all;

class GetTaskids implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param $data

     */

    protected $data;


    /**
     * Create a new job instance.
     *
     * @param $data
     * @param $make_ready
     */
    public function __construct($data)
    {
        $this->data = $data;

    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
 		$this->getAllPositions($this->data);
    }

    public function getAllPositions($data)
    {
		$ApiInterface = resolve(ApiInterface::class);
        $taskIDs = $ApiInterface->getTaskIDs( [ $data ]);
        if ( is_array($taskIDs) && count($taskIDs) > 0 ) {
            $positions = array_map(function($taskID) use ($data) {
                return [
                    'project_id' => $data['project_id'],
                    'region_id' => $data['region_id'],
                    'query_id' => $data['query_id'],
                    'task_id' => $taskID->task_id,
                    'status' => 'pending',
                    'task_table_id' => $data['task_table_id'],
                    'method' => $taskID->method,
                    'created_at'    => Carbon::now(),
                    'updated_at'   => Carbon::now()
                ];
            }, $taskIDs);

            $res = Position::insert($positions);
        }
        return 1;
    }
    public function where($array,$var,$type)
    {
        if($type=="query_name"){
            foreach ( $array as $item) {
                if($item->query_name==$var){
                    return $item->query_id;
                }
            }
        }else if(substr($type,-5)=='index'){
            foreach ($array as $item) {
                if($item->$type==$var){
                    return $item->region_id;
                }
            }
        }

    }

}
