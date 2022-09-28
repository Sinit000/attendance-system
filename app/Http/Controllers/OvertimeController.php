<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use App\Models\Overtime;
use App\Models\Structure;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class OvertimeController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        // 'unique' => 'This :attribute has already been taken.',
        // 'max' => ':Attribute may not be more than :max characters.',
    ];
    public function index()
    {
        $data = Overtime::with('user')->orderBy('created_at', 'DESC')->get();
        if (request()->ajax()) {
            return datatables()->of($data)
                ->addColumn('action', 'admin.users.action')
                ->addColumn('checkbox', function ($row) {
                    if ($row['send_status'] == 'false') {
                        return '<input type="checkbox" name="country_checkbox" data-id="' . $row['id'] . '"><label></label>';
                    }
                })
                // ->addColumn('checkbox', 'admin.overtime.checkbox')
                ->rawColumns(['action', 'checkbox'])
                ->addIndexColumn()
                ->make(true);
        }
        // $dateFrom="2022-07-10";
        // $dateTo="2022-07-26";
        // $totalOt=0;
        // $findOt= Overtime::where('user_id','=','2')->whereDate('overtimes.created_at', '>=', date('Y-m-d',strtotime(  $dateFrom )))
        // ->whereDate('overtimes.created_at', '<=', date('Y-m-d',strtotime($dateTo)))->get();
        // index list start from 0
        // for($i=0;$i<count( $findOt);$i++){
        //     $totalOt +=  $findOt[$i]["total_ot"];
        // }
        // $pastDF=Carbon::parse( $dateFrom);
        // $pastDT=Carbon::parse(  $dateTo);
        // $duration_in_days =   $pastDT ->diffInDays(  $pastDF);
        return view('admin.overtime.overtimes');
        // return response()->json(
        //     [
        //         'total_ot'=>$totalOt
        //     ]
        // );
    }
    public function getComponent()
    {
        $data = User::whereNotIn('id', [1])->orderBy('created_at', 'DESC')->get();

        if ($data) {
            return response()->json([
                "status" => 200,
                "data" => $data,

            ]);
        } else {
            return response()->json([
                "status" => 404,
                "data" => "Data not found!"
            ]);
        }
    }

    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $todayDate = Carbon::now()->format('m/d/Y');
        request()->validate([
            'user_id' => 'required',
            'number' => 'required',
            'reason' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
            'type' => 'required',
        ], $this->customMessages);

        $findEm = User::find($request->user_id);
        $otRate = 0;
        $otHour = 0;
        $otMethod = 1;
        $total = 0;
        $x = 0;

        // for calculate price for one hour
        $contract = Contract::where('user_id', $request->user_id)->first();
        if ($contract) {
            $structure = Structure::find($contract->structure_id);

            $baseSalary = $structure->base_salary;
            $standarHour =        $contract->working_schedule;
            // $standarHour =44;
            $SalaryOneHour =  ($baseSalary / 26) / 9;
            $x = round($SalaryOneHour, 2);
            $duration = 0;
            $totalDuration = 0;
            $duration_in_days = 0;
            if ($request->type == "hour") {
                $duration = $request->number;
                $totalDuration = $duration;
            } else {
                // check date 
                if ($request->from_date == $request->to_date) {
                    $duration = 8;
                    $totalDuration = 1;
                } else {
                    $dateFrom = "2022-07-21";
                    $dateTo = "2022-07-22";
                    $pastDF = Carbon::parse($request->from_date);
                    $pastDT = Carbon::parse($request->to_date);
                    $duration_in_days =   $pastDT->diffInDays($pastDF);
                    $totalDuration = $duration_in_days + 1;
                    // out put 1 ,but reality 2 
                    // $duration_in_days = $request->to_date->diffInDays($request->from_date);
                    // $duration= $duration_in_days;
                    $duration = ($duration_in_days + 1) * 8;
                }
            }
            if ($request->ot_method) {
                // $otRate =$request->ot_rate;
                // $otHour = $request->ot_hour;
                $otMethod = $request->ot_method;
                $total = $x *  $duration * $request->ot_method;
                $total = round($total, 2);
            } else {
                // $otRate =0;
                // $otHour = 0;
                $otMethod = 1;
                $total = $x *  $duration * 1;
                $total = round($total, 2);
            }

            $data = Overtime::create([
                'user_id'           => strip_tags(request()->post('user_id')),
                'reason'          => strip_tags(request()->post('reason')),
                'from_date'          => strip_tags(request()->post('from_date')),
                'to_date'          => strip_tags(request()->post('to_date')),
                'number'          => $totalDuration,
                'type'          => strip_tags(request()->post('type')),
                'notes'          => strip_tags(request()->post('notes')),
                'ot_rate'          => $x,
                'ot_hour'          => $duration,
                'ot_method'          => $otMethod,
                'total_ot'          => $total,
                'status' => 'pending',
                'pay_status' => 'pending',
                'send_status' => 'false',
                'date' => $todayDate,

            ]);

            if ($findEm->device_token) {
                $url = 'https://fcm.googleapis.com/fcm/send';
                $dataArr = array(
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'id' => $request->id,
                    'status' => "done",

                );
                $notification = array(
                    'title' => "Overtime requested!",
                    'text' => "Overtime requested!",
                    // 'isScheduled' => "true",
                    // 'scheduledTime' => "2022-06-14 17:55:00",
                    'sound' => 'default',
                    'badge' => '1',
                );
                // "registration_ids" => $firebaseToken,
                $arrayToSend = array(
                    "priority" => "high",
                    // "token"=>"7|Syty8L1QioCvQDQpl0axkahssTg542OE5HNCOpke",
                    // 'to'=>"/topics/6|bY5aVLz32sZrYIGjqpCqDUsRzFxopG8LgyRi0UOo",  
                    'to' => $findEm->device_token,
                    // 'registration_ids'=>'6|bY5aVLz32sZrYIGjqpCqDUsRzFxopG8LgyRi0UOo',
                    'notification' => $notification,
                    'data' => $dataArr,
                    'priority' => 'high'
                );
                $fields = json_encode($arrayToSend);
                $headers = array(
                    'Authorization: key=' . "AAAAqP0mBoo:APA91bEHUWxz5ZkOeZXpeoMSYtjQMdY8WCQyZSi7I5ycQJ3T6yUhqofYZ5w3AjCpjYSLm54Z3xTR3rsT7cLQ_L1xk7VNhODQDXi4GpxfRaDUH8eoefKuegD9_gx3IxKHIsFlLp8dcHe8",
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                $result = curl_exec($ch);
                // var_dump($result);
                curl_close($ch);
            }
            return response()->json($data);
        } else {
            $data = [
                'code' => -1,
                'message' => "This user hasn't had contract yet!"
            ];
            return response()->json($data);
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $data = Overtime::find($id);

        $user = User::whereNotIn('id', [1])->get();
        return response()->json(['data' => $data, 'user' => $user]);
    }



    public function update(Request $request, $id)
    {
        request()->validate([
            'user_id' => 'required',
            'number' => 'required',
            'reason' => 'required',

            'from_date' => 'required',
            'to_date' => 'required',
            'type' => 'required',
            // 'status' => 'required',
            // 'pay_status' => 'required',


        ], $this->customMessages);
        $data = Overtime::find($id);
        $otRate = 0;
        $otHour = 0;
        $otMethod = 1;
        $total = 0;
        $x = 0;
        // for calculate price for one hour
        $contract = Contract::where('user_id', $request->user_id)->first();
        if ($contract) {
            $structure = Structure::find($contract->structure_id);

            $baseSalary = $structure->base_salary;
            // $standarHour =44;
            $standarHour =        $contract->working_schedule;

            $SalaryOneHour =  ($baseSalary / 26) / 9;
            $x = round($SalaryOneHour, 2);
            $duration = 0;
            $duration_in_days = 0;
            $roundOt = 0;
            $totalDuration = 0;

            if ($request->type == "hour") {
                $duration = $request->number;
                $totalDuration = $duration;
            } else {
                // check date 
                if ($request->from_date == $request->to_date) {
                    $duration = 8;
                    $totalDuration = 1;
                } else {
                    $pastDF = Carbon::parse($request->from_date);
                    $pastDT = Carbon::parse($request->to_date);
                    $duration_in_days =   $pastDT->diffInDays($pastDF);
                    $totalDuration = $duration_in_days + 1;
                    // out put 1 ,but reality 2 
                    // $duration_in_days = $request->to_date->diffInDays($request->from_date);
                    // $duration= $duration_in_days;
                    $duration = ($duration_in_days + 1) * 8;
                }
            }
            if ($request->ot_method) {
                // $otRate =$request->ot_rate;
                $otHour = $request->ot_hour;
                $otMethod = $request->ot_method;
                $total = $x *  $duration * $request->ot_method;
                $total = round($total, 2);
            } else {
                $otRate = 0;
                $otHour = 0;
                $otMethod = 1;
                $total = $x *  $duration * 1;
                $total = round($total, 2);
            }
            if ($data->pay_status == "pending"  && ($data->status == "pending" || $data->status == "approved")) {
                $data->update([
                    'user_id'           => strip_tags(request()->post('user_id')),
                    'reason'          => strip_tags(request()->post('reason')),
                    'from_date'          => strip_tags(request()->post('from_date')),
                    'to_date'          => strip_tags(request()->post('to_date')),
                    'number'          =>   $totalDuration,
                    'type'          => strip_tags(request()->post('type')),
                    'notes'          => strip_tags(request()->post('notes')),
                    'ot_rate'          => $x,
                    'ot_hour'          => $duration,
                    'ot_method'          => $otMethod,
                    'total_ot'          => $total,
                    'send_status' => 'false',
                    'pay_status' => strip_tags(request()->post('pay_status')),
                ]);
            }


            $previous_user = $data->user_id;
            $findEm = User::find($request['user_id']);
            if ($findEm) {
                if ($previous_user != $request['user_id']) {
                    if ($findEm->device_token) {
                        $url = 'https://fcm.googleapis.com/fcm/send';
                        $dataArr = array(
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'id' => $request->id,
                            'status' => "done",

                        );
                        $notification = array(
                            'title' => "Overtime requested!",
                            'text' => "Overtime requested!",
                            // 'isScheduled' => "true",
                            // 'scheduledTime' => "2022-06-14 17:55:00",
                            'sound' => 'default',
                            'badge' => '1',
                        );
                        // "registration_ids" => $firebaseToken,
                        $arrayToSend = array(
                            "priority" => "high",
                            // "token"=>"7|Syty8L1QioCvQDQpl0axkahssTg542OE5HNCOpke",
                            // 'to'=>"/topics/6|bY5aVLz32sZrYIGjqpCqDUsRzFxopG8LgyRi0UOo",  
                            'to' => $findEm->device_token,
                            // 'registration_ids'=>'6|bY5aVLz32sZrYIGjqpCqDUsRzFxopG8LgyRi0UOo',
                            'notification' => $notification,
                            'data' => $dataArr,
                            'priority' => 'high'
                        );
                        $fields = json_encode($arrayToSend);
                        $headers = array(
                            'Authorization: key=' . "AAAAqP0mBoo:APA91bEHUWxz5ZkOeZXpeoMSYtjQMdY8WCQyZSi7I5ycQJ3T6yUhqofYZ5w3AjCpjYSLm54Z3xTR3rsT7cLQ_L1xk7VNhODQDXi4GpxfRaDUH8eoefKuegD9_gx3IxKHIsFlLp8dcHe8",
                            'Content-Type: application/json'
                        );
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                        $result = curl_exec($ch);
                        // var_dump($result);
                        curl_close($ch);
                    }
                }
            }
            return response()->json($data);
        }
    }
    public function sendtoAccount(Request $request)
    {
        try {
            $country_ids = $request->countries_ids;
            $values = [
                'send_status' => 'true'
            ];
            Overtime::whereIn('id', $country_ids)->update($values);


            $respone = [
                'message' => 'Success',
                'code' => 0,
                'data' => $country_ids
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


    public function destroy($id)
    {
        $data = Overtime::find($id);
        if ($data->status == "pending") {
            // check if position id belong to employee table
            $data->delete();
            $respone = [
                'message' => 'Success',
                'code' => 0,
            ];
        } else {
            $respone = [
                'message' => 'cannot delete overtime that completed',
                'code' => -1,
            ];
        }
        return response()->json(
            $respone,
            200
        );
    }
    // public function sendToAccount(Request $request){
    //     $ids = $request->ids;
    //     DB::table("overtimes")->whereIn('id',explode(",",$ids))->delete();
    //     return response()->json(['success'=>"Products Deleted successfully."]);
    // }
}
