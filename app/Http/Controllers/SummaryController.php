<?php

namespace App\Http\Controllers;

use App\Charts;
use App\Position;
use App\Project;
use App\ProjectRegion;
use App\Region;
use App\Schedule;
use App\support\Filter\Filter;
use App\support\Statistics\StatisticService;
use Illuminate\Http\Request;

class SummaryController extends Controller
{

   public function index(Project $project,Request $request)
   {
       session(['selected_project_id' => $project->id]);
       $weekdays = [1=> 'Пн',2=> 'Вт',3=> 'Ср',4=> 'Чт',5=> 'Пт',6=> 'Сб',7=> 'Вс'];
       $tops = ['total_1_3'=>'total_first_1_3','total_1_10'=>'total_first_1_10','total_11_30'=>'total_first_11_30','total_31_50'=>'total_first_31_50','total_51_100'=>'total_first_51_100'];
       $filter = new Filter( $project,$request);
       $filter->set_dates();
       //dump($filter->first_last_position_date('yandex'));
       $dates = $filter->first_last_position_date('yandex');
       $Statistics = [];

       if (isset($request->filter_search_engine) && $request->filter_search_engine != 'all'){
           $method = $request->filter_search_engine;
       }
       $arr = [];
       foreach ($filter->regions as $region){
           foreach (['yandex','google'] as $method){
               $Statistics[$region->id][$method] = new StatisticService($filter->startTime, $filter->endTime, $method,$project->id, $region->id, []);
//               Charts::create(['summary_project_id'=>$project->id, 'summary_start_date'=>$dates[0], 'summary_end_date'=>$dates[1],  'summary_type_widget'=>$Statistics[$region->id][$method], 'summary_search_engine', 'summary_region_id', 'summary_region_name', 'summary_date_get', 'summary_result']);
           }
           $arr[$region->id] = $region->name;
       }

       $schedules = Schedule::where('project_id', session('selected_project_id'))->where('name','!=',null)->where('date','!=','undefined')->get();

       $projectRegions = ProjectRegion::with('region')
         ->where('project_id', session('selected_project_id'))
         ->orderBy('url', 'asc')
         ->paginate(config('app.paginate_by', '25'))->onEachSide(2);

       $charts = Charts::where('summary_project_id',$project->id);

       return view('brief.index',
          ['regions' => $projectRegions,
          'charts' => $charts,
          'schedules'=>$schedules,
          'weekdays'=>$weekdays,
              'stats'=>$Statistics,
              'regions_array'=>$arr,
              'tops'=>$tops
          ]);
   }





   public function store(Request $request, Region $region)
   {


      $summaryDateDiapozoneFrontend = explode('/', $request->summary_date); //Диапозон даты из модального окна
      $summaryTypeWidjetFrontend  = $request->summary_type_widget; //Тип виджета из модального окна
      $summarySearchEngine = $request->summary_search_engine; //Поисковая система  из модального окна
      $summaryRegionId = $request->summary_region_id; //Поисковая система  из модального окна

      $summaryRegionName = Region::where('id', $summaryRegionId)->get();

      $dateGetPosition = Position::where('project_id', session('selected_project_id'))->where('method', $summarySearchEngine)->whereregion_id($summaryRegionId)->whereBetween("{$summarySearchEngine}_date", [$summaryDateDiapozoneFrontend[0], $summaryDateDiapozoneFrontend[1]])->get()->groupBy("{$summarySearchEngine}_date");


      $dateGetPositionAll = $dateGetPosition->keys();

      $middlePositionAll = [];
      foreach ($dateGetPosition as $keyDate => $middlePos) {
         $middlePositionAll[] = round($dateGetPosition[$keyDate]->avg("{$summarySearchEngine}_position"));
      }

      $summaryListDateGetPosition = join(',', $dateGetPositionAll->all());
      $summaryListMiddlePositionAll = implode(',', $middlePositionAll);

      $this->tops(session('selected_project_id'), $summarySearchEngine, $summaryRegionId, $dateGetPositionAll[5]);


      $summaryCharts = [
         'summary_type_widget' => $summaryTypeWidjetFrontend,
         'summary_search_engine' => $summarySearchEngine ? 'Яндекс' : 'Гугл',
         'summary_start_date' => $summaryDateDiapozoneFrontend[0],
         'summary_end_date' => $summaryDateDiapozoneFrontend[1],
         'summary_search_engine' => ($summarySearchEngine == 'yandex') ? 'Яндекс' : 'Гугл',
         'summary_region_id' => $summaryRegionId,
         'summary_region_name' => $summaryRegionId,
         'summary_date_get' => $summaryListDateGetPosition,
         'summary_middle_position' => $summaryListMiddlePositionAll
      ];

      $summaryRegionName = Charts::create($summaryCharts);

      if ($summaryRegionId) {
         $summaryRegionName->region()->attach($summaryRegionId);
      }


      return  redirect()->route('project.brief',  session('selected_project_id'))
         ->with('success', [__('Виджет создан')]);
   }


   public function tops($project_id = 18, $method, $region_id, $date)
   {
      $intervals = [[1, 10], [11, 30], [31, 50], [51, 150], [1, 3]];
      $rates = [];
      $tops = [];
      $quantities = [];


      $method_position = $method . "_position";


      $positions = Position::where("project_id", $project_id)->where("method", $method)
         ->where("region_id", $region_id) //If you want only one region
         ->where($method . "_date", $date)->select("region_id", $method_position, $method . "_date", "query_id")
         ->get()
         ->groupBy(["query_id", "region_id"])
         ->map(function ($value, $key) use ($method, &$quantities) {
            return $value->each(function ($item) use ($method, &$quantities, $key) {

               $quantities[][$item->first()[$method . '_date']] = $item->first()[$method . '_position'];
               $index = count($quantities) - 1;
               $quantities[$index]['query_id'] = $key;
            });
         });

      dump($positions);
      $rates = collect($quantities)->countBy(function ($item) use ($intervals, $date, &$tops) {
         collect($intervals)->map(function ($value) use ($item, $date, &$tops) {
            if (isset($item[$date]) && ($item[$date] >= $value[0] && $item[$date] <= $value[1])) {
               $tops[$date . ' ' . $value[0] . '-' . $value[1]] = $tops[$date . ' ' . $value[0] . '-' . $value[1]] ?? 0;
               $tops[$date . ' ' . $value[0] . '-' . $value[1]] += 1;
            }
         });
      })->all();
      dd($tops[$date . ' 1-10']);
   }
}
