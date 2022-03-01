<?php

namespace App\Http\Middleware;
use App\CompetitorPosition;
use App\Interfaces\ApiInterface;
use App\Position;
use Carbon\Carbon;
use Closure;

class SessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (null == session('available_units') ) {
            $apiInterface = resolve(ApiInterface::class);
            session(['available_units'=> $apiInterface->getUnits() ]);
        }
//dd(999);
        $pendingPositions = Position::with('project.projectRegions')->where([
            ['positions.status' , 'pending'],
            ['positions.updated_at' , '>=', Carbon::yesterday()->startOfDay()]
        ])->get();
        //dd($pendingPositions);
        if (count($pendingPositions) < 0 &&session('selected_project_id')!==null) {
            $apiInterface = resolve(ApiInterface::class);
            //$apiInterface->test();

            $mixed = $apiInterface->getPositionsByTaskIDs($pendingPositions);
            $positions = $mixed['position'];
            $full_url = $mixed['path'];
            //dd($mixed);
            if (is_array($positions) && count($positions) > 0) {
                foreach ($positions as $key => $value) {
                    $tempPosition = Position::find($key);
                    $tempPosition->status = 'complete';
                    $tempPosition->full_url = $full_url[$key];
                    $tempPosition[$tempPosition->method . '_position'] = $value;
                    $tempPosition[$tempPosition->method . '_date'] = Carbon::now();
                    $tempPosition->save();
                }
                session(['available_units'=> $apiInterface->getUnits() ]);
            }

        }
        return $next($request);
    }
}
