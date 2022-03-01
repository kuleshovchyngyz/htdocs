<?php


namespace App\support\Position;


use App\Position;

class Positions
{
    protected $positions;
    public $groupedPositions;
    public $groupedPositionsex;
    public $regions;
    public $queries;
    public $queries_original;
    public $sort_date='null';
    public $sort_type = 'null';
    public $sort_color = 'white';
    public $filter_init = 'main';
    public $filter_competitor = 'self';
    public $method;
    public static  $sort = [
        'asc'=>'sortByAscending',
        'desc'=>'sortByDescending'
    ];
    public function __construct($positions,$regions,$query_group_ids,$queries,$method)
    {
        $this->positions = $positions;
        $this->regions = $regions;
        $this->query_group_ids = $query_group_ids;
        $this->queries = $queries;
        $this->queries_original = $queries;
        $this->method = $method;
    }

    public function split()
    {
        $this->groupedPositions = [];
        $this->groupedPositionsex = [];

        $some = [];
        foreach ($this->positions as $key => $position)
        {
            $queryPosition = new QueryPosition();
            if(!in_array($position->id,$some)){
                $i=0;
                $arrr = [];
                $some[] = $position->id;
                while (true){
                    if(!isset($this->positions[$key+$i])){

                        $queryPosition->setCompetitor($arrr);
                        $seKey = 'yandex';
                        $queryPosition->setRegion($position->region_name);
                        $queryPosition->setUrl($position->full_url);
                        if (is_null($position->yandex_date))
                        {
                            $seKey = 'google';
                            $queryPosition->setPosition($position->google_position);
                        }
                        else
                        {
                            $queryPosition->setPosition($position->yandex_position);
                        }

                        $queryPosition->setPosition_class_name($this->getPositionColorClass($queryPosition->position));
                        $arr = $this->getMatched($position->query_id, $position->full_url, $this->regions->toArray() , $this->query_group_ids, $this->queries->toArray());
                        $queryPosition->setTarget_path($arr["full"]);
                        $queryPosition->setMatched_icon($arr["icon"]);

                        //if($position->progress===null||$position->progress==0){
                        if($position->progress===null){

                            $p = Position::find($position->id);
                            $queryPosition->setChange($p->diff());
                        }else{
                            $queryPosition->setChange($position->progress);
                        }
                        $this->groupedPositions[$position->query_id . '--' . $position->action_date][$seKey][] = $queryPosition;
                        $this->groupedPositionsex[$seKey][$position->action_date][$position->query_id] = $queryPosition;
                        break;
                    }
                    if($position->id == $this->positions[$key+$i]->id && $this->positions[$key+$i]->competitor_id!==null){

                        $arrr[$this->positions[$key+$i]->competitor_id] = [ 'y_p' => $this->positions[$key+$i]->y_p, 'g_p'=> $this->positions[$key+$i]->g_p ];
                    }
                    else{
                        $queryPosition->setCompetitor($arrr);
                        $seKey = 'yandex';
                        $queryPosition->setRegion($position->region_name);
                        $queryPosition->setUrl($position->full_url);
                        if (is_null($position->yandex_date))
                        {
                            $seKey = 'google';
                            $queryPosition->setPosition($position->google_position);
                        }
                        else
                        {
                            $queryPosition->setPosition($position->yandex_position);
                        }
                        $queryPosition->setPosition_class_name($this->getPositionColorClass($queryPosition->position));
                        $arr = $this->getMatched($position->query_id, $position->full_url, $this->regions->toArray() , $this->query_group_ids, $this->queries->toArray());
                        $queryPosition->setTarget_path($arr["full"]);
                        $queryPosition->setMatched_icon($arr["icon"]);
                        //if($position->progress===null||$position->progress==0){
                        if($position->progress===null){

                            $p = Position::find($position->id);
                            $queryPosition->setChange($p->diff());
                        }else{
                            $queryPosition->setChange($position->progress);
                        }

                        $this->groupedPositions[$position->query_id . '--' . $position->action_date][$seKey][] = $queryPosition;
                        $this->groupedPositionsex[$seKey][$position->action_date][$position->query_id] = $queryPosition;
                        break;
                    }
                    $i++;
                }
            }
        }


        return  $this->groupedPositions;
    }
    public function sort(){



        $this->toggle_sort_type();
        if($this->sort_date!='null'&&$this->sort_type!='null'){
            $this->indexify();
            //dd($this->groupedPositionsex[$this->method]);
            //2021-08-25
            $s =  collect($this->groupedPositionsex[$this->method][$this->sort_date]);
            uasort($this->groupedPositionsex[$this->method][$this->sort_date], array($this,self::$sort[$this->sort_type]));
            //dump($this->groupedPositionsex[$this->method][$this->sort_date]);
            $arr =  (array_keys($this->groupedPositionsex[$this->method][$this->sort_date]));
            $properOrderedArrayueries = collect(array_replace(array_flip($arr), $this->queries));
            $this->queries = $properOrderedArrayueries;
        }
    }
    private function toggle_sort_type(){

        if($this->sort_type=='desc'){
            $this->sort_type = 'asc';
            $this->sort_color = 'red';
        }
        else if($this->sort_type == 'asc'){
            $this->sort_type = 'null';
            $this->sort_color = 'white';
        }
        else if($this->sort_type=='null')
        {
            $this->sort_type = 'desc';
            $this->sort_color = 'green';
        }

    }
    public function indexify(){
        $arr = [];
        foreach ($this->queries as $key =>$query){
            $arr[$query->query_id] = $query;
        }
        $this->queries = ($arr);
    }

     function sortByDescending($param1, $param2) {
         return ($param1->position < $param2->position) ? -1 : (($param1->position > $param2->position) ? 1 : 0);
        return strcmp($param1->position, $param2->position);
    }
     function sortByAscending($param1, $param2) {
         return ($param1->position > $param2->position) ? -1 : (($param1->position < $param2->position) ? 1 : 0);
        return strcmp($param1->position, $param2->position);
    }

    public function ex(){
        $this->sort();
        return $this->groupedPositionsex;
    }
    public function remove_slashes($target)
    {
        if ($target == "")
        {
            return "";
        }

        $pattern_start = "/^\/+/";
        $pattern_end = "/\/+$/";
        if (preg_match($pattern_start, $target))
        {
            $target = preg_split($pattern_start, $target);
            $target = $target[1];
        }
        if (preg_match($pattern_end, $target))
        {
            $target = preg_split($pattern_end, $target);
            $target = $target[0];
        }
        return $target;
    }
    public function getMatched($query_id, $full_url, $project_region, $query_groups, $queries)
    {

        $arr = array();
        foreach ($query_groups as $key => $value)
        {
            foreach ($queries as $query)
            {
                if ($query->query_id == $query_id && $query->query_group_id == $value['id'])
                {

                    $target = $this->remove_slashes($project_region[0]['url']);

                    $full = $target . '/' . $value['target_path'];

                    $full = $this->remove_slashes($full);

                    //return $full;
                    $full = str_replace("https://", "", $full, $count);
                    $full = str_replace("http://", "", $full, $count);
                    $full = str_replace("www.", "", $full, $count);

                    $full_url = str_replace("https://", "", $full_url, $count);
                    $full_url = str_replace("http://", "", $full_url, $count);
                    $full_url = str_replace("www.", "", $full_url, $count);

                    $full_url = $this->remove_slashes($full_url);
                    $arr["full"] = $full;
                    $arr["full_url"] = $full_url;

                    if ($full_url == $full)
                    {
                        $arr["icon"] = "fa-check-circle";
                    }
                    else
                    {
                        $arr["icon"] = "fa-times-circle";
                    }
                    //dd($arr);
                    return $arr;
                    //return $full ;//== $full ? "true" : "false";
                    //return $full;

                }
            }
        }
    }
    private function getPositionColorClass($position)
    {
        if ($position == '' || $position < 1 || $position == 100)
        {
            return 'position-container__null';
        }
        else if ($position >= 1 && $position <= 3)
        {
            return 'position-container__1-3';
        }
        else if ($position <= 10)
        {
            return 'position-container__4-10';
        }
        if ($position <= 20)
        {
            return 'position-container__11-20';
        }
        if ($position <= 40)
        {
            return 'position-container__21-40';
        }
        if ($position <= 60)
        {
            return 'position-container__41-60';
        }
        if ($position <= 80)
        {
            return 'position-container__61-80';
        }
        if ($position < 100)
        {
            return 'position-container__81-100';
        }
        else
        {
            return 'position-container__100plus';
        }
    }
}
