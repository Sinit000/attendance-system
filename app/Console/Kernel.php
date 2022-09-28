<?php

namespace App\Console;
use App\Models\Holiday;
use App\Models\Notification;
use App\Notifications\PushNotification;
use Carbon\Carbon;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        commands\HolidayCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('command:holiday')
        
                 ->everyMinute();
        // $schedule->call(function () {
        //     $data = Holiday::first();
        //     $a = new PushNotification();
        //        $a->notifyAllSpecificuser($data);
            
        //     // // for($i=0;$i<count($data);$i++){
        //     // //     if($data[$i]['from_date'] ==  Carbon::now('Y-m-d')){
                       
        //     // //     }
        //     // // }
        //     // if($data){
               
        //     //    echo $data->name;
        //     // }
            
   
        // })->everyFourHours();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
