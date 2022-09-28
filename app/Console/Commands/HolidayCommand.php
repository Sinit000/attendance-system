<?php

namespace App\Console\Commands;

use App\Models\Counter;
use App\Models\Holiday;
use App\Models\Notification;
use App\Notifications\PushNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;




class HolidayCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:holiday';

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
        $data = Holiday::whereDate('from_date',Carbon::now())->first();
            // for($i=0;$i<count($data);$i++){
            //     if($data[$i]['from_date'] ==  Carbon::now('Y-m-d')){
                       
            //     }
            // }
            if($data){
               
               $counter = Counter::all();
               $total =0;
               for($i=0; $i< count($counter);$i++){
                $duration = $counter[$i]['total_ph'];
                   if( $duration >0){
                        $total = $duration - $data->duration;
                        $counter[$i]['total_ph'] = $total;
                   }
                   
                   $query = $counter[$i]->update();
                    
               }
               $a = new PushNotification();
               $a->notify($data);
            //    Counter::update();
               
            }
            
    }
}
