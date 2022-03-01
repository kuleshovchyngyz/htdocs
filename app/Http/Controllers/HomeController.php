<?php

namespace App\Http\Controllers;

use App\Jobs\GetPositions;
use App\Position;
use App\Project;
use App\support\Statistics\StatisticService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function testing($id)
    {
        GetPositions::dispatch([
            'date' => Carbon::now(),
            'text' => $id
        ])->delay(now()->addSeconds(1));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */



    private function getUrlPositionFromResponse($projectUrl, $response)
    {
        $data = ['domain' => '', 'path' => '', 'position' => ''];
        foreach ($response as $key => $value) {
            if (strpos($value->domain, $projectUrl) !== false) {
                $data['domain'] = $value->domain;
                $data['path'] = $value->path;
                $data['position'] = $value->position;
                $data['www'] = $value->www;

                return $data;
            }
        }
        return '-3';
    }



    public function index(Request $request)
    {

        $projects = Project::withAvg('positions:yandex_position')
            ->withAvg('positions:google_position')
            ->withCount('queries')
            ->withMax('positions:created_at')
            ->groupBy('projects.id')
            ->orderBy('positions_created_at_max', 'desc');

        foreach ($projects->get() as $project) {
            if ($project->positions_created_at_max != null) {
                $projectCreatedMaxDate = explode(' ', $project->positions_created_at_max);

                $googlePositionHome = Position::where('method', 'google')
                    ->where('project_id', $project->id)
                    ->where('yandex_date', null)
                    ->where('status', 'complete')
                    ->where('google_date', '<', $projectCreatedMaxDate[0])
                    ->orderBy('google_date', 'DESC')
                    ->first();
                $googlePositionHomeQueries = $project->queries->pluck('id')->toArray();

                if ($googlePositionHome != null) {

//                    $Statistics['google'][$project->id] = new NewStatistic($projectCreatedMaxDate[0], $googlePositionHome->created_at->format('Y-m-d'), 'google', $googlePositionHomeQueries, $project->id);
                    $statistic = new StatisticService( $googlePositionHome->created_at->format('Y-m-d'),$projectCreatedMaxDate[0], 'google',  $project->id,null,$googlePositionHomeQueries);
                    if($statistic->statistics!==null){
                        $Statistics['google'][$project->id] = $statistic;
                    }
                }


                $yandexPositionHome = Position::where('method', 'yandex')
                    ->where('project_id', $project->id)
                    ->where('google_date', null)
                    ->where('status', 'complete')
                    ->where('yandex_date', '<', $projectCreatedMaxDate[0])
                    ->orderBy('yandex_date', 'DESC')
                    ->first();
                $yandexPositionHomeQueries = $project->queries->pluck('id')->toArray();

                if ($yandexPositionHome != null) {
//                    ($startTime, $endTime, $search_engine,$project, $region_id)
//                    $Statistics['yandex'][$project->id] = new NewStatistic($projectCreatedMaxDate[0], $yandexPositionHome->created_at->format('Y-m-d'), 'yandex', $yandexPositionHomeQueries, $project->id);
                    $statistic = new StatisticService($yandexPositionHome->created_at->format('Y-m-d'),$projectCreatedMaxDate[0],  'yandex',  $project->id,null,$yandexPositionHomeQueries);
                    if($statistic->statistics!==null){
                        $Statistics['yandex'][$project->id] = $statistic;
                    }

                }
            }
        }
        //$keys = collect($Statistics['yandex'])->keys()->merge(collect($Statistics['google'])->keys())->unique();
//        $projects= $projects->whereIn('id',$keys)->paginate(config('app.paginate_by', '25'))->onEachSide(2);
        $projects= $projects->paginate(config('app.paginate_by', '25'))->onEachSide(2);

        return view('home', [
            'projects' => $projects,
            'statistics' => $Statistics
        ]);
    }
}
