<?php

    use App\Client;use App\Position;
	   function send_to_tg_bot($message,$from){

        $message1 = 'Снятие  <strong>"'.$from.'"</strong>:'. PHP_EOL;


        $message1 .= $message ;



        //companycode - Индивидуальный код организации (получить у администратора)
        $data = ["companycode" => "co19a1ddfa37041", "data" => [["message" => $message1]]];
        $url = 'https://t.kuleshov.studio/api/getmessages';
        $data_string = json_encode($data);

        $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        return true;
    }
	function change($var){
        if($var>0){
            return 'green';
        }else if ($var<0){
            return 'red';
        }
        else{
            return 'none';
        }
    }
    function arrow_change($var){
        if($var>0){
            return 'fa-arrow-up';
        }else if ($var<0){
            return 'fa-arrow-down';
        }

    }
    function has_clientby($p_id,$id){
        $p_id = \App\Project::find($p_id);
        $client = \App\Client::find($id);
        if($client!==null){
            $projects = explode(',',$client->projects);

            if(in_array($p_id->id,$projects)){
                return true;
            }
        }
    return false;
    }

    function max_by_date($date_arr){
        if(!$date_arr){
            return '';
        }
        usort($date_arr, function($a, $b) {
            $dateTimestamp1 = strtotime($a);
            $dateTimestamp2 = strtotime($b);

            return $dateTimestamp1 < $dateTimestamp2 ? -1: 1;
        });
        return $date_arr[count($date_arr) - 1];
    }
    function object_to_array($arr,$str )
    {
        if(!$arr){
            return [];
        }
        $n = array();
        foreach ($arr as $k => $a){
            $n[] = $a->$str;
        }
        return $n;
    }
    function getPositionColorClass($position) {
        if ($position == '' || $position < 1 || $position == 100) {
            return 'position-container__null';
        }
        else if ($position >= 1 && $position <= 3) {
            return 'position-container__1-3';
        }
        else if ($position <= 10) {
            return 'position-container__4-10';
        }
        if ($position <= 20) {
            return 'position-container__11-20';
        }
        if ($position <= 40) {
            return 'position-container__21-40';
        }
        if ($position <= 60) {
            return 'position-container__41-60';
        }
        if ($position <= 80) {
            return 'position-container__61-80';
        }
        if ($position < 100) {
            return 'position-container__81-100';
        }
        else {
            return 'position-container__100plus';
        }
    }
    function exist_in($owned_urls,$string){

        foreach ($owned_urls as $url) {
            //if (strstr($string, $url)) { // mine version
            if (strpos($string, $url) !== FALSE) { // Yoshi version

                return true;
            }
        }

        return false;
    }
    function get_days_from_date($request=[]){
        $str = '';
        $arr = explode(",",$request);
        foreach ($arr as $key => $a){
            if($key == 0){
                $day = explode('-',$a);
                $str = $day[2];
            }else{
                $day = explode('-',$a);
                $str = $str.','.$day[2];
            }
        }
        return $str;
    }
    function remove_slashes_w($target)
    {
        if($target==""){
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
        $target = str_replace("https://", "", $target , $count);
        $target = str_replace("http://", "", $target , $count);
        $target = str_replace("www.", "", $target , $count);
        return $target;
    }
    function mkmk($id){
        $positions = \App\Position::find($id)->first();

        return $positions;
    }

    function getClosestDate($search, $arr) {

           $filtered = collect($arr)->filter(function ($value) use ($search) {
               return $value >= $search;
           })->toArray();
        $closest = null;
        foreach ($filtered as $item) {
            if ($closest === null || abs($search - $closest) > abs($item - $search)) {
                $closest = $item;
            }
        }

        return $closest;
    }

?>
