<?php

namespace App\Jobs;

use App\Interfaces\ApiInterface;
use App\PendingQuery;
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

class GetPositions  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;


    /**
     * Create a new job instance.
     *
     * @param $data
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
        $this->getPositions($this->data);
    }
    public function getPositions($pendingPositions)
    {
        $apiInterface = resolve(ApiInterface::class);
        if (count($pendingPositions) > 0) {
            $mixed = $apiInterface->getPositionsByTaskIDs($pendingPositions);
            $positions = $mixed['position'];
            $full_url = $mixed['path'];
            if (is_array($positions) && count($positions) > 0) {
                foreach ($positions as $key => $value) {
                    $tempPosition = Position::find($key);
                    $tempPosition->status = 'complete';
                    $tempPosition->full_url = $full_url[$key];
                    $tempPosition[$tempPosition->method . '_position'] = $value;
                    $tempPosition[$tempPosition->method . '_date'] = Carbon::now();
                    //                    $tempPosition[$tempPosition->method . '_date'] = Carbon::yesterday();
                    $tempPosition->save();
                    $tempPosition = Position::find($key);
                    $this->deletePending($tempPosition);
                    $tempPosition->diff();
                }

                session(['available_units' => $apiInterface->getUnits()]);
            }
        }
    }
    public function deletePending($tempPosition)
    {
        PendingQuery::where('query_id', $tempPosition->query_id)->where('region_id', $tempPosition->region_id)->where('project_id', $tempPosition->project_id)->where('method', $tempPosition->method)->delete();
    }
}
