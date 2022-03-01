<?php

namespace App\Console;

use App\Jobs\GetPositions;
use App\Test;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        $now = Carbon::now();
        $month = $now->format('F');
        $year = $now->format('yy');
        $today = Carbon::now()->format('Y-m-d');
        $shs =  \App\Schedule::where('status','<=','0')->get();
        foreach ($shs as $sh){
            foreach (explode(",",$sh->date) as $date){
                if($sh->type == "weekly"){
                    $schedule->call('App\Http\Controllers\ScheduleController@proceed_scheduled_positions_to_get_task_ids',['id'=>$sh->id,'project_id'=>$sh->project_id] )->timezone('Europe/Moscow')->weeklyOn($date, $sh->time);
                }
                if($sh->type == "fixed_date"){
                    if(trim($date)==$today){
                        $schedule->call('App\Http\Controllers\ScheduleController@proceed_scheduled_positions_to_get_task_ids',['id'=>$sh->id,'project_id'=>$sh->project_id] )->timezone('Europe/Moscow')->at($sh->time);
                    }
                }
                if($sh->type == "everymonth"){
                    $schedule->call('App\Http\Controllers\ScheduleController@proceed_scheduled_positions_to_get_task_ids',['id'=>$sh->id,'project_id'=>$sh->project_id] )
                        ->timezone('Europe/Moscow')
                        ->monthlyOn(explode('-',$date)[2], $sh->time);
                }
                if($sh->type == "get_position"){
                    $schedule->call('App\Http\Controllers\ScheduleController@proceed_scheduled_positions_from_task_ids',['id'=>$sh->id,'project_id'=>$sh->project_id] )->everyFiveMinutes();
                    //->everyMinute();
                    //->everyFiveMinutes();
                    //->everyTenMinutes();
					//->everyThreeMinutes();
                }



            }

        }

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    public function send_to_tg_bot($message,$from){

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
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
