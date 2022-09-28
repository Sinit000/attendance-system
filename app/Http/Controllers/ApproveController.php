<?php

namespace App\Http\Controllers;

use App\Models\Changedayoff;
use App\Models\Checkin;
use App\Models\Leave;
use App\Models\User;
use App\Models\Contract;
use App\Models\Counter;
use App\Models\Leaveout;
use App\Models\Leavetype;
use App\Models\Overtimecompesation;
use App\Models\Structure;
use App\Models\Workday;
use App\Notifications\PushNotification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;

class ApproveController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        // 'unique' => 'This :attribute has already been taken.',
        // 'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {
        $data = Leave::with('user', 'leavetype')->orderBy('created_at', 'DESC')->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $totalLeave = 0;
        // return response()->json($data);
        return view('admin.approve.approve');
        // $dateFrom="2022-07-21";
        // $dateTo="2022-08-07";
        // $findLeave = Leave::where('user_id', 2)->whereDate('leaves.created_at', '>=', date('Y-m-d', strtotime( $dateFrom)))
        // ->whereDate('leaves.created_at', '<=', date('Y-m-d', strtotime( $dateTo)))->get();
        // if ($findLeave) {

        //     for ($i = 0; $i < count($findLeave); $i++) {
        //         if($findLeave[$i]["leave_type_id"] == "1"){
        //        updates     $totalLeave = 0;
        //         }
        //         if ($findLeave[$i]["leave_type_id"] == "2") {
        //             $totalLeave = 0;
        //         }
        //         if($findLeave[$i]["leave_type_id"] == "3"){
        //             if ($findLeave[$i]["type"] == "day") {
        //                 $totalLeave += $findLeave[$i]["number"];
        //                 // $totalOt = round($totalOt, 2);
        //             } else {

        //                 $totalLeave  += $findLeave[$i]["leave_deduction"];
        //             }
        //         } 
        //     }  
        // } else {
        //     $totalLeave = 0;
        // }
        // return response()->json([
        //     'data'=>$totalLeave
        // ]);

    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $data = Leave::find($id);
        return response()->json($data);
    }


    public function update(Request $request, $id)
    {
        request()->validate([
            'status' => 'required|string',
            'leave_deduction' => 'required',
        ], $this->customMessages);
        $timeNow = Carbon::now()->format('H:i:s');
        //code...
        $data = Leave::find($id);
        $status = "";
        $message = "";
        $leaveDuction = 0;
        if ($request["status"] == 'pending') {
            $status = 'pending';
        }
        if ($request["status"] == 'approve') {
            $status = 'approved';
        } else {
            $status = 'rejected';
        }

        $data->status = $status;
        if ($request->leave_deduction) {
            $leaveDuction = $request->leave_deduction;
        } else {
            $leaveDuction = 0;
        }

        

        $data->leave_deduction = $leaveDuction;


        $query = $data->update();
        $profile = User::find($data->user_id);

        $startDate = date('m/d/Y', strtotime($data->from_date));
        $endDate = date('m/d/Y', strtotime($data->to_date));

        if ($data->status == "rejected") {
            $message = "Your leave request has been rejected!";
        }
        if ($data->status == 'approved') {
            // for type = half_day_m && half_day_n must create record
            // 03/23/22
            // end 23/03/22
            if ($data->type == "hour") {
               
            }
            if ($data->type == "half_day_m") {
                $att = new Checkin();
                $att->user_id = $profile->id;
                $att->status = 'scanned';
                $att->checkin_time = "0";
                // $att->checkout_time = "0";
                $att->checkin_late = "0";
                // $att->checkout_late = "0";
                $att->checkin_status = "permission halfday morning";
                // $att->checkout_status = "leave";
                $att->date = $startDate;
                $att->created_at = $startDate;
                $att->save();
                // $startDate=date('m/d/Y',strtotime($startDate.'+1 day'));
                // $respone = [
                //     'message'=>'Success',
                //     'code'=>0,
                // ];
            }
            if ($data->type == "half_day_n") {
                $att = new Checkin();
                $att->user_id = $profile->id;
                $att->status = 'present';
                // $att->status='permission half-day afternoon';
                // $att->checkin_time = "0";
                $att->checkout_time = "0";
                // $att->checkin_late = "0";
                $att->checkout_late = "0";
                // $att->checkin_status = "permission half-day afternoon";
                $att->checkout_status = "permission half-day afternoon";
                $att->date = $startDate;
                $att->created_at = $startDate;
                $att->save();
                // $startDate=date('m/d/Y',strtotime($startDate.'+1 day'));
                // $respone = [
                //     'message'=>'Success',
                //     'code'=>0,
                // ];
            }
            if ($data->type == "day") {
                while ($startDate <= $endDate) {
                    $att = new Checkin();
                    $att->user_id = $data->user_id;
                    $att->status = 'leave';
                    $att->checkin_time = '0';
                    $att->checkout_time = '0';
                    $att->checkin_status = "leave";
                    $att->checkout_status = "leave";
                    $att->checkin_late = "0";
                    $att->checkin_status = "0";
                    $att->date = $startDate;
                    $att->created_at = $startDate;
                    $att->save();
                    $startDate = date('m/d/Y', strtotime($startDate . '+1 day'));
                }
            }
            $message = "your leave request has been approved!";
        }
        if ($profile->device_token) {
            $notification = [
                'id' => $request->id,
                'title' => $message,
                'text' => $message,
                'device_token'=>$profile->device_token
            ];
            $a = new PushNotification();
               $a->notifySpecificuser($notification);
        }
//      counter 
        $leavetype = Leavetype::find($data->leave_type_id);
        $counter = Counter::where('user_id','=',$data->user_id)->first();
        $counterLeft =0;
        if(str_contains($leavetype->leave_type,'Hospitality') ){
            $counterLeft =  $counter->hospitality_leave - $data->number;   
            $counter-> hospitality_leave= $counterLeft;
        }elseif(str_contains($leavetype->leave_type,'Marriage')){
            $counterLeft =  $counter->marriage_leave - $data->number;  
            $counter-> marriage_leave= $counterLeft;
        }elseif(str_contains($leavetype->leave_type,'Peternity')){
            $counterLeft =  $counter->peternity_leave - $data->number;
            $counter-> peternity_leave= $counterLeft;
        }
        elseif(str_contains($leavetype->leave_type,'Funeral')){
            $counterLeft =  $counter->funeral_leave - $data->number;
            $counter-> funeral_leave= $counterLeft;
        }elseif(str_contains($leavetype->maternity_leave,'Maternity')){
            // $counterLeft =  0;
            $counter-> maternity_leave= 0;
        }else{
            $counterLeft =  0;
        }
        $counter->update();
        
        

       
        
        if ($query) {
            return response()->json(['code' => 0, 'message' => 'Data have Been updated']);
        } else {
            return response()->json(['code' => -1, 'message' => 'Something went wrong']);
        }
    }
    // overtime compestion
   
    public function getOtCompesation(){
        $data = Overtimecompesation::with('user')->orderBy('created_at', 'DESC')->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        $totalLeave = 0;
        // return response()->json($data);
        return view('admin.approve.compesation');
    }
    public function editOtCompesation($id)
    {
        $data = Overtimecompesation::find($id);
        return response()->json($data);
    }
    public function updateOTCompestion(Request $request, $id)
    {
        request()->validate([
            'status' => 'required|string',

        ], $this->customMessages);
        $data = Overtimecompesation::find($id);
        $message = "";
        $code = 2;
        $status = "";
        $totalDuration = 0;
        $case="";

        $userDuration = 0;
        $leftDuration = 0;
        $Checkduration=0;
        // $findCounter="";
        
       
        if ($data) {
            if ($request["status"] == 'pending') {
                $status = 'pending';
            }
            if ($request["status"] == 'approve') {
                $status = 'approved';
            } else {
                $status = 'rejected';
            }
            $profile = User::find($data->user_id);
            $findCounter = Counter::where('user_id', '=', $data->user_id)->first();
            if ($status == "approved") {
                if($findCounter){
                    $totalDuration =  $findCounter->ot_duration;
                }
                // check user type for request , day or hour
                if($data->type=="hour"){
                    $Checkduration= $data->duration;;
                }
                if($data->type == "day"){
                    $Checkduration = $data->duration * 8;
                }
               
                // $findCounter = Counter::where('user_id', '=', $data->user_id)->get();

                // if (count($findCounter) != 0) {
                //     $counter = Counter::where('user_id', '=', $data->user_id)->first();

                //     for ($i = 0; $i < count($findCounter); $i++) {
                //         $totalDuration +=  $findCounter[$i]["ot_duration"];
                //     }
                // } 
                if ($totalDuration == 0) {
                    $message = "Sorry,cannot request this!";
                    $code = -1;
                    $case="1";
                } else {
                    $userDuration = $Checkduration;
                    if ($userDuration > $totalDuration) {
                        $message = "Sorry,user ot compestion request is less the total ot that completed";
                        $code = -1;
                        $case="2";
                    }
                    if ($userDuration == $totalDuration) {
                        $leftDuration = 0;
                        $message = "Success";
                        $code = 0;
                        $case="3";
                    }
                    if ($userDuration < $totalDuration) {
                        $leftDuration = $totalDuration - $userDuration;
                        $message = "Success";
                        $code = 0;
                        $case="4";
                    }
                }
                
            } 
            if($status == "rejected")
            {
               
                $case="10";
                $code=3;
                // $query = $data->update();
                // $respone = [
                //     'message' => "Success",
                //     'code' => 0,
                //     'case'=>$case

                // ];
            }
           
            // get duration from user request 

            if ($code == 0) {
                $data->status = "approved";
                $findCounter->ot_duration = $leftDuration;
                $query=   $findCounter->save();
                // $data->duration = $leftDuration;
                $query = $data->update();
                // $data->approved_by=
                $respone = [
                    'message' => "Success",
                    'code' => 0,
                    'total_duration'=>$findCounter,
                    // 'left_duration'=>$findCounter->duration ,
                    // 'user_duration'=> $data->duration,
                    // 'case'=>$case
    
                ];
            }
            if($code ==3){
                $data->status = "rejected";
                $query=$data->update();
                $respone = [
                    'message' =>  "Success",
                    'code' => 0,
    
                ];
            }
            if($code ==-1) {
                $respone = [
                    'message' =>  $message,
                    'code' => -1,
                    // 'total_duration'=>$totalDuration,
                    // 'left_duration'=>$leftDuration,
                    // 'user_duration'=> $data->duration,
                    // 'case'=>$case

                ];
            }
            if ($profile->device_token) {
                $notification = [
                    'id' => $request->id,
                    'title' => $message,
                    'text' => $message,
                    'device_token'=>$profile->device_token
                ];
                $a = new PushNotification();
                   $a->notifySpecificuser($notification);
            }
        } else {
            $respone = [
                'message' =>  "No compesation id found",
                'code' => -1,

            ];
        }
        // return response()->json(['code' => 0, 'message' => 'Data have Been updated']);
        return response($respone, 200);
    }

    public function destroy($id)
    {
    }

    public function getChangeDayoff()
    {
        $data = Changedayoff::with('user')->orderBy('created_at', 'ASC')->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.approve.change_day_off');
    }
    public function editChangeDayOff($id)
    {
        $data = Changedayoff::find($id);
        return response()->json($data);
    }
    public function updateChangeDayoff(Request $request, $id)
    {
        request()->validate([
            'status' => 'required',

        ], $this->customMessages);
        $data = Changedayoff::find($id);
        $message = "";
        $code = 2;
        $status = "";
        
        // $findCounter="";
        if ($data) {
            $profile = User::find($data->user_id);
            if ($request["status"] == 'pending') {
                $status = 'pending';
            }
            if ($request["status"] == 'approve') {
                $status = 'approved';
            } else {
                $status = 'rejected';
            }
            // $findCounter = Counter::where('user_id', '=', $data->user_id)->first();
            if ($status == "approved") {
                $message = "Your request has been accepted!";
                $code =0;
            } 
            if($status == "rejected")
            {
               
                $message = "Sorry, your request has been rejected!";
                $code=3;
            }
            $case="";
            // get duration from user request 
            if ($code == 0) {
                // if user can cell holiday
                $workId=0;
                if(str_contains($data->type,'cancel')){

                    // change user work day , update user workday or create new work day 
                    // cannot change user workday to work 7 day in case this workday was share to other employee
                    $workday = Workday::whereNull('off_day')
                    ->orWhere('off_day','=',"")
                    ->first();
                   
                    if($workday){
                        $profile->workday_id = $workday->id;
                        $profile->update();
                        
                        $case="1";
                    }else{
                        
                        $case="2";
                        $w = Workday::create([
                            'name'           => "Custom",
                            'working_day'          => "0,1,2,3,4,5,6",
                            'notes'       => "custom",
                
                        ]);
                        $profile->workday_id = $w->id;
                        $profile->update();
                        // $workId="no workday";  
                    }
                   
                }
                if(str_contains($data->type,'change dayoff')){
                    $profile->workday_id = $data->workday_id;
                         $profile->update();
                }
                if(str_contains($data->type,'ph')){
                   
                }
                $data->status= "approved";
                $query = $data->update();
                $respone = [
                    'message' => "Success",
                    'code' => 0,
                   
                ];
            }
            if($code ==3){
                $data->status = "rejected";
                $query=$data->update();
                $respone = [
                    'message' =>  "Success",
                    'code' => 0,
                ];
            }
            // send message
            
            if ($profile->device_token) {
                if ($profile->device_token) {
                    $notification = [
                        'id' => $request->id,
                        'title' => $message,
                        'text' => $message,
                        'device_token'=>$profile->device_token
                    ];
                    $a = new PushNotification();
                       $a->notifySpecificuser($notification);
                }
            }
            // 
           
        } else {
            $respone = [
                'message' =>  "No change dayoff id found",
                'code' => -1,
            ];
        }
        // return response()->json(['code' => 0, 'message' => 'Data have Been updated']);
        return response($respone, 200);
    }
    // leave out
    public function getLeaveout()
    {
        $data = Leaveout::with('user')->orderBy('created_at', 'ASC')->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('admin.approve.leaveout');
    }
    public function editLeaveout($id)
    {
        $data = Leaveout::find($id);
        return response()->json($data);
    }

    public function updateLeaveout(Request $request, $id)
    {
        request()->validate([
            'status' => 'required|string',

        ], $this->customMessages);
        $data = Leaveout::find($id);
        $message = "";
        $code = 2;
        $status = "";
        $totalDuration = 0;
        $case="";

        $userDuration = 0;
        $leftDuration = 0;
        $Checkduration=0;
       
        // $findCounter="";
        if ($data) {
            $profile = User::find($data->user_id);
            if ($request["status"] == 'pending') {
                $status = 'pending';
            }
            if ($request["status"] == 'approve') {
                $status = 'approved';
            } else {
                $status = 'rejected';
            }
            // $findCounter = Counter::where('user_id', '=', $data->user_id)->first();
            if ($status == "approved") {
                $message = "Your request has been accepted!";
                $code =0;
            } 
            if($status == "rejected")
            {
               
                $message = "Sorry, your request has been rejected!";
                $code=3;
            }
            // get duration from user request 
            if ($code == 0) {
                $query = $data->update();
                $respone = [
                    'message' => "Success",
                    'code' => 0,
                ];
            }
            if($code ==3){
                $data->status = "rejected";
                $query=$data->update();
                $respone = [
                    'message' =>  "Success",
                    'code' => 0,
                ];
            }
            // send message
            
            if ($profile->device_token) {
                if ($profile->device_token) {
                    $notification = [
                        'id' => $request->id,
                        'title' => $message,
                        'text' => $message,
                        'device_token'=>$profile->device_token
                    ];
                    $a = new PushNotification();
                       $a->notifySpecificuser($notification);
                }
            }
            // 
           
        } else {
            $respone = [
                'message' =>  "No leaveout id found",
                'code' => -1,
            ];
        }
        // return response()->json(['code' => 0, 'message' => 'Data have Been updated']);
        return response($respone, 200);
    }
    // for hr send to accountant
    public function confirmLeave(Request $request){
        try {
            $country_ids = $request->countries_ids;
            $values=[
                'send_status'=>'true'
            ];
            Leave::whereIn('id', $country_ids)->update($values);
          
            
            $respone = [
                'message' => 'Success',
                'code' => 0,
                'data'=> $country_ids
            ];

            return response(
                $respone,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
       

    }

}
