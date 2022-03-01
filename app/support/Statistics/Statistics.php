<?php


namespace App\support\Statistics;


use App\Position;
use Illuminate\Support\Facades\DB;

class Statistics
{


    public $decrease;
    public $increase;
    public $unchanged;
    public $decrease_perecent;
    public $increase_perecent;
    public $unchanged_perecent;
    public $average;
    public $total_change;
    public $total_1_10;
    public $total_first_1_10;
    public $total_1_10_percent;
    public $total_1_3;
    public $total_first_1_3;
    public $total_1_3_percent;
    public $total_11_30;
    public $total_first_11_30;
    public $total_11_30_percent;
    public $total_31_50;
    public $total_first_31_50;
    public $total_31_50_percent;
    public $total_51_100;
    public $total_first_51_100;
    public $total_51_100_percent;
    public $total;
    public  function set_percents($decrease_perecent,$increase_perecent,$unchanged_perecent)
    {
        $this->decrease_perecent = round($decrease_perecent);
        $this->increase_perecent = round($increase_perecent);
        $this->unchanged_perecent = round($unchanged_perecent);

    }

    public function __construct($decrease, $increase, $unchanged, $average, $total_change)
    {
        $this->decrease = $decrease;
        $this->increase =$increase;
        $this->unchanged = $unchanged;
        $this->average = $average;
        $this->total_change = $total_change;
    }
    public  function set_1_10($total_1_10)    {

        $this->total_1_10 = $total_1_10;
    }
    public  function set_1_3($total_1_3)
    {

        $this->total_1_3 = $total_1_3;
    }
    public  function set_range_first($total_first_1_10,$total_first_11_30,$total_first_31_50,$total_first_51_100,$total_first_1_3)
    {
        $this->total_first_1_10 = $total_first_1_10;
        $this->total_first_11_30 = $total_first_11_30;
        $this->total_first_31_50 = $total_first_31_50;
        $this->total_first_51_100 = $total_first_51_100;
        $this->total_first_1_3 = $total_first_1_3;
    }
    public  function set_range_percents($total_1_10_percent,$total_11_30_percent,$total_31_50_percent,$total_51_100_percent,$total_1_3_percent)
    {
        $this->total_1_10_percent = $total_1_10_percent;
        $this->total_11_30_percent = $total_11_30_percent;
        $this->total_31_50_percent = $total_31_50_percent;
        $this->total_51_100_percent = $total_51_100_percent;
        $this->total_1_3_percent = $total_1_3_percent;
    }

    public  function set_11_30($total_11_30){
        $this->total_11_30 = $total_11_30;
    }
    public  function set_31_50($total_31_50){
        $this->total_31_50 = $total_31_50;
    }
    public  function set_51_100($total_51_100){
        $this->total_51_100 = $total_51_100;
    }
    public  function set_total($total){
        $this->total = $total;
    }


}
