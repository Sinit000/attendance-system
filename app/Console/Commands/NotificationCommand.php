<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Notifications\PushNotification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class NotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = Notification::whereDate('from_date',Carbon::now())->first();
        $timeNow = Carbon::now()->format('H:i:s');
        // for($i=0;$i<count($data);$i++){
        //     if($data[$i]['from_date'] ==  Carbon::now('Y-m-d')){
                   
        //     }
        // }
        if($data){
           
           
           $a = new PushNotification();
           $a->notify($data);
        //    Counter::update();
           
        }
    }
}
