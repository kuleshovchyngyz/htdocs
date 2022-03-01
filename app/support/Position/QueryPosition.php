<?php


namespace App\support\Position;


class QueryPosition
{
    public $position_mm;
    public $position_m;
    public $region;
    public $full_url;
    public $position;
    public $position_class_name;
    public $target_path;
    public $matched_icon;
    public $change;

    public function __construct()
    {

    }
    public function setCompetitor($position_mm){
        $this->position_mm = $position_mm;
    }
    public function setPosition_m($position_m){
        $this->position_m = $position_m;
    }
    public function setRegion($region){
        $this->region = $region;
    }
    public function setUrl($full_url){
        $this->full_url = $full_url;
    }
    public function setPosition($position){
        $this->position = $position;
    }
    public function setPosition_class_name($position_class_name){
        $this->position_class_name = $position_class_name;
    }
    public function setTarget_path($target_path){
        $this->target_path = $target_path;
    }
    public function setMatched_icon($matched_icon){
        $this->matched_icon = $matched_icon;
    }
    public function setChange($change){
        $this->change = $change;
    }
}
