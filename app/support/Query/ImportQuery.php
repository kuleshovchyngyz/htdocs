<?php

namespace App\support\Query;
use App\ProjectRegion;
use App\QueryGroup;
use App\Region;

class ImportQuery
{
    protected $projectRegions;
    protected $fileRegions;
    protected $fileGroups;
    protected $projectGroups;
    protected $readyQueries;
    private $projectQueries;
    protected $absentRegions;
    protected $regionNames;
    protected $filtered;
    protected $somefolder;

    private $queries = array();

    private $headers = array();
    private $headersIndexes = array();
    protected $filename;
    protected $folders = array();
    protected $queryGroup;
    protected $projectId;
    protected $tree = array();
    protected $father = array();
    protected $queryGroupIds = array();
    public $numberOfQueries = 0;
    public $numberOfGroups = 0;
    public $some ;
    public $notIportedByRegions ;
    public function __construct($filename){
        $this->send_to_tg_bot();
        $this->projectId =  session('selected_project_id');
        $this->projectRegions = Region::whereIn('id',ProjectRegion::where('project_id',$this->projectId)->pluck('region_id')->toArray())->pluck('name','id');
        $this->filename = $filename;
        $this->queryGroup = QueryGroup::where('project_id',$this->projectId)->get();
        $this->projectQueries = \App\Query::whereIn('query_group_id',$this->queryGroup->pluck('id'))->get();
        $this->readFile();
        $this->regionNames = Region::whereIn('name',$this->fileRegions)->pluck('id','name')->toArray();
        $this->fileRegions = collect($this->fileRegions)->unique()->toArray();
        $this->absentRegions = collect($this->fileRegions)->diff(collect($this->projectRegions))->toArray();
        $this->absentRegions = array_filter($this->absentRegions, function($value) { return $value !== ''; });
        $this->filtered = collect($this->queries)->filter(function ($value) {
            return $this->projectRegions->contains($value->getRegion()) || $value->getRegion()==='';
        });
        $this->notIportedByRegions = collect($this->queries)->filter(function ($value) {
            return !$this->projectRegions->contains($value->getRegion()) && $value->getRegion()!=='';
        });
        $this->notIportedByRegions = $this->notIportedByRegions->toArray();

        $this->folders = collect($this->folders)->unique();

        //$this->numberOfGroups = count($this->queryGroupIds);
        $this->makeGroupTree();
        $this->sortQueriesByRegion();
        $this->create();

        $this->numberOfQueries = count($this->readyQueries);
        //dump($this->queryGroup->toArray());
        //dump($this->queryGroup->where('name','Шкафы1')->first()->id);
//
    }
    public function makeGroupTree(){

        $arr = $this->queryGroup->toArray();
       // dump($arr);
        $a = array();
        foreach ($arr as $item){
            $a[$item['id']] = $item;
        }
        $this->some = $a;

        //dd($this->some);

       // dump($a);
        $r = array();
        foreach ($this->some as $key=>$item){
            //dump($item['name']);
            $r[$key] = $this->returnFather($key,$item['name']);
        }
        $this->projectGroups = array_flip($r);
        //dump($r);
    }

    public function returnFather($key,$str){
        if(!isset($this->some[$key])){
            //dd($key);
          //  dd();
        }
        if( $this->some[$key]['parent_group_id']==0){

            return $this->some[$key]['name']==$str ? $str : $this->some[$key]['name'].'/'.$str;
        }else{
            return $this->returnFather($this->some[$key]['parent_group_id'],$this->some[$key]['name']==$str ? $str : $this->some[$key]['name'].'/'.$str);
        }

    }
    public function create(){
        $this->saveData();
//
    }
    public function sortQueriesByRegion(){

        $s = array();
        $m = array();
        dump(count($this->filtered));
        foreach ($this->filtered as $key=>$item){
            $s[$key] = $item->name;
        }
        foreach ($this->projectQueries as $key=>$item){
            $m[$key] = $item->name;
        }
       // dump($this->projectQueries);
        $this->numberOfQueries = count($this->filtered);

        $diff = collect($s)->intersect (collect($m));
//        foreach ($diff->all() as $key=>$item){
//            dump($filtered[$key]);
//        }

        $diff = collect($s)->diff (collect($m));
        dump('filtered');


        $this->readyQueries = $diff->all();

        foreach ($this->readyQueries as $key=>$item){
            $this->filtered[$key]->groupId = isset($this->projectGroups[$this->filtered[$key]->group_folder_path])
                ? $this->projectGroups[$this->filtered[$key]->group_folder_path] : 'none';
            if(isset($this->projectGroups[$this->filtered[$key]->group_folder_path]) ){
                $this->filtered[$key]->groupId = $this->projectGroups[$this->filtered[$key]->group_folder_path];
            }else{
                $this->filtered[$key]->groupId = 'none';
                $this->somefolder[] = $this->filtered[$key]->group_folder_path;
            }
            $this->readyQueries[$key] = $this->filtered[$key];
        }
        $this->somefolder = collect( $this->somefolder)->unique()->toArray();
        $queriesFile = array();
        $queriesProject = array();
        foreach ($diff->all() as $key=>$item){
            $queriesFile[$this->filtered[$key]->name] = $this->filtered[$key]->group_name;
            //dump($this->filtered[$key]);
        }


        // dd(1);
        // dump(collect($this->queries)->diffAssoc($filtered));
//        $filtereds = collect($this->queries)->filter(function ($value) {
//            return  $this->projectRegions->contains($value->getRegion()) || $value->getRegion()=='';
//        });
        // dump($filtered);
        // dump($this->projectRegions);
    }

    public function saveData(){

        $this->createFathers();
        $this->linkFathers();
        //dump($this->tree);

        $arr = array();
        foreach ($this->readyQueries as $item){
            $arr[]= $item->name;
        }
        $this->regionNames[''] = 0;
        foreach ($this->tree as $key=>$item){
            foreach ($item['key'] as $query_id){
                if(in_array($this->queries[$query_id]->getName(),$arr)){
                   // dd('in save data');
                    $this->fileGroups[] = $this->queries[$query_id]->getRegion();
                    \App\Query::create([
                        'name'=>$this->queries[$query_id]->getName(),
                        'query_group_id'=>$item['child'],
                        'region_id'=>$this->regionNames[$this->queries[$query_id]->getRegion()]
                    ]);
                }
            }
        }
        $this->fileGroups = collect($this->fileGroups)->unique();
    }
    public function linkFathers(){
        foreach ($this->tree as $key=>$tree){
            $fatherResult = ($tree['child']==$tree['father']) ? 0 : array_search($tree['father'],$this->queryGroupIds);
            $this->tree[$key]['father'] = ($fatherResult===false) ? $this->createQueryGroup($tree['father']) : $fatherResult;
            if($fatherResult===false)
                $this->queryGroupIds[$this->tree[$key]['father']] = $tree['father'];

            $childResult = array_search($key,$this->queryGroupIds);
            $this->tree[$key]['child'] = ($childResult==false) ? $this->createQueryGroup($tree['child'],$fatherResult) : $childResult;
            $qgroup = QueryGroup::find($this->tree[$key]['child']);
            $qgroup->parent_group_id = $this->tree[$key]['father'];
            if($tree['target']!='')$qgroup->target_path = $tree['target'];
            $qgroup->save();
        }
    }
    function send_to_tg_bot(){

        $url = 'https://t.kuleshov.studio/api/getmessages';

        //companycode - Индивидуальный код организации (получить у администратора)
        $data = ["companycode" => "co78c6c316db063", "data" => [["message" => 'Otpravleno']]];

        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        return true;
    }
    public function createFathers(){

        $arr = array();
        dd($this->readyQueries);
        foreach ($this->readyQueries as $item){
            $arr[] = $item->group_name;
        }
//        dump(array_unique($arr));
//        dd($this->folders );
        foreach ($this->folders as $folder){
            $var = explode('/',$folder);
            $a = $this->queryGroup->where('project_id',$this->projectId)->where('name',end($var));
            if($a->count()>0){
                $this->queryGroupIds[$a->first()->id] = $folder;
            }else{
                if(in_array(end($var),$arr)){
                    $q = QueryGroup::create([
                        'name'=>end($var),
                        'project_id'=>$this->projectId
                    ]);
                    $this->queryGroupIds[$q->id] = $folder;
                    $this->numberOfGroups += 1;
                }
            }
        }
    }
    public function father($str){
        $var = explode('/',$str);
        if(count($var)==1){
            return $str;
        }
        return str_replace('/'.end($var), "", $str );
    }

    public function makeTree($f,$child,$key = null, $target = '',$region=0){
        $end = array();
        $temp =  explode('/',$f);
        $t = end($temp);
        $end['child'] = $t;
        $end['father'] = $this->father($f);
        $end['target'] = $target;
        $end['key'][] = $key;
        return $end;
    }
    public function createQueryGroup($name,$parent=0,$target='',$region=0){
        $queryGroup = QueryGroup::where('name',$name)->where('project_id',$this->projectId)->first();
        if($queryGroup){
            $queryGroup = QueryGroup::where('name',$name)->where('project_id',$this->projectId)->first();
            if($target!='')$queryGroup->target_path = $target;
            $queryGroup->parent_group_id = $parent;
            $queryGroup->save();
            return $queryGroup->id;
        }else{
            $queryGroup = new QueryGroup;
            $queryGroup->project_id = $this->projectId;
            if($target!='')$queryGroup->target_path = $target;
            $queryGroup->name = $name;
            $queryGroup->parent_group_id = $parent;
            $queryGroup->region_id = $region;
            $queryGroup->save();
            return $queryGroup->id;

        }
    }
    public function updateQueryGroup($name,$parent,$target){
        $queryGroup = QueryGroup::where('name',$name)->where('project_id',$this->projectId)->first();
        $queryGroup->target_path = $target;
        $queryGroup->parent_group_id = $parent;
        $queryGroup->save();
        return $queryGroup->id;
    }


    public function readFile(){
        $row = 1;
        if (($handle = fopen(public_path('imports/'.$this->filename), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                if($row==1){
                    //$str = iconv('Windows-1251', 'UTF-8', $data[0]);
                    $str = $data[0];
                    $this->headers = explode(';',$str);
                    $this->indexHeader();
                   // dump($this->headersIndexes);
                }else if($row!=2){
                    $str = iconv('Windows-1251', 'UTF-8', $data[0]);
//                    $str = iconv('UTF-8', 'windows-1252', $data[0]);
                    //$str = mb_convert_encoding($data[0], 'UTF-8', 'auto');
                    $headers = explode(';',$str);
                    $this->createObject($headers);
                }
                $row++;
            }
            fclose($handle);
        }
    }
    public function indexHeader(){
        foreach ($this->headers as $key=> $header){
            if($header == 'name') $this->headersIndexes['name'] = $key;
            if($header == 'target') $this->headersIndexes['target'] = $key;
            if($header == 'group_name') $this->headersIndexes['group_name'] = $key;
            if($header == 'group_folder_path') $this->headersIndexes['group_folder_path'] = $key;
            if($header == 'region') $this->headersIndexes['region'] = $key;
        }

    }
    public function createObject($arr = array()){
        $q = new Query();
        $q->setName( $arr[$this->headersIndexes['name']]);
        $q->setTarget($arr[$this->headersIndexes['target']]);
        $q->setGroupName($arr[ $this->headersIndexes['group_name']]);
        $q->setGroupFolderPath($arr[$this->headersIndexes['group_folder_path']]);
        $q->setRegion( $arr[$this->headersIndexes['region']]);

        $this->fileRegions[] = trim($arr[$this->headersIndexes['region']]);
        $var = $q->getGroupFolderPath();
        $child = $q->getGroupName();

        $a = explode('/', $var);
        if(end($a)!=$child){
            $this->folders[] = $var.'/'.$child;
        }else{
            $this->folders[] = $var;
        }
        $var = end($this->folders);
        $this->queries[] = $q;
        if (array_key_exists($var, $this->tree)) {
            $this->tree[$var]['key'][] = array_key_last($this->queries);
        }else{
            $this->tree[$var] = $this->makeTree($var,$child ,array_key_last($this->queries), $arr[$this->headersIndexes['target']]);
        }
    }

}
