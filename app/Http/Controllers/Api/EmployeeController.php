<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Checkin;
use App\Models\Checkout;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Notice;
use App\Models\Store;
use App\Models\Timetable;
use App\Notifications\TelegramRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Config;
use App\Exceptions\InvalidOrderException;
use App\Models\Department;
use App\Models\Leave;
use App\Models\Leavetype;
use App\Models\Notification;
use App\Models\Overtime;
use App\Models\Payslip;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


use App\Models\Position;
use Carbon\Carbon;
// use App\Models\TimetableEmployee;
use App\Models\GroupDepartment;
use App\Models\Workday;
use App\Models\User;
use App\Models\Subleavetype;
use App\Models\Category;
use App\Models\Salary;
use App\Models\Contract;
use App\Models\Structure;
use App\Models\Counter;
use App\Models\Changedayoff;
use App\Models\Holiday;
use App\Models\Leaveout;
use App\Models\Overtimecompesation;
use App\Notifications\PushNotification;

// use Illuminate\Support\Str;

class EmployeeController extends Controller
{

    public function index()
    {
        //
    }

    public function register(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'gender' => 'required',
                'username' => 'required|string|unique:users,username',
                'department_id' => 'required',
                'position_id' => 'required',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        'message' => $error,
                        'code' => -1,
                    ],
                    201
                );
            } else {
                $user = User::create([
                    'name' => $request['name'],
                    'gender' => $request['gender'],
                    'username' => $request['username'],
                    'position_id' => $request['position_id'],
                    'department_id' => $request['department_id'],
                    'password' => bcrypt($request['password'])
                ]);
                $token = $user->createToken('mytoken')->plainTextToken;
                $respone = [
                    'message' => 'Success',
                    'code' => 0,
                    'user' => $user,
                    'token' => $token,
                ];
                return response($respone, 200);
            }
        } catch (Exception $e) {
            return response()->json(
                [

                    'message' => $e->getMessage(),

                ],
                500
            );
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        'message' => $error,
                        'code' => -1,
                        
                    ],
                    201
                );
            } else {

                $user = User::with('role')->where('username', $request->username)
                    ->whereNotIn('id', [1])->first();
                // check password
                if ($user) {
                    $work = "";
                    // $dayoff = Workday::find($records->workday_id);
                    // $countd = Workday::find($record->workday_id)->count();
                    // check workday 
                    // if( $dayoff){
                    //     $workday = explode(',', $dayoff->off_day);
                    //     // work day
                    //     $check = "true";
                    //     $notCheck = $this->getWeekday($todayDate);
                    //     // 1 = count($dayoff)
                    //     for ($i = 0; $i <  count( $workday); ++$i) {
                    //         //   if offday = today check will false
                    //         if ($workday[$i] == $notCheck) {
                    //             $check = "false";
                    //         }

                    //     }
                    //     if ($check == "false") {
                    //         // day off cannot check
                    //         $work="false";
                    //     }else{
                    //         $work="true";
                    //     }
                    // }
                    // $records->workday= $work;
                    if (Hash::check($request->password, $user->password)) {
                        $token = $user->createToken('mytoken')->plainTextToken;
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                            'user' => $user,
                            'token' => $token,
                        ];
                        return response($respone, 200);
                    } else {
                        return response()->json(
                            [
                                'message' => "Wrong username and password",
                                'code' => -1,
                                // 'data'=>[]
                            ]
                        );
                    }
                } else {
                    return response()->json(
                        [
                            'message' => "Username does not exist",
                            'code' => -1,
                            // 'data'=>[]
                        ]
                    );
                }
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                    // 'data'=>[]
                ],
                500
            );
        }
    }
    public function changepassword(Request $request)
    {
        try {
            $use_id = $request->user()->id;
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        'message' => $error,
                        'code' => -1,
                        // 'data'=>[]
                    ],
                    201
                );
            } else {

                $user = User::find($use_id);
                // check password
                if ($user) {
                    // check the same or now
                    if (Hash::check($request->old_password, $user->password)) {
                        $user->update([
                            'password' => Hash::make($request->new_password)
                        ]);
                        $token = $user->createToken('mytoken')->plainTextToken;
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                            'token' => $token

                        ];
                        // return response($respone ,200);
                    } else {
                        $respone = [
                            'message' => "Incorrect old password",
                            'code' => -1,
                        ];
                    }
                } else {
                    $respone = [
                        'message' => "Username does not exist",
                        'code' => -1,
                    ];
                }
                return response()->json(
                    $respone,
                    200
                );
            }
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage()
                    // 'data'=>[]
                ],
                500
            );
        }
    }
    public function checkProfile(Request $request, $date)
    {
        try {
            //code...
            // get user by token with timetable
            $use_id = $request->user()->id;
            $typedate = Workday::first();
            $todayDate = "";
            // $checkinRecord = Checkin::where('user_id', $use_id)
            // ->whereNull('checkout_time')
            // ->latest()->first();

            // $todayDate = Carbon::now()->format('m/d/Y');

            $records = User::find($use_id);
            if ($records) {

                if ($typedate->type_date_time == "server") {
                    $todayDate = Carbon::now()->format('m/d/Y');
                    $checkinRecord = Checkin::where('user_id', $records->id)
                        ->where('date', '=', $todayDate)
                        ->first();
                } else {
                    // $todayDate= $request['date'];
                    // check leave condition
                    $d = date('m/d/Y', strtotime($date));
                    // $leave =Leave::where('user_id',$records->id)->latest()->first();
                    $checkinRecord = Checkin::where('user_id', $records->id)
                        // ->where('checkout_time','=','0')
                        ->where('date', '=', $d)

                        // ->whereNull('checkout_time')
                        ->latest()->first();
                    // ->where('date', '=', $todayDate)


                }
                $checkinStatus = "false";
                if ($checkinRecord) {
                    // $checkinStatus="true";
                    if ($checkinRecord->checkout_status) {
                        $checkinStatus = "present";
                    } else {
                        // if have only checkin_status
                        $checkinStatus = "true";
                    }
                    if ($checkinRecord->status == "absent") {
                        $checkinStatus = "absent";
                    }
                    if ($checkinRecord->status == "leave") {
                        $checkinStatus = "leave";
                    }
                }
                $records->checkin_status = $checkinStatus;
                if ($checkinRecord) {
                    $records->checkin_id = $checkinRecord['id'];
                } else {
                    $records->checkin_id = null;
                }
                $records->checkin = $checkinRecord;
            }

            $respone = [
                'message' => 'Success',
                'code' => 0,
                'user' =>  $records
                // 'checkin'=>$checkin,

            ];
            return response(
                $respone,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getprofile(Request $request)
    {
        try {
            //code...
            // get user by token with timetable
            $use_id = $request->user()->id;
            $typedate = Workday::first();
            $todayDate = "";


            $records = User::with('timetable', 'department', 'position', 'workday', 'role')->find($use_id);
            if ($records) {

                if ($typedate->type_date_time == "server") {
                    $todayDate = Carbon::now()->format('m/d/Y');
                    $checkinRecord = Checkin::where('user_id', $records->id)
                        ->where('date', '=', $todayDate)
                        ->first();
                } else {
                    // $todayDate= $request['date'];
                    $checkinRecord = Checkin::where('user_id', $records->id)
                        ->whereNull('checkout_time')
                        ->latest()->first();
                    // ->where('date', '=', $todayDate)


                }
                $checkinStatus = "false";
                if ($checkinRecord) {
                    // $checkinStatus="true";
                    if ($checkinRecord->checkout_status) {
                        $checkinStatus = "present";
                    } else {
                        // if have only checkin_status
                        $checkinStatus = "true";
                    }
                    if ($checkinRecord->status == "absent") {
                        $checkinStatus = "absent";
                    }
                    if ($checkinRecord->status == "leave") {
                        $checkinStatus = "leave";
                    }
                }
                $records->checkin_status = $checkinStatus;
                if ($checkinRecord) {
                    $records->checkin_id = $checkinRecord['id'];
                } else {
                    $records->checkin_id = null;
                }
                $records->checkin = $checkinRecord;
                // workday
                // $work="";
                // $dayoff = Workday::find($records->workday_id);
                // // $countd = Workday::find($record->workday_id)->count();
                // // check workday 
                // if( $dayoff){
                //     $workday = explode(',', $dayoff->off_day);
                //     // work day
                //     $check = "true";
                //     $notCheck = $this->getWeekday($todayDate);
                //     // 1 = count($dayoff)
                //     for ($i = 0; $i <  count( $workday); ++$i) {
                //         //   if offday = today check will false
                //         if ($workday[$i] == $notCheck) {
                //             $check = "false";
                //         }

                //     }
                //     if ($check == "false") {
                //         // day off cannot check
                //         $work="false";
                //     }else{
                //         $work="true";
                //     }
                // }
                // $records->workday= $work;
            }
            // if($records){
            //     $checkinStatus = "false";
            //     $checkinRecord = Leave::where('user_id',$records->id)
            //     ->where('date','=',$todayDate)
            //     ->first();
            //     if($checkinRecord)
            //     {
            //         $checkinStatus="true";
            //     }
            //     $records->leave_status=$checkinStatus;
            //     $records->leave = $checkinRecord;
            // }
            // if ($records) {
            //     // $checkinStatus = false;
            //     $checkinStatus = "0";

            //     $checkinRecord = TimetableEmployee::where('user_id', $records->id)->count();

            //     if ($checkinRecord) {
            //         if ($checkinRecord == 1) {
            //             $checkinStatus = "1";
            //         } else {
            //             $checkinStatus = "2";
            //         }
            //     }
            //     $records->em_type = $checkinStatus;
            //     // $record->leave = $checkinRecord;
            // }

            $respone = [
                'message' => 'Success',
                'code' => 0,
                'user' =>  $records
                // 'checkin'=>$checkin,

            ];
            return response(
                $respone,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function userlocation(Request $request)
    {
        $storelat = "11.5539968";
        $storelon = "104.906752";

        $userlat = $request["lat"];
        $userlot = $request["lon"];
        $timeNow = Carbon::now()->format('H:i:s');
        $data = $this->distance($storelat, $storelon, $userlat, $userlot, "K");
        // $data= $this-> getDistanceBetweenPointsNew( $userlat,$userlot,$storelat,$storelon, 'kilometers' );
        return response()->json(
            [
                // 'status'=>'false',
                'message' => "Success",
                'data' => $data,
                'time' => $timeNow
            ],
            200
        );
    }
    function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit)
    {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        switch ($unit) {
            case 'miles':
                break;
            case 'kilometers':
                $distance = $distance * 1.609344;
        }
        return (round($distance, 2));
    }
    protected function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {


        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }
    public function checkin(Request $request)
    {
        try {
            $status = ["on time", "very good", "late", "too late"];
            $typedate = Workday::first();
            $todayDate = "";
            $overtime = "";
            if ($typedate->type_date_time == "server") {
                $todayDate = Carbon::now()->format('m/d/Y');
            } else {
                $todayDate = $request['date'];

                // Str::substr($request['date'], 0, 10);

                //   $day = Str::substr($typedate->date_time, 2, 2);
                // $month = Str::substr($typedate->date_time, 0, 2);
                // $year = Str::substr($typedate->date_time, 4, 4);
                // $todayDate = $month . '/' . $day . '/'. $year;
                // $todayDate =(string)$month + "/" + (string)$day + "/" + (string)$year;

            }


            // $timeNow = Carbon::now()->format('H:i:s');
            // // $time = new Date();
            $use_id = $request->user()->id;
            // $todayDate = Carbon::now()->format('m/d/Y');

            $employee = User::find($use_id);
            // check check in the same with overtime or not
            // $overtime = Overtime::where('user_id','=',$employee->id)
            // ->where('from_date','=',$today)
            // ->orWhere('to_date','=',$today)->latest()->first();  
            $targetStatus = "";
            $validator = Validator::make($request->all(), [
                'checkin_time' => 'required|string',
                'lat' => 'required',
                'lon' => "required",
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        'message' => $error,
                        'code' => -1,
                    ],
                    201
                );
            } else {
                // checkin employee id
                if ($employee) {
                    if ($typedate->type_date_time == "server") {
                        $todayDate = Carbon::now()->format('m/d/Y');
                        $today = Carbon::now()->format('Y-m-d');
                        $overtime = Overtime::where('user_id', '=', $employee->id)
                            ->where('from_date', '=', $today)
                            ->orWhere('to_date', '=', $today)->latest()->first();
                    } else {
                        $todayDate = $request['date'];
                        $overtime = Overtime::where('user_id', '=', $employee->id)
                            ->where('from_date', '=', $todayDate)
                            ->orWhere('to_date', '=', $todayDate)->latest()->first();
                    }

                    $position = Position::find($employee->position_id);
                    $scann = Checkin::where('user_id', '=', $employee->id)->latest()->first();
                    $i = "";
                    $store = Department::find($employee->department_id);
                    $data = Location::find($store->location_id);
                    if ($data->id != $store->location_id) {
                        $respone = [
                            'message' => 'location are not allow',
                            'code' => -1,
                            // 'userId'=> $store,
                        ];
                    } else {

                        // $location = Location::
                        // selectRaw("*,
                        //             ( 6371 * acos( cos( radians(" . $lat . ") ) *
                        //             cos( radians(lat) ) *
                        //             cos( radians(lon) - radians(" .  $lon . ") ) +
                        //             sin( radians(" . $lat . ") ) *
                        //             sin( radians(lat) ) ) )
                        //             AS distance")
                        // ->first();
                        $location = $this->distance($data->lat, $data->lon, $request['lat'], $request['lon'], "K");
                        // out put 7km
                        if (
                            $location >= 0.3
                            // $location['distance']>=0.2
                        ) {
                            $respone = [
                                'message' => 'your location is not allow',
                                'code' => -1,
                                'distance' => $location
                            ];
                        } else {
                            $otStatus = "false";
                            $findtime = Timetable::find($employee->timetable_id);
                            $userCheckin = Carbon::parse($request['checkin_time']);
                            $userDuty = Carbon::parse($findtime->on_duty_time);
                            $diff = $userCheckin->diff($userDuty);
                            $hour = ($diff->h) * 60;
                            $minute = $diff->i;
                            $code = "";
                            $min = "";
                            $check = "";
                            $lateMinuteAdmin = $findtime['late_minute'];
                            if ($scann) {
                                if ($scann->date != $todayDate) {

                                    // $schedule = TimetableEmployee::where('user_id', $employee->id)->first();
                                    // $findtime = Timetable::find($schedule->timetable_id);
                                    if ($findtime) {
                                        if ($findtime['late_minute'] == "0") {

                                            if ($findtime['on_duty_time'] == $request['checkin_time']) {
                                                $targetStatus = $status[0];
                                                $min = "0";
                                                $code = 0;
                                            }   // 07:30 // 6
                                            elseif ($findtime['on_duty_time'] > $request['checkin_time']) {
                                                $targetStatus = $status[1];

                                                $min = $minute + $hour;
                                                $code = 0;
                                            }
                                            // 08:00,8:30
                                            elseif ($findtime['on_duty_time'] < $request['checkin_time']) {
                                                $targetStatus = $status[2];
                                                $min = $minute + $hour;
                                                $code = 0;
                                            }
                                            if ($typedate->type_date_time == "server") {
                                                $checkin = Checkin::create([
                                                    'checkin_time' => $request['checkin_time'],
                                                    'date' => $todayDate,
                                                    'status' => "checkin",
                                                    'checkin_status' => $targetStatus,
                                                    'checkin_late' => $min,
                                                    'send_status' => 'false',
                                                    'confirm' => 'false',
                                                    'ot_status' =>  $otStatus,
                                                    'user_id' => $employee->id,


                                                ]);
                                            } else {
                                                $checkin = Checkin::create([
                                                    'checkin_time' => $request['checkin_time'],
                                                    'date' => $todayDate,
                                                    'status' => "checkin",
                                                    'checkin_status' => $targetStatus,
                                                    'checkin_late' => $min,
                                                    'user_id' => $employee->id,
                                                    'send_status' => 'false',
                                                    'confirm' => 'false',
                                                    'ot_status' =>  $otStatus,
                                                    'created_at' => $request['created_at'],
                                                    'updated_at' => $request['created_at'],


                                                ]);
                                            }

                                            $respone = [
                                                'message' => 'Success',
                                                'code' => 0,
                                            ];
                                            $notification = new Notice(
                                                [
                                                    'notice' => "Checkin",
                                                    'noticedes' => "Employee name : {$employee->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Checkin time : " . $request['checkin_time'] . "\n" . "Date : " . $todayDate . "\n" . "Checkin status :" . $targetStatus . "\n" . "Time :" . $min . "\n",

                                                    'telegramid' => Config::get('services.telegram_id')
                                                ]
                                            );

                                            //  // $notification->save();
                                            $notification->notify(new TelegramRegister());
                                        } else {

                                            //  if admin set late minute
                                            if ($findtime['on_duty_time'] == $request['checkin_time']) {
                                                $targetStatus = $status[0];
                                                $min = ($minute + $hour) - $lateMinuteAdmin;
                                                $code = 0;
                                            }   // 07:30 // 6
                                            elseif ($findtime['on_duty_time'] > $request['checkin_time']) {
                                                $targetStatus = $status[1];
                                                $min = ($minute + $hour) - $lateMinuteAdmin;
                                                $code = 0;
                                            }
                                            // 08:00,8:30
                                            elseif ($findtime['on_duty_time'] < $request['checkin_time']) {
                                                $targetStatus = $status[2];
                                                $min = ($minute + $hour) - $lateMinuteAdmin;
                                                $code = 0;
                                            }
                                            if ($typedate->type_date_time == "server") {
                                                $checkin = Checkin::create([
                                                    'checkin_time' => $request['checkin_time'],
                                                    'date' => $todayDate,
                                                    'status' => "checkin",
                                                    'checkin_status' => $targetStatus,
                                                    'checkin_late' => $min,
                                                    'send_status' => 'false',
                                                    'confirm' => 'false',
                                                    'ot_status' =>  $otStatus,
                                                    'user_id' => $employee->id,


                                                ]);
                                            } else {
                                                $checkin = Checkin::create([
                                                    'checkin_time' => $request['checkin_time'],
                                                    'date' => $todayDate,
                                                    'status' => "checkin",
                                                    'checkin_status' => $targetStatus,
                                                    'checkin_late' => $min,
                                                    'user_id' => $employee->id,
                                                    'send_status' => 'false',
                                                    'confirm' => 'false',
                                                    'ot_status' =>  $otStatus,
                                                    'created_at' => $request['created_at'],
                                                    'updated_at' => $request['created_at'],


                                                ]);
                                            }


                                            $respone = [
                                                'message' => 'Success',
                                                'code' => 0,

                                            ];
                                            $notification = new Notice(
                                                [
                                                    'notice' => "Checkin",
                                                    'noticedes' => "Employee name : {$employee->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Checkin time : " . $request['checkin_time'] . "\n" . "Date : " . $todayDate . "\n" . "Checkin status :" . $targetStatus . "\n" . "Time :" . $min . "\n",
                                                    // 'noticedes'=>"employee_name"."\n"."checkin_time".$request['checkin_time']."\n"."date". $request['date']."\n"."checkin_status".$targetStatus."\n",
                                                    'telegramid' => Config::get('services.telegram_id')
                                                ]
                                            );

                                            // $notification->save();
                                            $notification->notify(new TelegramRegister());
                                        }
                                    }
                                    if ($overtime) {
                                        // $otStatus = "true";
                                        $overtime->status = "completed";
                                        $overtime->update();
                                        if ($overtime->pay_type == "holiday") {
                                            $counter = Counter::where('user_id', '=', $employee->id)->first();
                                            if ($overtime->type == "hour") {
                                                $counter->ot_duration  = $overtime->number;
                                            } else {
                                                $counter->ot_duration  = $overtime->number * 8;
                                            }

                                            $counter->update();
                                        }
                                    }
                                } else {
                                    $respone = [
                                        'message' => 'cannot checkin',
                                        'code' => -1,
                                        'checkindate' => $todayDate,
                                        'lastcheckin' => $scann->date,
                                        // "req"=>$checkin,
                                    ];
                                }
                            } else {
                                // first scann for employee type 1 :have timetable 1

                                if ($findtime) {
                                    if ($findtime['late_minute'] == "0") {

                                        if ($findtime['on_duty_time'] == $request['checkin_time']) {
                                            $targetStatus = $status[0];
                                            $min = "0";
                                            $code = 0;
                                        }   // 07:30 // 6
                                        // user on duty time : 8 but come arround 7
                                        elseif ($findtime['on_duty_time'] > $request['checkin_time']) {
                                            $targetStatus = $status[1];
                                            // $min = $minute;

                                            $min = $minute + $hour;
                                            $code = 0;
                                        }
                                        // 08:00,8:30
                                        elseif ($findtime['on_duty_time'] < $request['checkin_time']) {
                                            $targetStatus = $status[2];
                                            $min = $minute + $hour;
                                            $code = 0;
                                        }
                                        if ($typedate->type_date_time == "server") {
                                            $checkin = Checkin::create([
                                                'checkin_time' => $request['checkin_time'],
                                                'date' => $todayDate,
                                                'status' => "checkin",
                                                'checkin_status' => $targetStatus,
                                                'checkin_late' => $min,
                                                'user_id' => $employee->id,
                                                'confirm' => 'false',
                                                'ot_status' =>  $otStatus,
                                                'send_status' => 'false',


                                            ]);
                                        } else {
                                            $checkin = Checkin::create([
                                                'checkin_time' => $request['checkin_time'],
                                                'date' => $todayDate,
                                                'status' => "checkin",
                                                'checkin_status' => $targetStatus,
                                                'checkin_late' => $min,
                                                'user_id' => $employee->id,
                                                'send_status' => 'false',
                                                'confirm' => 'false',
                                                'ot_status' =>  $otStatus,
                                                'created_at' => $request['created_at'],
                                                'updated_at' => $request['created_at'],


                                            ]);
                                        }


                                        $respone = [
                                            'message' => 'Success',
                                            'code' => 0,

                                        ];
                                        $notification = new Notice(
                                            [
                                                'notice' => "Checkin",
                                                'noticedes' => "Employee name : {$employee->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Checkin time : " . $request['checkin_time'] . "\n" . "Date : " . $todayDate . "\n" . "Checkin status :" . $targetStatus . "\n" . "Time :" . $min . "\n",
                                                // 'noticedes'=>"employee_name"."\n"."checkin_time".$request['checkin_time']."\n"."date". $request['date']."\n"."checkin_status".$targetStatus."\n",
                                                'telegramid' => Config::get('services.telegram_id')
                                            ]
                                        );

                                        //  // $notification->save();
                                        $notification->notify(new TelegramRegister());
                                    } else {

                                        //  if admin set late minute
                                        if ($findtime['on_duty_time'] == $request['checkin_time']) {
                                            $targetStatus = $status[0];
                                            $min = "0";
                                            $code = 0;
                                        }   // 07:30 // 6
                                        // user come before time , minute late admin , see left duration
                                        elseif ($findtime['on_duty_time'] > $request['checkin_time']) {
                                            $targetStatus = $status[1];
                                            $min = ($minute + $hour) - $lateMinuteAdmin;
                                            $code = 0;
                                        }
                                        // 08:00,8:30
                                        elseif ($findtime['on_duty_time'] < $request['checkin_time']) {
                                            $targetStatus = $status[2];
                                            $min = ($minute + $hour) - $lateMinuteAdmin;
                                            $code = 0;
                                        }
                                        if ($typedate->type_date_time == "server") {
                                            $checkin = Checkin::create([
                                                'checkin_time' => $request['checkin_time'],
                                                'date' => $todayDate,
                                                'status' => "checkin",
                                                'checkin_status' => $targetStatus,
                                                'checkin_late' => $min,
                                                'user_id' => $employee->id,
                                                'confirm' => 'false',
                                                'ot_status' =>  $otStatus,
                                                'send_status' => 'false',


                                            ]);
                                        } else {
                                            $checkin = Checkin::create([
                                                'checkin_time' => $request['checkin_time'],
                                                'date' => $todayDate,
                                                'status' => "checkin",
                                                'checkin_status' => $targetStatus,
                                                'checkin_late' => $min,
                                                'user_id' => $employee->id,
                                                'send_status' => 'false',
                                                'confirm' => 'false',
                                                'ot_status' =>  $otStatus,
                                                'created_at' => $request['created_at'],
                                                'updated_at' => $request['created_at'],


                                            ]);
                                        }

                                        $respone = [
                                            'message' => 'Success',
                                            'code' => 0,

                                        ];
                                        $notification = new Notice(
                                            [
                                                'notice' => "Checkin",
                                                'noticedes' => "Employee name : {$employee->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Checkin time : " . $request['checkin_time'] . "\n" . "Date : " . $todayDate . "\n" . "Checkin status :" . $targetStatus . "\n" . "Time :" . $min . "\n",
                                                'telegramid' => Config::get('services.telegram_id')
                                            ]
                                        );

                                        // $notification->save();
                                        $notification->notify(new TelegramRegister());
                                    }
                                }
                                if ($overtime) {
                                    // $otStatus = "true";

                                    $overtime->status = "completed";
                                    $overtime->update();
                                    if ($overtime->pay_type == "holiday") {
                                        $counter = Counter::where('user_id', '=', $employee->id)->first();
                                        if ($overtime->type == "hour") {
                                            $counter->ot_duration  = $overtime->number;
                                        } else {
                                            $counter->ot_duration  = $overtime->number * 8;
                                        }
                                        $counter->update();
                                    }
                                }
                            }
                        }
                    }
                    return response()->json(
                        $respone,
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'message' => "No employee found",
                            'code' => -1,
                        ],
                        200
                    );
                }
            }
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    function getWeekday($date)
    {
        return date('w', strtotime($date));
    }


    public function usercheckout(Request $request, $id)
    {
        try {
            $status = ["good", "very good", "early", "too early"];
            $typedate = Workday::first();
            $todayDate = "";
            if ($typedate->type_date_time == "server") {
                $todayDate = Carbon::now()->format('m/d/Y');
            } else {
                $todayDate = $request['date'];
                // $todayDate= Str::substr($request['date'], 0, 10);
                //   $day = Str::substr($typedate->date_time, 2, 2);
                // $month = Str::substr($typedate->date_time, 0, 2);
                // $year = Str::substr($typedate->date_time, 4, 4);
                // $todayDate = $month . '/' . $day . '/'. $year;
                // $todayDate =(string)$month + "/" + (string)$day + "/" + (string)$year;

            }

            $targetStatus = "";
            $use_id = $request->user()->id;
            $employee = User::find($use_id);
            // $findCheckid = Checkin::find($use_id);

            $timeNow = Carbon::now()->format('H:i:s');
            $validator = Validator::make($request->all(), [
                // 'checkin_id' => 'required|string',
                'checkout_time' => 'required|string',
                'lat' => 'required',
                'lon' => "required",
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        'message' => $error,
                        'code' => -1,
                    ],
                    201
                );
            } else {
                // checkin employee id

                if ($employee) {
                    $position = Position::find($employee->position_id);
                    // retain condition user can checkout two time in oneday
                    // $id =checkin id, if have check in id , it means already checkin
                    $scann = Checkin::where('id', '=', $id)->where('user_id', '=', $employee->id)
                        ->whereNull('checkout_time')
                        ->latest()->first();


                    $store = Department::find($employee->department_id);


                    $data = Location::find($store->location_id);
                    if ($data->id != $store->location_id) {
                        $respone = [
                            'message' => 'location are not allow',
                            'code' => -1,

                        ];
                    } else {
                        $location = $this->distance($data->lat, $data->lon, $request['lat'], $request['lon'], "K");

                        // out put 7km
                        if ($location >= 0.3) {
                            $respone = [
                                'message' => 'your location is not allow',
                                'code' => -1,
                                'distance' => $location
                            ];
                        } else {
                            $findtime = Timetable::find($employee->timetable_id);

                            $userCheckin = Carbon::parse($request['checkout_time']);
                            $userDuty = Carbon::parse($findtime->off_duty_time);
                            $diff = $userCheckin->diff($userDuty);
                            $hour = ($diff->h) * 60;
                            $minute = $diff->i;
                            $code = "";
                            $min = "";
                            $case = "";
                            $lateMinuteAdmin = $findtime['early_leave'];
                            $totalMn = "";
                            $chekinLate = 0;
                            $chekinBF = 0;
                            $chekoutearly = 0;
                            $chekoutBF = 0;

                            // second record
                            // check checkin id and employee id
                            if ($scann) {
                                $standardHour = 0;
                                if ($scann->checkin_status == "late" || $scann->checkin_status == "too late") {
                                    $chekinLate =  $scann->checkin_late;
                                } else {
                                    // start 8, come 7: 30
                                    $chekinBF =  $scann->checkin_late;
                                }

                                $leave = Leave::where('user_id', '=', $employee->id)
                                    ->where('from_date', '=', $todayDate)
                                    ->first();
                                $leaveDuration = 0;
                                if ($leave) {
                                    // check leave status
                                    // calculate as mn

                                    if ($leave->type == "hour") {

                                        $leaveDuration = ($leave->number) * 60;
                                    }
                                    if ($leave->type == "half_day_m") {
                                        $leaveDuration = 4.5 * 60;
                                    }
                                    if ($leave->type == "half_day_n") {
                                        $leaveDuration = 4.5 * 60;
                                    }
                                } else {
                                    $leaveDuration = 0;
                                }

                                // check employee type first
                                if ($scann->date == $todayDate) {

                                    $contract = Contract::where('user_id', '=', $employee->id)->first();
                                    if ($contract) {
                                        $standardHour = ($contract->working_schedule) / 6;
                                    } else {
                                        $standardHour = 9;
                                    }
                                    if ($findtime) {
                                        //    because user checkout must check if checkout time < 1 hour can checkout 
                                        if ($findtime['early_leave'] == "0") {
                                            if ($findtime['off_duty_time'] == $request['checkout_time']) {
                                                $targetStatus = $status[0];
                                                $min = "0";
                                                $code = 0;
                                                $case = "1";
                                            }
                                            // user leave before time out
                                            // user leave 4, but time out 5
                                            elseif ($findtime['off_duty_time'] > $request['checkout_time']) {
                                                $targetStatus = $status[3];
                                                $min = $minute + $hour;
                                                $code = 0;
                                                $case = "2";
                                            }
                                            // user finish after time out 
                                            // user leave 8 , timeout 6
                                            elseif ($findtime['off_duty_time'] < $request['checkout_time']) {
                                                $targetStatus = $status[0];
                                                $min = $minute + $hour;
                                                $code = 0;
                                                $case = "3";
                                            }
                                            // standard hour for 1 day =9 h * 60mn = mn 540 mn
                                            if ($targetStatus == "early" || $targetStatus == "too early") {
                                                $chekoutearly = $min;
                                            } else {
                                                $chekoutBF = $min;
                                            }
                                            $totalMn = $standardHour - ($chekinLate +  $chekoutearly +  $leaveDuration -  $chekinBF - $chekoutBF) / 60;
                                            $totalMn = round($totalMn, 2);
                                            $scann->user_id = $employee->id;

                                            $scann->checkout_time = $request['checkout_time'];
                                            $scann->checkout_status = $targetStatus;
                                            $scann->status = "present";
                                            $scann->checkout_late = $min;
                                            $scann->duration = $totalMn;
                                            $scann->update();
                                            $respone = [
                                                'message' => 'Success',
                                                'code' => 0,

                                            ];
                                            $notification = new Notice(
                                                [
                                                    'notice' => "Checkout",
                                                    'noticedes' => "Employee name : {$employee->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Checkout time : " . $request['checkout_time'] . "\n" . "Date : " . $todayDate . "\n" . "Checkout status :" . $targetStatus . "\n" . "Time :" . $min . "\n",
                                                    // 'noticedes'=>"employee_name"."\n"."checkin_time".$request['checkin_time']."\n"."date". $request['date']."\n"."checkin_status".$targetStatus."\n",
                                                    'telegramid' => Config::get('services.telegram_id')
                                                ]
                                            );

                                            $notification->notify(new TelegramRegister());
                                        } else {
                                            // if admin set for leave early
                                            if ($findtime['off_duty_time'] == $request['checkout_time']) {
                                                $targetStatus = $status[0];
                                                $min = "0";
                                                $code = 0;
                                            }
                                            // user leave before time out
                                            // user leave 4, but time out 5
                                            elseif ($findtime['off_duty_time'] > $request['checkout_time']) {
                                                $targetStatus = $status[3];
                                                $min = $minute + $hour;
                                                $code = 0;
                                            }
                                            // user leave after time out
                                            // 08:00,8:30
                                            elseif ($findtime['off_duty_time'] < $request['checkout_time']) {
                                                $targetStatus = $status[0];
                                                $min = $minute + $hour;
                                                $code = 0;
                                            }
                                            if ($targetStatus == "early" || $targetStatus == "too early") {
                                                $chekoutearly = $min;
                                            } else {
                                                $chekoutBF = $min;
                                            }
                                            $totalMn =  $standardHour - ($chekinLate +  $chekoutearly +  $leaveDuration -  $chekinBF - $chekoutBF) / 60;
                                            $totalMn = round($totalMn, 2);
                                            $scann->user_id = $employee->id;
                                            $scann->checkout_time = $request['checkout_time'];
                                            $scann->checkout_status = $targetStatus;
                                            $scann->status = "present";
                                            $scann->checkout_late = $min;
                                            $scann->duration = $totalMn;
                                            $scann->update();
                                            $respone = [
                                                'message' => 'Success',
                                                'code' => 0,
                                            ];
                                            $notification = new Notice(
                                                [
                                                    'notice' => "Checkout",
                                                    'noticedes' => "Employee name : {$employee->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Checkout time : " . $request['checkout_time'] . "\n" . "Date : " . $todayDate . "\n" . "Checkout status :" . $targetStatus . "\n" . "Time :" . $min . "\n",
                                                    // 'noticedes'=>"employee_name"."\n"."checkin_time".$request['checkin_time']."\n"."date". $request['date']."\n"."checkin_status".$targetStatus."\n",
                                                    'telegramid' => Config::get('services.telegram_id')
                                                ]
                                            );
                                            $notification->notify(new TelegramRegister());
                                        }
                                    }
                                } else {
                                    $respone = [
                                        'message' => 'Already checkin',
                                        'code' => -1,
                                        'checkindate' => $todayDate,
                                        'lastcheckin' => $scann->date,
                                        // "req"=>$checkin,
                                    ];
                                }
                            } else {
                                // don't have checkin id = employee id
                                $respone = [
                                    'message' => 'No checkin id found ',
                                    'code' => -1,
                                ];
                            }
                        }
                    }

                    return response()->json(
                        $respone,
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'message' => "No employee found",
                            'code' => -1,
                        ],
                        200
                    );
                }
            }
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function leavetype(Request $request)
    {
        try {
            //code...
            $data = Leavetype::where('parent_id', '=', 0)->get();
            if ($data) {
                return response(
                    [
                        'code' => 0,
                        'message' => 'success',
                        'data' => $data
                    ]
                );
            }
        } catch (Exception $e) {
            // return response($e ,200);
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
    }
    public function subleavetype(Request $request, $id)
    {
        try {

            $department = Leavetype::where('parent_id', '=', $id)->get();
            return response()->json(
                [
                    'message' => "Success",
                    'code' => 0,
                    'data' => $department
                ],
                200
            );
        } catch (Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),

                ]
            );
        }
    }
    public function getSchedule(Request $request)
    {
        try {
            // $user_id = $request->user()->id;

            // $ex1 = TimetableEmployee::where('user_id', $user_id)->get();
            // foreach ($ex1 as $key => $val) {
            //     // $ex2 =  User::where('id',$val->user_id)->first();
            //     $time = Timetable::where('id', $val->timetable_id)->first();
            //     // $val->emplyee = $ex2;
            //     $val->timetable = $time;
            // }

            // //
            // return response()->json(
            //     ['data' => $ex1],
            //     200
            // );
        } catch (Exception $e) {
            // return response($e ,200);
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
    }
    public function getleave(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            // $employee = User::find($use_id);
            $pageSize = $request->page_size ?? 10;
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $todayDate = Carbon::now()->format('m/d/Y');
            // $postion = Position::paginate($pageSize);

            //code...

            if ($request->has('from_date') && $request->has('to_date')) {
                // $total= Leave::where('user_id',$user_id)-> whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($toDate))) ->count();
                // $pending= Leave::where('user_id',$user_id)-> whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($toDate)))
                // ->where('status','=','pending')
                // ->count();
                // // include approve and reject
                // $complete= Leave::where('user_id',$user_id)-> whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($toDate)))
                // ->where('status','=','approved')
                // ->orWhere('status','=','rejected')
                // ->count();
                $data = Leave::with('leavetype')->where('user_id', $user_id)->whereDate('leaves.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('leaves.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
            } else {
                $data = Leave::with('leavetype')->where('user_id', $user_id)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($pageSize);
            }
            // else{
            //     $complete= Leave::where('user_id',$user_id)->where('date','=',$todayDate)
            //     ->where('status','=','approved')
            //     ->orWhere('status','=','rejected')
            //      ->count();
            //      $pending= Leave::where('user_id',$user_id)->where('date','=',$todayDate)
            //      ->where('status','=','pending')
            //      ->count();
            //     $total= Leave::where('user_id',$user_id)->where('date','=',$todayDate) ->count();
            //     $data = Leave::with('leavetype')->where('user_id',$user_id)->with('user','leavetype')
            //     ->orderBy('created_at', 'DESC')->paginate($pageSize);
            // }
            // $response = [


            //     'total_leave'=>$total,
            //     'pending_leave'=>$pending,
            //     'complete_leave'=>$complete
            //     // 'checkin'=>$checkin,
            // ];
            return response()->json(
                $data,
                200
            );
        } catch (Exception $e) {
            // return response($e ,200);
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
    }
    public function countEachleave(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            // $employee = User::find($use_id);
            $pageSize = $request->page_size ?? 10;
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $todayDate = Carbon::now()->format('m/d/Y');
            // $postion = Position::paginate($pageSize);

            //code...

            if ($request->has('from_date') && $request->has('to_date')) {
                // $total= Leave::where('user_id',$user_id)-> whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($toDate))) ->count();
                // $pending= Leave::where('user_id',$user_id)-> whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($toDate)))
                // ->where('status','=','pending')
                // ->count();
                // // include approve and reject
                // $complete= Leave::where('user_id',$user_id)-> whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($toDate)))
                // ->where('status','=','approved')
                // ->orWhere('status','=','rejected')
                // ->count();
                $data = Leave::with('leavetype')->where('user_id', $user_id)->whereDate('leaves.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('leaves.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
            } else {
                $data = Leave::with('leavetype')->where('user_id', $user_id)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($pageSize);
            }
            // else{
            //     $complete= Leave::where('user_id',$user_id)->where('date','=',$todayDate)
            //     ->where('status','=','approved')
            //     ->orWhere('status','=','rejected')
            //      ->count();
            //      $pending= Leave::where('user_id',$user_id)->where('date','=',$todayDate)
            //      ->where('status','=','pending')
            //      ->count();
            //     $total= Leave::where('user_id',$user_id)->where('date','=',$todayDate) ->count();
            //     $data = Leave::with('leavetype')->where('user_id',$user_id)->with('user','leavetype')
            //     ->orderBy('created_at', 'DESC')->paginate($pageSize);
            // }
            // $response = [


            //     'total_leave'=>$total,
            //     'pending_leave'=>$pending,
            //     'complete_leave'=>$complete
            //     // 'checkin'=>$checkin,
            // ];
            return response()->json(
                $data,
                200
            );
        } catch (Exception $e) {
            // return response($e ,200);
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
    }
    public function addleave(Request $request)
    {
        try {
            // $todayDate = Carbon::now()->format('m/d/Y H:i:s' );
            $use_id = $request->user()->id;
            $user = User::find($use_id);
            $position = Position::find($user->position_id);
            $countDuration = 0;

            $countLeaveeDuration  = 0;
            $leftDuration = 0;
            $message = "";
            $code = 2;
            $findLeave = "";
            $case = "";
            $typeDuraton = 0;
            $d = "";
            $validator = Validator::make($request->all(), [
                'reason' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'leave_type_id' => 'required',
                'number' => 'required',
                'type' => 'required'

            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        // 'status'=>'false',
                        'message' => $error,
                        'code' => -1,
                        // 'data'=>[]
                    ],
                    201
                );
            } else {
                $typedate = Workday::first();
                // count leave employee and calculate
                // check leavetype first
                $type = $request['type'];
                if ($type == "hour") {
                    $countDuration = $request['number'];
                    $message = "hour";
                    $code = 0;
                }
                if ($type == "half_day_m") {
                    $countDuration = 4;
                    $message = "half day morning";
                    $code = 0;
                }
                if ($type == "half_day_n") {
                    $countDuration = 4;
                    $message = "half day afternoon";
                    $code = 0;
                }
                if ($type == "day") {

                    $pastDF = Carbon::parse($request['from_date']);
                    $pastDT = Carbon::parse($request['to_date']);
                    $countDuration =   $pastDT->diffInDays($pastDF);
                    $countDuration = $countDuration + 1;
                    $leaveRequest = $request['leave_type_id'];
                    $findLeave = Leave::where('user_id', '=', $user->id)

                        ->where('leave_type_id', '=', $leaveRequest)
                        ->count();
                    // $leave = count($findLeave);
                    if ($findLeave >= 1) {
                        $leavecount = Leave::where('user_id', '=', $user->id)

                            ->where('leave_type_id', '=', $leaveRequest)
                            ->get();
                        for ($i = 0; $i < count($leavecount); $i++) {
                            $countLeaveeDuration += $leavecount[$i]['number'];
                        }
                        // // to get type and duration

                        $findnewLeave = Leave::where('user_id', '=', $user->id)
                            ->where('leave_type_id', '=', $request['leave_type_id'])
                            ->first();
                        if ($findnewLeave) {
                            $findnewLeavetype = Leavetype::find($request['leave_type_id']);

                            $d = $findnewLeavetype->duration;
                            if ($findnewLeavetype->parent_id == 0) {
                                // sick or unpaid
                                if ($findnewLeavetype->duration == "0") {
                                    $code = 0;
                                } elseif (str_contains($findnewLeavetype->duration, 'month')) {
                                    $findPayslip = Payslip::where('user_id', '=', $user->id)
                                        ->count();
                                    if ($findPayslip <= 0) {
                                        $message = "Sorry, you don't enough permissin to request";
                                        $code = -1;
                                    }
                                    if ($findPayslip >= 11) {
                                        $code = 0;
                                    }
                                } else {
                                    // hospitalilty leave
                                    $typeDuraton = $findnewLeavetype->duration;
                                    if ($countLeaveeDuration >= $typeDuraton) {


                                        $message = "Sorry, you reach the limit permission";
                                        $code = -1;
                                    }
                                    if ($countLeaveeDuration < $typeDuraton) {
                                        $leftDuration = $typeDuraton - $countLeaveeDuration;
                                        // check if duration that they input ,bigger than specific duration
                                        if ($countDuration >  $leftDuration) {

                                            $message = "Please minus your input duration ";
                                            $code = -1;
                                        }
                                        if ($countDuration <=  $leftDuration) {
                                            $code = 0;
                                        }
                                    }
                                }
                            } else {
                                // special leave
                                $typeDuraton = $findnewLeavetype->duration;
                                if ($countLeaveeDuration >= $typeDuraton) {

                                    $message = "Sorry, you reach the limit permission";
                                    $code = -1;
                                    $typeDuraton = $findnewLeavetype->duration;
                                }
                                if ($countLeaveeDuration < $typeDuraton) {
                                    $leftDuration = $typeDuraton - $countLeaveeDuration;
                                    // check if duration that they input ,bigger than specific duration
                                    if ($countDuration >  $leftDuration) {

                                        $message = "Please minus your input duration";
                                        $code = -1;
                                    }
                                    if ($countDuration <=  $leftDuration) {
                                        $message = "still left leave";
                                        $code = 0;
                                    }
                                }
                            }
                        }
                    } else {
                        // first request leave 
                        // check leavetype ,
                        $type = Leavetype::find($request['leave_type_id']);
                        if ($type->parent_id == 0) {
                            // sick or unpaid
                            if ($type->duration == "0") {
                                $code = 0;
                            } elseif (str_contains($type->duration, 'month')) {
                                $findPayslip = Payslip::where('user_id', '=', $user->id)
                                    ->count();
                                if ($findPayslip <= 0) {
                                    $message = "Sorry, you don't enough permissin to request";
                                    $code = -1;
                                }
                                if ($findPayslip >= 11) {
                                    $code = 0;
                                }
                            } else {
                                // hosiptilty leave
                                $typeDuraton = $type->duration;
                                if ($countLeaveeDuration >= $typeDuraton) {


                                    $message = "Sorry, you reach the limit permission";
                                    $code = -1;
                                }
                                if ($countLeaveeDuration < $typeDuraton) {
                                    $leftDuration = $typeDuraton - $countLeaveeDuration;
                                    // check if duration that they input ,bigger than specific duration
                                    if ($countDuration >  $leftDuration) {

                                        $message = "Please minus your input duration ";
                                        $code = -1;
                                    }
                                    if ($countDuration <=  $leftDuration) {



                                        $code = 0;
                                    }
                                }
                            }
                        } else {
                            // special leave
                            $typeDuraton = $type->duration;
                            if ($countDuration >  $typeDuraton) {
                                $message = "Please minus your input duration";
                                $code = -1;
                            }
                            if ($countDuration <=  $typeDuraton) {
                                // $message = "still left leave";
                                $code = 0;
                            }
                        }
                    }
                }
                if ($code == 0) {
                    if ($typedate->type_date_time == "server") {
                        $todayDate = Carbon::now()->format('m/d/Y H:i:s');
                        $data = Leave::create([
                            'user_id' => $user->id,
                            'reason' => $request['reason'],
                            'note' => $request['note'],
                            'type' => $request['type'],
                            // 'subtype_id' => $request['subtype_id'],
                            'leave_type_id' => $request['leave_type_id'],
                            'from_date' => $request['from_date'],
                            'to_date' => $request['to_date'],
                            'number' => $countDuration,
                            'date' => $todayDate,
                            'status' => 'pending',
                            'image_url' => $request['image_url']
                        ]);
                    } else {
                        $data = Leave::create([
                            'user_id' => $user->id,
                            'reason' => $request['reason'],
                            'note' => $request['note'],
                            'type' => $request['type'],
                            // 'subtype_id' => $request['subtype_id'],
                            'leave_type_id' => $request['leave_type_id'],
                            'from_date' => $request['from_date'],
                            'to_date' => $request['to_date'],
                            'number' => $countDuration,
                            'date' => $request['date'],
                            'status' => 'pending',
                            'image_url' => $request['image_url'],
                            'created_at' => $request['created_at'],
                            'updated_at' => $request['created_at'],
                        ]);
                    }

                    $respone = [
                        'message' => "Success",
                        'code' => 0,

                    ];
                    $notification = new Notice(
                        [
                            'notice' => "Leave",
                            'noticedes' => "Employee name : {$user->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Reason : " . $request['reason'] . "\n" . "From Date : " . $request['from_date']  . "\n" . "To Date :" . $request['to_date'] . "\n" . "Type : " . $request['type'] . "\n" . "Duration :" . $countDuration . "\n",
                            'telegramid' => Config::get('services.telegram_id')
                        ]
                    );
                    $notification->notify(new TelegramRegister());
                } else {
                    $respone = [
                        'message' =>  $message,
                        'code' => $code,
                        'duration' => $d

                    ];
                }
                return response($respone, 200);
            }
        } catch (Exception $e) {
            // return response($e ,200);
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
    }
    public function getleaveChief(Request $request)
    {
        try {
            //code...
            // get user by token with
            $use_id = $request->user()->id;
            $ex = User::find($use_id);
            // $find= Department::where('manager','=', $ex->);
            $pageSize = $request->page_size ?? 10;
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');


            $todayDate = Carbon::now()->format('m/d/Y');




            // foreach($user as $key =>$value){
            //     $leav = Leave::select('leaves.*') 
            //     ->where('user_id', $value->id)->first();
            //     //  ->join('users','users.id','leaves.user_id')
            //     //  ->get();
            //      $value->leave = $leav;
            //  }
            // $user = Department::where('departments.id','=',$ex ->department_id)
            // ->join('users','users.department_id','=','departments.id')
            // // ->where('users.department_id','=',$ex ->department_id)
            // ->get();
            // foreach($user as $key =>$value){
            //     $leav = Leave::select('leaves.*') 
            //     ->where('user_id', $value->id)->first();
            //     //  ->join('users','users.id','leaves.user_id')
            //     //  ->get();
            //      $value->leave = $leav;
            //  }


            if ($request->has('from_date') && $request->has('to_date')) {
                // $total= Leave:: whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($toDate))) ->count();
                // $pending= Leave:: whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($toDate)))
                // ->where('status','=','pending')
                // ->count();
                // // include approve and reject
                // $complete= Leave:: whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($toDate)))
                // ->where('status','=','approved')
                // ->orWhere('status','=','rejected')
                // ->count();
                $user = Leave::select('leaves.*', 'leavetypes.leave_type', 'users.name')
                    ->join('leavetypes', 'leavetypes.id', '=', 'leaves.leave_type_id')
                    ->join('users', 'users.id', '=', 'leaves.user_id')
                    ->where('users.department_id', '=', $ex->department_id)
                    ->whereDate('leaves.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('leaves.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
                // $data = Leave::with('user','leavetype')
                // ->whereDate('leaves.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('leaves.created_at', '<=', date('Y-m-d',strtotime($toDate)))
                // ->orderBy('created_at', 'DESC')->paginate($pageSize);
            } else {

                $user = Leave::select('leaves.*', 'leavetypes.leave_type', 'users.name')
                    ->join('leavetypes', 'leavetypes.id', '=', 'leaves.leave_type_id')
                    ->join('users', 'users.id', '=', 'leaves.user_id')
                    ->where('users.department_id', '=', $ex->department_id)
                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
            }

            //

            // $postion = Timetable::orderBy('created_at', 'DESC')->paginate($pageSize);

            // $respone = [
            //     'message'=>'Success',
            //     'code'=>0,
            //     'data'=>$data,
            //     'total_leave'=>$total,
            //     'pending_leave'=>$pending,
            //     'complete_leave'=>$complete
            //     // 'checkin'=>$checkin,
            // ];
            return response(
                $user,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function editLeaveChief(Request $request, $id)
    {
        try {
            //code...
            // get user by token with timetable

            $data = Leave::find($id);
            $todayDate = Carbon::now()->format('m/d/Y');
            $timeNow = Carbon::now()->format('H:i:s');
            $leaveDuction = 0;
            $status = "";
            $message = "";
            if ($data) {
                $validator = Validator::make($request->all(), [

                    'status' => 'required',
                    'leave_deduction' => 'required',

                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->all()[0];
                    return response()->json(
                        [
                            'message' => $error,
                            'code' => -1,
                        ],
                        201
                    );
                } else {
                    if ($request["status"] == 'pending') {
                        $status = 'pending';
                    }
                    if ($request["status"] == 'approved') {
                        $status = 'approved';
                    } else {
                        $status = 'rejected';
                    }
                    $data->status = $status;


                    if ($request["leave_deduction"]) {
                        $leaveDuction = $request["leave_deduction"];
                    } else {
                        $leaveDuction = 0;
                    }
                    $data->leave_deduction = $leaveDuction;
                    $query = $data->update();
                    $profile = User::find($data->user_id);
                    //  $startDate = date('Y-m-d',strtotime($data->from_date));
                    $startDate = date('m/d/Y', strtotime($data->from_date));
                    $endDate = date('m/d/Y', strtotime($data->to_date));
                    if ($data->status == "rejected") {
                        $message = "Your leave request has been rejected!";
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                        ];
                    }
                    if ($data->status == 'approved') {
                        // check type of leave late 1 hour, permission half day or 1 day 2 day
                        if ($data->type == "hour") {
                            // if one hour 

                        }
                        // half_day_m == "morning"
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
                            //     'message' => 'Success',
                            //     'code' => 0,
                            // ];
                        }
                        // half_day_n :afernoon = checkout
                        if ($data->type == "half_day_n") {
                            $att = new Checkin();
                            $att->user_id = $profile->id;
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

                        }
                        if ($data->type == "day") {
                            while ($startDate <= $endDate) {
                                $att = new Checkin();
                                $att->user_id = $profile->id;
                                $att->status = 'leave';
                                $att->checkin_time = "0";
                                $att->checkout_time = "0";
                                $att->checkin_late = "0";
                                $att->checkout_late = "0";
                                $att->checkin_status = "leave";
                                $att->checkout_status = "leave";
                                $att->date = $startDate;
                                $att->created_at = $startDate;
                                $att->save();
                                $startDate = date('m/d/Y', strtotime($startDate . '+1 day'));
                            }

                            $respone = [
                                'message' => 'Success',
                                'code' => 0,
                            ];
                        }
                        $message = "your leave request has been approved!";
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                        ];
                    }
                    if ($profile->device_token) {
                        $url = 'https://fcm.googleapis.com/fcm/send';
                        $dataArr = array(
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'id' => $request->id,
                            'status' => "done",

                        );
                        $notification = array(
                            'title' => $message,
                            'text' => $message,
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
                            'to' => $profile->device_token,
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
            } else {
                $respone = [
                    'message' => 'No leave id found',
                    'code' => -1,


                ];
            }

            return response(
                $respone,
                200
            );
        } catch (Exception $e) {

            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function editleave(Request $request, $id)
    {
        try {
            // $todayDate = Carbon::now()->format('m/d/Y H:i:s' );
            $use_id = $request->user()->id;
            $user = User::find($use_id);
            $countDuration = 0;
            $countDuration = 0;

            $countLeaveeDuration  = 0;
            $leftDuration = 0;
            $message = "";
            $code = 2;
            $findLeave = "";
            $case = "";
            $typeDuraton = 0;
            $d = "";
            $position = Position::find($user->position_id);
            $data = Leave::find($id);
            if ($data) {
                $validator = Validator::make($request->all(), [
                    'reason' => 'required',
                    'from_date' => 'required',
                    'to_date' => 'required',
                    'leave_type_id' => 'required',
                    'number' => 'required',
                    'type' => 'required',
                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->all()[0];
                    return response()->json(
                        [

                            'message' => $error,
                            'code' => -1,

                        ],
                        200
                    );
                } else {
                    $typedate = Workday::first();

                    // $data = Leave::where('user_id', $user->id)
                    //     ->where('id', $leave->id)
                    //     ->latest()
                    //     ->first();
                    if ($data->status == "pending") {
                        $type = $request['type'];
                        if ($type == "hour") {
                            $countDuration = $request['number'];
                            $message = "hour";
                            $code = 0;
                        }
                        if ($type == "half_day_m") {
                            $countDuration = 4;
                            $message = "half day morning";
                            $code = 0;
                        }
                        if ($type == "half_day_n") {
                            $countDuration = 4;
                            $message = "half day afternoon";
                            $code = 0;
                        }
                        if ($type == "day") {
                            $pastDF = Carbon::parse($request['from_date']);
                            $pastDT = Carbon::parse($request['to_date']);
                            $countDuration =   $pastDT->diffInDays($pastDF);
                            $countDuration = $countDuration + 1;
                            $leaveRequest = $request['leave_type_id'];
                            $findLeave = Leave::where('user_id', '=', $user->id)

                                ->where('leave_type_id', '=', $leaveRequest)
                                ->where('status', '=', 'approved')
                                ->count();
                            // $leave = count($findLeave);
                            if ($findLeave >= 1) {
                                $leavecount = Leave::where('user_id', '=', $user->id)

                                    ->where('leave_type_id', '=', $leaveRequest)
                                    ->get();
                                for ($i = 0; $i < count($leavecount); $i++) {
                                    $countLeaveeDuration += $leavecount[$i]['number'];
                                }
                                // to get type and duration

                                $findnewLeave = Leave::where('user_id', '=', $user->id)
                                    ->where('leave_type_id', '=', $request['leave_type_id'])
                                    ->first();
                                if ($findnewLeave) {
                                    $findnewLeavetype = Leavetype::find($request['leave_type_id']);
                                    // sick leave and unpaid leave

                                    $d = $findnewLeavetype->duration;
                                    if ($findnewLeavetype->parent_id == 0) {
                                        // sick or unpaid
                                        if ($findnewLeavetype->duration == "0") {
                                            $code = 0;
                                        } elseif (str_contains($findnewLeavetype->duration, 'month')) {
                                            $findPayslip = Payslip::where('user_id', '=', $user->id)
                                                ->count();
                                            if ($findPayslip <= 0) {
                                                $message = "Sorry, you don't enough permissin to request";
                                                $code = -1;
                                            }
                                            if ($findPayslip >= 11) {
                                                $code = 0;
                                            }
                                        } else {
                                            // hospitalilty leave
                                            $typeDuraton = $findnewLeavetype->duration;
                                            if ($countLeaveeDuration >= $typeDuraton) {


                                                $message = "Sorry, you reach the limit permission";
                                                $code = -1;
                                            }
                                            if ($countLeaveeDuration < $typeDuraton) {
                                                $leftDuration = $typeDuraton - $countLeaveeDuration;
                                                // check if duration that they input ,bigger than specific duration
                                                if ($countDuration >  $leftDuration) {

                                                    $message = "Please minus your input duration ";
                                                    $code = -1;
                                                }
                                                if ($countDuration <=  $leftDuration) {



                                                    $code = 0;
                                                }
                                            }
                                        }
                                    } else {
                                        // special leave
                                        $typeDuraton = $findnewLeavetype->duration;
                                        if ($countLeaveeDuration >= $typeDuraton) {

                                            $message = "Sorry, you reach the limit permission";
                                            $code = -1;
                                            $typeDuraton = $findnewLeavetype->duration;
                                        }
                                        if ($countLeaveeDuration < $typeDuraton) {
                                            $leftDuration = $typeDuraton - $countLeaveeDuration;
                                            // check if duration that they input ,bigger than specific duration
                                            if ($countDuration >  $leftDuration) {

                                                $message = "Please minus your input duration";
                                                $code = -1;
                                            }
                                            if ($countDuration <=  $leftDuration) {
                                                $message = "still left leave";
                                                $code = 0;
                                            }
                                        }
                                    }
                                }
                            } else {

                                // leave status pending
                                $Leave = Leave::where('user_id', '=', $user->id)

                                    ->where('leave_type_id', '=', $leaveRequest)
                                    ->where('status', '=', 'pending')
                                    ->get();
                                // first request leave 
                                // check leavetype ,



                                if (count($Leave) > 0) {
                                    for ($i = 0; $i < count($Leave); $i++) {
                                        if ($Leave[$i]['id'] == $data->leave_type_id) {
                                            $countLeaveeDuration = 0;
                                        } else {
                                            $countLeaveeDuration += $Leave[$i]['number'];
                                        }
                                    }
                                    $findnewLeave = Leave::where('user_id', '=', $user->id)
                                        ->where('leave_type_id', '=', $request['leave_type_id'])
                                        ->where('status', '=', 'pending')
                                        ->first();
                                    if ($findnewLeave) {
                                        $type = Leavetype::find($request['leave_type_id']);
                                        if ($type->parent_id == 0) {
                                            // sick or unpaid
                                            if ($type->duration == "0") {
                                                $code = 0;
                                            } elseif (str_contains($type->duration, 'month')) {
                                                $findPayslip = Payslip::where('user_id', '=', $user->id)
                                                    ->count();
                                                if ($findPayslip <= 0) {
                                                    $message = "Sorry, you don't enough permissin to request";
                                                    $code = -1;
                                                }
                                                if ($findPayslip >= 11) {
                                                    $code = 0;
                                                }
                                            } else {
                                                // hosiptilty leave
                                                $typeDuraton = $type->duration;
                                                if ($countLeaveeDuration >= $typeDuraton) {


                                                    $message = "Sorry, you reach the limit permission";
                                                    $code = -1;
                                                }
                                                if ($countLeaveeDuration < $typeDuraton) {
                                                    $leftDuration = $typeDuraton - $countLeaveeDuration;
                                                    // check if duration that they input ,bigger than specific duration
                                                    if ($countDuration >  $leftDuration) {

                                                        $message = "Please minus your input duration ";
                                                        $code = -1;
                                                    }
                                                    if ($countDuration <=  $leftDuration) {



                                                        $code = 0;
                                                    }
                                                }
                                            }
                                        } else {
                                            // special leave
                                            $typeDuraton = $type->duration;
                                            // $typeDuraton = $type->duration;
                                            if ($countDuration >  $typeDuraton) {
                                                $message = "Please minus your input duration";
                                                $code = -1;
                                            }
                                            if ($countDuration <=  $typeDuraton) {
                                                // $message = "still left leave";
                                                $code = 0;
                                            }
                                        }
                                    }
                                } else {
                                    $type = Leavetype::find($request['leave_type_id']);
                                    if ($type->parent_id == 0) {
                                        // sick or unpaid
                                        if ($type->duration == "0") {
                                            $code = 0;
                                        } elseif (str_contains($type->duration, 'month')) {
                                            $findPayslip = Payslip::where('user_id', '=', $user->id)
                                                ->count();
                                            if ($findPayslip <= 0) {
                                                $message = "Sorry, you don't enough permissin to request";
                                                $code = -1;
                                            }
                                            if ($findPayslip >= 11) {
                                                $code = 0;
                                            }
                                        } else {
                                            // hosiptilty leave
                                            $typeDuraton = $type->duration;
                                            if ($countLeaveeDuration >= $typeDuraton) {


                                                $message = "Sorry, you reach the limit permission";
                                                $code = -1;
                                            }
                                            if ($countLeaveeDuration < $typeDuraton) {
                                                $leftDuration = $typeDuraton - $countLeaveeDuration;
                                                // check if duration that they input ,bigger than specific duration
                                                if ($countDuration >  $leftDuration) {

                                                    $message = "Please minus your input duration ";
                                                    $code = -1;
                                                }
                                                if ($countDuration <=  $leftDuration) {



                                                    $code = 0;
                                                }
                                            }
                                        }
                                    } else {
                                        // special leave
                                        $typeDuraton = $type->duration;
                                        // $typeDuraton = $type->duration;
                                        if ($countDuration >  $typeDuraton) {
                                            $message = "Please minus your input duration";
                                            $code = -1;
                                        }
                                        if ($countDuration <=  $typeDuraton) {
                                            // $message = "still left leave";
                                            $code = 0;
                                        }
                                    }
                                }
                                // $d= $type->duration;
                                // $contain = str_contains($d, "month");
                                // maternity leave (delivery baby)

                            }
                        }
                        if ($code == 0) {
                            if ($typedate->type_date_time == "server") {
                                $todayDate = Carbon::now()->format('m/d/Y H:i:s');
                                $data->user_id = $user->id;
                                $data->reason = $request['reason'];
                                $data->note = $request['note'];
                                $data->type = $request['type'];
                                $data->subtype = $request['subtype'];
                                $data->leave_type_id = $request['leave_type_id'];
                                $data->from_date = $request['from_date'];
                                $data->to_date = $request['to_date'];
                                $data->number =  $countDuration;
                                $data->image_url = $request['image_url'];
                                $data->status = 'pending';
                                $data->date =  $todayDate;
                                $data->update();
                            } else {
                                $data->user_id = $user->id;
                                $data->reason = $request['reason'];
                                $data->note = $request['note'];
                                $data->type = $request['type'];
                                $data->subtype = $request['subtype'];
                                $data->leave_type_id = $request['leave_type_id'];
                                $data->from_date = $request['from_date'];
                                $data->to_date = $request['to_date'];
                                $data->number =  $countDuration;
                                $data->image_url = $request['image_url'];
                                $data->status = 'pending';
                                $data->date =   $request['date'];
                                $data->update();
                            }


                            $respone = [
                                'message' => "Success",
                                'code' => 0,
                                // 'duraton'=>$d,

                            ];
                        } else {
                            $respone = [
                                'message' =>  $message,
                                'code' => $code,
                                // 'duraton'=>$d,


                            ];
                        }
                    }

                    return response($respone, 200);
                }
            } else {
                return response()->json(
                    [

                        'message' => "No leave id found",
                        'code' => -1,

                    ],
                    200
                );
            }
        } catch (Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),

                ]
            );
        }
    }
    public function deleteLeave(Request $request, $id)
    {
        try {
            $data = Leave::find($id);
            $use_id = $request->user()->id;
            $user = User::find($use_id);

            if ($data) {
                // check if position id belong to employee table
                $findEm = Leave::where('user_id', $user->id)->where('id', $id)->latest()->first();

                if ($findEm) {

                    if ($findEm->status == "pending") {
                        $data->delete();
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                        ];
                    } else {
                        $respone = [
                            'message' => 'no permission to delete',
                            'code' => -1,
                            // 'data'=>$findEm,
                            // 'id' =>$id
                        ];
                    }
                } else {
                    $respone = [
                        'message' => 'No employee id found',
                        'code' => -1,
                    ];
                }
            } else {
                $respone = [
                    'message' => 'No leave id found',
                    'code' => -1,
                ];
            }
            return response()->json(
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
    public function notification(Request $request)
    {

        try {
            $pageSize = $request->page_size ?? 10;
            //code...
            $data = Notification::paginate($pageSize);

            return response()->json(
                $data,
                200
            );
        } catch (Exception $e) {
            // return response($e ,200);
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
    }
    // for push local notification
    public function editFcmUser(Request $request)
    {
        try {
            $use_id = $request->user()->id;
            $validator = Validator::make($request->all(), [
                'device_token' => 'required',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        // 'status'=>'false',
                        'message' => $error,
                        'code' => -1,
                        // 'data'=>[]
                    ],
                    201
                );
            } else {
                $data = User::find($use_id);
                if ($data) {
                    $data->device_token = $request["device_token"];
                    $data->save();
                    $response = [
                        'message' => "Success",
                        'code' => 0,

                    ];
                } else {
                    $response = [
                        'message' => "No user id found",
                        'code' => -1,

                    ];
                }
                return response()->json(
                    $response,
                    200
                );
            }
        } catch (Exception $e) {
            // return response($e ,200);
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
    }
    public function create()
    {
        //
    }
    public function getcheckinlist(Request $request)
    {
        try {

            $user_id = $request->user()->id;
            // $employee = User::find($use_id);
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $todayDate = Carbon::now()->format('m/d/Y');
            $pageSize = $request->page_size ?? 10;
            //

            if ($request->has('from_date') && $request->has('to_date')) {


                $data  = Checkin::where('user_id', $user_id)
                    ->whereDate('checkins.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('checkins.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'ASC')
                    ->paginate($pageSize);
            } else {
                $data = Checkin::where('user_id', $user_id)
                    ->orderBy('created_at', 'ASC')
                    ->paginate($pageSize);
            }

            return response()->json(
                $data,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }

        // $data = Checkin::with('timetable','department','position','store','store.location')->find($use_id);
    }
    public function updateprofile(Request $request)
    {
        try {
            //code...
            // get user by token with timetable
            $user_id = $request->user()->id;
            $data = User::find($user_id);
            if ($data) {
                $data->profile_url = $request->profile_url;
                $data->save();
                $respone = [
                    'message' => 'Success',
                    'code' => 0,

                ];
            } else {
                $respone = [
                    'message' => 'Wrong user id',
                    'code' => 0,

                ];
            }
            return response()->json(
                $respone,
                200
            );
            // $validator = Validator::make($request->all(), [
            //     'name' => 'required|string',
            //     'gender' => 'required',
            //     'store_id' => 'required',

            //     'department_id' => 'required',
            //     // 'timetable_id' => 'required',
            //     'position_id' => 'required',
            //     'password' => 'required'
            // ]);
            // if ($validator->fails()) {
            //     $error=$validator->errors()->all()[0];
            //     return response()->json(
            //         [
            //             // 'status'=>'false',
            //             'message'=>$error,
            //             'code'=>-1,
            //             // 'data'=>[]
            //         ],201
            //     );

            // }else{
            //     // $query = $request->all();
            //     // $data->update( $query);
            //     //  $respone = [
            //     //      'message'=>'Success',
            //     //      'code'=>0,

            //     //  ];


            //     // return response($respone ,200);

            // }
        } catch (Exception $e) {
            //throw $th;
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getLocation(Request $request)
    {
        // $data = Location::first();
        $latitude = 11.5791579;
        $longitude = 104.8682052;
        $radius = 20000;
        // 6371 is Earth radius in km.)


        $data = Location::selectRaw("*,
                    ( 6371 * acos( cos( radians(" . $latitude . ") ) *
                    cos( radians(lat) ) *
                    cos( radians(lon) - radians(" . $longitude . ") ) +
                    sin( radians(" . $latitude . ") ) *
                    sin( radians(lat) ) ) )
                    AS distance")

            ->first();

        $km = 1.0;
        // output 7km
        // calculate km
        try {
            if ($data['distance'] <= 0.5) {
                return  response()->json(
                    [
                        'code' => 0,
                        'data' => $data
                    ]
                );
            } else {
                return  response()->json(
                    [
                        'code' => -1,
                        'data' => "grater than 0.5 km"
                    ]
                );
            }
        } catch (Exception $e) {
            //throw $th;
            return  response()->json(
                [
                    'code' => 0,
                    'message' => $e->getMessage()
                ]
            );
        }
    }


    public function store(Request $request)
    {
        //
    }
    public function employeeUploads(Request $request)
    {
        try {

            // file(file):keywork in postman, if put photo , file(photo)
            $uploadedFileUrl = Cloudinary ::upload($request->file('file')->getRealPath(),[
                'folder'=>'employee'
            ])->getSecurePath();
            //$result =  $request->file('file')->store('uploads/employee', 'photo');

            //    $request->file('photo')->store('uploads/employee','photo');
            $respone = [
                'message' => 'Sucess',
                'code' => 0,
                'profile_url' => $uploadedFileUrl

            ];


            return response()->json(
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
    // cheif department
    public function requestOvertime(Request $request)
    {
        try {
            // $todayDate = Carbon::now()->format('m/d/Y');
            $use_id = $request->user()->id;
            $ex = User::find($use_id);
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'number' => 'required',
                'type' => 'required',
                'reason' => 'required',
                'number' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        // 'status'=>'false',
                        'message' => $error,
                        'code' => -1,
                        // 'data'=>[]
                    ],
                    201
                );
            } else {
                $findEm = User::find($request['user_id']);
                $otRate = 0;
                $otHour = 0;
                $otMethod = 0;
                $total = 0;
                $totalDuration = 0;
                $contract = Contract::where('user_id', $request['user_id'])->first();
                $typedate = Workday::first();
                if ($contract) {
                    $structure = Structure::find($contract->structure_id);

                    $baseSalary = $structure->base_salary;
                    // $standarHour = 44;
                    $standarHour =        $contract->working_schedule;
                    $SalaryOneHour =  $baseSalary / ($standarHour * 4);
                    $duration = 0;
                    $duration_in_days = 0;
                    if ($request['type'] == "hour") {
                        $duration = $request['number'];
                        $totalDuration = $duration;
                    } else {
                        // check date 
                        if ($request['from_date'] == $request['to_date']) {
                            $duration = 8;
                            $totalDuration = 1;
                        } else {
                            $dateFrom = "2022-07-21";
                            $dateTo = "2022-07-22";
                            $pastDF = Carbon::parse($request['from_date']);
                            $pastDT = Carbon::parse($request['to_date']);
                            $duration_in_days =   $pastDT->diffInDays($pastDF);
                            $totalDuration = $duration_in_days + 1;
                            // out put 1 ,but reality 2 
                            // $duration_in_days = $request->to_date->diffInDays($request->from_date);
                            // $duration= $duration_in_days;
                            $duration = ($duration_in_days + 1) * 8;
                        }
                    }
                    if ($request['ot_method']) {
                        // $otRate =$request->ot_rate;
                        // $otHour = $request->ot_hour;
                        $otMethod = $request['ot_method'];
                        $total = $SalaryOneHour *  $duration *  $otMethod;
                        $total = round($total, 2);
                    } else {
                        // $otRate =0;
                        // $otHour = 0;
                        $otMethod = 0;
                        $total = 0;
                    }
                    // 
                    if ($typedate->type_date_time == "server") {
                        $todayDate = Carbon::now()->format('m/d/Y');
                        $user = Overtime::create([
                            'user_id' => $request['user_id'],
                            'reason' => $request['reason'],
                            'status' => 'pending',
                            'date' => $todayDate,
                            'number' => $totalDuration,
                            'type' => $request['type'],
                            'from_date' => $request['from_date'],
                            'to_date' => $request['to_date'],
                            'ot_rate' => round($SalaryOneHour, 2),
                            'ot_hour' => $duration,
                            'ot_method' => $otMethod,
                            'total_ot' => $total,
                            'pay_status' => 'pending',
                            'requested_by' => $ex->name,
                            'notes' => $request['note'],
                            'send_status' => 'false',

                        ]);
                    } else {
                        $user = Overtime::create([
                            'user_id' => $request['user_id'],
                            'reason' => $request['reason'],
                            'status' => 'pending',
                            'date' => $request['date'],
                            'number' => $totalDuration,
                            'type' => $request['type'],
                            'from_date' => $request['from_date'],
                            'to_date' => $request['to_date'],
                            'ot_rate' => round($SalaryOneHour, 2),
                            'ot_hour' => $duration,
                            'ot_method' => $otMethod,
                            'total_ot' => $total,
                            'pay_status' => 'pending',
                            'requested_by' => $ex->name,
                            'notes' => $request['note'],
                            'send_status' => 'false',
                            'created_at' => $request['created_at'],
                            'updated_at' => $request['created_at'],

                        ]);
                    }

                    $respone = [
                        'message' => 'Success',
                        'code' => 0,

                    ];
                    if ($findEm->device_token) {
                        $notification = [
                            'id' => $request->id,
                            'title' => 'Overtime Requested!',
                            'text' =>'Overtime Requested!',
                            'device_token'=>$findEm->device_token
                        ];
                        $a = new PushNotification();
                           $a->notifySpecificuser($notification);
                    }
                } else {
                    $respone = [
                        'message' => 'Sorry, emloyee has not sign contract yet!',
                        'code' => -1,
                    ];
                }

                return response()->json($respone, 200);
            }
        } catch (Exception $e) {
            return response()->json(
                [

                    'message' => $e->getMessage(),

                ],
                500
            );
        }
    }
    public function editOvertime(Request $request, $id)
    {
        try {
            // $todayDate = Carbon::now()->format('m/d/Y');
            $data = Overtime::find($id);
            if ($data) {
                $validator = Validator::make($request->all(), [
                    'user_id' => 'required',
                    'number' => 'required',
                    'reason' => 'required',
                    'number' => 'required',
                    'from_date' => 'required',
                    'to_date' => 'required',
                    'type' => 'required',
                    //   
                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->all()[0];
                    return response()->json(
                        [
                            // 'status'=>'false',
                            'message' => $error,
                            'code' => -1,
                            // 'data'=>[]
                        ],
                        201
                    );
                } else {
                    $previous_user = $data->user_id;
                    $findEm = User::find($request['user_id']);
                    $otRate = 0;
                    $otHour = 0;
                    $otMethod = 0;
                    $total = 0;
                    $contract = Contract::where('user_id', $request['user_id'])->first();
                    $totalDuration = 0;
                    $typedate = Workday::first();
                    if ($contract) {
                        $structure = Structure::find($contract->structure_id);

                        $baseSalary = $structure->base_salary;
                        $standarHour =        $contract->working_schedule;
                        // $standarHour = 44;
                        $SalaryOneHour =  $baseSalary / ($standarHour * 4);
                        $duration = 0;
                        $duration_in_days = 0;
                        if ($request['type'] == "hour") {
                            $duration = $request['number'];
                            $totalDuration = $duration;
                        } else {
                            // check date 
                            if ($request['from_date'] == $request['to_date']) {
                                $duration = 8;
                                $totalDuration = 1;
                            } else {
                                $dateFrom = "2022-07-21";
                                $dateTo = "2022-07-22";
                                $pastDF = Carbon::parse($request['from_date']);
                                $pastDT = Carbon::parse($request['to_date']);
                                $duration_in_days =   $pastDT->diffInDays($pastDF);
                                $totalDuration = $duration_in_days + 1;
                                // out put 1 ,but reality 2 
                                // $duration_in_days = $request->to_date->diffInDays($request->from_date);
                                // $duration= $duration_in_days;
                                $duration = ($duration_in_days + 1) * 8;
                            }
                        }
                        if ($request['ot_method']) {
                            // $otRate =$request->ot_rate;
                            // $otHour = $request->ot_hour;
                            $otMethod = $request['ot_method'];
                            $total = $SalaryOneHour *  $duration * $otMethod;
                            $total = round($total, 2);
                        } else {
                            // $otRate =0;
                            // $otHour = 0;
                            $otMethod = 0;
                            $total = 0;
                        }
                        // if user already approve urgent busy however status approve , but can edit other user
                        if ($data->pay_status == "pending"  && ($data->status == "pending" || $data->status == "approved")) {
                            if ($typedate->type_date_time == "server") {
                                $todayDate = Carbon::now()->format('m/d/Y');
                                $data->user_id = $request['user_id'];
                                $data->reason = $request['reason'];
                                $data->notes = $request['note'];
                                $data->from_date = $request['from_date'];
                                $data->to_date = $request['to_date'];
                                $data->number = $totalDuration;
                                $data->type = $request['type'];
                                // $data->status = $request['pay_status'];
                                $data->ot_rate =  round($SalaryOneHour, 2);
                                $data->ot_hour =  $duration;
                                $data->ot_method = $otMethod;
                                $data->total_ot = $total;
                                $data->date = $todayDate;
                                // $data->pay_status = "pending";
                                $data->update();
                            } else {
                                $data->user_id = $request['user_id'];
                                $data->reason = $request['reason'];
                                $data->notes = $request['note'];
                                $data->from_date = $request['from_date'];
                                $data->to_date = $request['to_date'];
                                $data->number = $totalDuration;
                                $data->type = $request['type'];
                                // $data->status = $request['pay_status'];
                                $data->ot_rate =  round($SalaryOneHour, 2);
                                $data->ot_hour =  $duration;
                                $data->ot_method = $otMethod;
                                $data->total_ot = $total;
                                $data->date = $request['date'];
                            }

                            $respone = [
                                'message' => 'Success',
                                'code' => 0,

                            ];
                            if ($previous_user != $request['user_id']) {
                                if ($findEm->device_token) {
                                    $notification = [
                                        'id' => $request->id,
                                        'title' => 'Overtime Requested!',
                                        'text' =>'Overtime Requested!',
                                        'device_token'=>$findEm->device_token
                                    ];
                                    $a = new PushNotification();
                                       $a->notifySpecificuser($notification);
                                }
                            }
                        }
                    } else {
                        $respone = [
                            'message' => 'No employee id found',
                            'code' => -1,


                        ];
                    }
                }
            } else {
                $respone = [
                    'message' => 'No leave id found',
                    'code' => -1,

                ];
            }
            return response()->json($respone, 200);
        } catch (Exception $e) {
            return response()->json(
                [

                    'message' => $e->getMessage(),

                ],
                500
            );
        }
    }
    public function deleteOvertime($id)
    {
        try {
            // if stutus ==pending
            $data = Overtime::find($id);
            if ($data) {
                if ($data->status == "pending") {
                    $data->delete();
                    $respone = [
                        'message' => 'Success',
                        'code' => 0,
                    ];
                } else {
                    $respone = [
                        'message' => 'Cannot delete this overtime',
                        'code' => 0,
                    ];
                }
            } else {
                $respone = [
                    'message' => 'No overtime id found',
                    'code' => -1,
                ];
            }
            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
    public function getOvertime(Request $request)
    {
        try {
            $use_id = $request->user()->id;
            $ex = User::find($use_id);
            // $find= Department::where('manager','=', $ex->);
            $pageSize = $request->page_size ?? 10;
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');


            if ($request->has('from_date') && $request->has('to_date')) {

                $user = Overtime::select('overtimes.*', 'users.name')

                    ->join('users', 'users.id', '=', 'overtimes.user_id')
                    ->where('users.department_id', '=', $ex->department_id)
                    ->whereDate('overtimes.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('overtimes.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'ASC')->paginate($pageSize);
            } else {

                $user = Overtime::select('overtimes.*', 'users.name')

                    ->join('users', 'users.id', '=', 'overtimes.user_id')
                    ->where('users.department_id', '=', $ex->department_id)
                    ->orderBy('created_at', 'ASC')->paginate($pageSize);
            }
            // $timetable = Overtime::orderBy('created_at', 'DESC')->paginate($pageSize);
            return response()->json(
                $user,
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    // public function getOvertByUser(Request $request, $id)
    // {
    //     try {
    //         $pageSize = $request->page_size ?? 10;
    //         $use_id = $request->user()->id;

    //         $department = Department::with('location')->where('group_department_id', $id)->paginate($pageSize);
    //         return response()->json(
    //             // 'code'=>0,
    //             // 'message'=>'sucess',
    //             $department,
    //             200
    //         );
    //     } catch (Exception $e) {
    //         //throw $th;
    //         return response()->json([
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }
    // user by department
    public function getUserByDepartment(Request $request)
    {
        try {
            // 3
            $user_id = $request->user()->id;
           
            $user = User::find($user_id);

            // $find = Department::where('manager',  $user_id)->first();
            $pageSize = $request->page_size ?? 10;

            $department = User::where('department_id', $user->department_id)->whereNotIn('id', [1])->paginate($pageSize);
            return response()->json(
                $department,
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function getAllUserByDepartment(Request $request)
    {
        try {
            // 3
            $user_id = $request->user()->id;

            $find = Department::where('manager', $user_id)->first();
            if ($find) {
                $department = User::where('department_id', $find->id)->whereNotIn('id', [1])->get();
                $response = [
                    'code' => 0,
                    'message' => 'Success',
                    'data' =>  $department
                ];
            } else {
                $response = [
                    'code' => -1,
                    'message' => "sorry, you don't have enough permission to request",

                ];
            }
            $pageSize = $request->page_size ?? 10;


            return response()->json(
                $response,
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    // get overtime for own 
    public function getMyOvertime(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            // $employee = User::find($use_id);
            $pageSize = $request->page_size ?? 10;
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $todayDate = Carbon::now()->format('m/d/Y');
            // $postion = Position::paginate($pageSize);

            //code...

            if ($request->has('from_date') && $request->has('to_date')) {

                $data = Overtime::where('user_id', $user_id)->whereDate('overtimes.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('overtimes.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'ASC')->paginate($pageSize);
            } else {
                $data = Overtime::where('user_id', $user_id)
                    ->orderBy('created_at', 'ASC')
                    ->paginate($pageSize);
            }
            return response()->json(
                $data,
                200
            );
        } catch (Exception $e) {
            // return response($e ,200);
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
    }
    // edit status overtime 
    public function editOvertimeStatus(Request $request, $id)
    {
        try {
            $data = Overtime::find($id);
            // for sending notification back to manager
            $ex1 = User::find($data->user_id);
            $department = Department::where('manager', $ex1->id);
            $todayDate = Carbon::now()->format('m/d/Y');
            $timeNow = Carbon::now()->format('H:i:s');
            $status = "";
            $title = "";
            $paytype = "";

            if ($data) {
                $validator = Validator::make($request->all(), [
                    'status' => 'required',
                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->all()[0];
                    return response()->json(
                        [
                            'message' => $error,
                            'code' => -1,
                        ],
                        201
                    );
                } else {
                    if ($request["status"] == "rejected") {
                        $title = "Employee has been rejected overtime!";
                        // $paytype=null;  
                    }
                    if ($request["status"] == 'approved') {
                        // if approved user must choose pay_type :return as cash or holiday

                        $title = "Employee has been approved overtime!";
                        $paytype = $request["pay_type"];
                        // check with attendance user come or not

                    }
                    // when paytype status ="completed";
                    $data->status = $request["status"];
                    $data->pay_type = $paytype;
                    $query = $data->update();
                    $respone = [
                        'message' => 'Success',
                        'code' => 0,
                    ];
                    // if this user has manager send notifcation back to manager
                    if ($department) {

                        if ($ex1->device_token) {
                            $notification = [
                                'id' => $request->id,
                                'title' => $title,
                                'text' =>$title,
                                'device_token'=>$ex1->device_token
                            ];
                            $a = new PushNotification();
                               $a->notifySpecificuser($notification);
                            
                        }
                    }
                }
            } else {
                $respone = [
                    'message' => 'No leave id found',
                    'code' => -1,


                ];
            }

            return response(
                $respone,
                200
            );
        } catch (Exception $e) {

            return response([
                'message' => $e->getMessage()
            ]);
        }
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }




    // payroll
    public function getMypayslip(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            // $find= Department::where('manager','=', $ex->);
            $pageSize = $request->page_size ?? 10;
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');

            if ($request->has('from_date') && $request->has('to_date')) {

                $data = Payslip::where('user_id', $user_id)->whereDate('payslips.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('payslips.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'DESC')
                    ->paginate($pageSize);
                foreach ($data as $key => $val) {
                    $contract = Contract::where('user_id', '=', $val->user_id)->first();
                    $structure = Structure::where('id', '=', $contract->structure_id)->first();
                    $start_date = date('m/d/Y', strtotime($val->from_date));
                    $monthName = Carbon::createFromFormat('m/d/Y', $start_date)->format('F');
                    $val->month = $monthName;
                    $val->base_salary = $structure['base_salary'];
                }
            } else {
                $data = Payslip::where('user_id', $user_id)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($pageSize);
                foreach ($data as $key => $val) {
                    $contract = Contract::where('user_id', '=', $val->user_id)->first();
                    $structure = Structure::where('id', '=', $contract->structure_id)->first();
                    $start_date = date('m/d/Y', strtotime($val->from_date));
                    $monthName = Carbon::createFromFormat('m/d/Y', $start_date)->format('F');
                    $val->month = $monthName;
                    $val->base_salary = $structure['base_salary'];
                }
            }
            // $timetable = Overtime::orderBy('created_at', 'DESC')->paginate($pageSize);
            return response()->json(
                $data,
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    // overtime compesation
    public function addOvertimeCompesation(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            $position = Position::find($ex->position_id);
            // Carbon::now()->format('Y/m/d H:i:s')]);
            $todayDate = Carbon::now()->format('m/d/Y H:i:s');
            $typedate = Workday::first();
            // type = hour or day
            $validator = Validator::make($request->all(), [
                // 'user_id' => 'required',
                'type' => 'required',
                'reason' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                'duration' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        'message' => $error,
                        'code' => -1,
                    ],
                    201
                );
            } else {
                $duration = 0;
                if ($request['type'] == "hour") {
                    $duration = $request['duration'];
                } else {
                    $pastDF = Carbon::parse($request['from_date']);
                    $pastDT = Carbon::parse($request['to_date']);
                    $duration_in_days =   $pastDT->diffInDays($pastDF);

                    $duration = ($duration_in_days + 1);
                }
                if ($typedate->type_date_time == "server") {
                    $data = Overtimecompesation::create([
                        'user_id' => $ex->id,
                        'type' => $request['type'],
                        'reason' => $request['reason'],
                        'from_date' => $request['from_date'],
                        'to_date' => $request['to_date'],
                        'duration' =>  $duration,
                        'status' =>  "pending",
                        'date' => $todayDate
                    ]);
                } else {
                    $data = Overtimecompesation::create([
                        'user_id' => $ex->id,
                        'type' => $request['type'],
                        'reason' => $request['reason'],
                        'from_date' => $request['from_date'],
                        'to_date' => $request['to_date'],
                        'duration' =>  $duration,
                        'status' =>  "pending",
                        'date' => $request['date'],
                        'created_at' => $request['created_at'],
                        'updated_at' => $request['created_at'],
                    ]);
                }

                $respone = [
                    'message' => 'Success',
                    'code' => 0,
                    // 'data'=>$data->id
                ];
                $notification = new Notice(
                    [
                        'notice' => "Overtime Compesation",
                        'noticedes' => "Employee name : {$ex->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Reason : " . $request['reason'] . "\n" . "From Date : " . $request['from_date']  . "\n" . "To Date :" . $request['to_date'] . "\n" . "Type : " . $request['type'] . "\n" . "Duration :" . $duration . "\n",
                        'telegramid' => Config::get('services.telegram_id')
                    ]
                );
                $notification->notify(new TelegramRegister());
            }
            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
    public function editOvertimeCompesation(Request $request, $id)
    {
        try {
            $data = Overtimecompesation::find($id);
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            $todayDate = Carbon::now()->format('m/d/Y H:i:s');
            $typedate = Workday::first();
            if ($data) {
                $validator = Validator::make($request->all(), [
                    'type' => 'required',
                    'reason' => 'required',
                    'from_date' => 'required',
                    'to_date' => 'required',
                    'duration' => 'required'
                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->all()[0];
                    return response()->json(
                        [
                            'message' => $error,
                            'code' => -1,
                        ],
                        201
                    );
                } else {
                    if ($data->status == "pending") {
                        $duration = 0;
                        if ($request['type'] == "hour") {
                            $duration = $request['duration'];
                        } else {
                            $pastDF = Carbon::parse($request['from_date']);
                            $pastDT = Carbon::parse($request['to_date']);
                            $duration_in_days =   $pastDT->diffInDays($pastDF);

                            $duration = ($duration_in_days + 1);
                        }

                        if ($typedate->type_date_time == "server") {
                            $data->user_id = $ex->id;
                            $data->type = $request['type'];
                            $data->reason = $request['reason'];
                            $data->from_date = $request['from_date'];
                            $data->to_date = $request['to_date'];
                            $data->duration = $duration;
                            $data->date = $todayDate;
                            $data->update();
                        } else {
                            $data->user_id = $ex->id;
                            $data->type = $request['type'];
                            $data->reason = $request['reason'];
                            $data->from_date = $request['from_date'];
                            $data->to_date = $request['to_date'];
                            $data->duration = $duration;
                            $data->date = $request['date'];
                            $data->update();
                        }



                        $respone = [
                            'message' => 'Success',
                            'code' => 0,

                        ];
                    }
                }
            } else {
                $respone = [
                    'message' => 'No compesation id found',
                    'code' => -1,
                ];
            }
            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
    public function deleteOvertimeCompastion($id)
    {
        try {
            $data = Overtimecompesation::find($id);
            if ($data) {
                if ($data->status == "pending") {
                    $data->delete();
                    $respone = [
                        'message' => 'Success',
                        'code' => 0,
                    ];
                } else {
                    $respone = [
                        'message' => 'Cannot delete compesaton that completed',
                        'code' => -1,
                    ];
                }
            } else {
                $respone = [
                    'message' => 'No overtime compesation id found',
                    'code' => -1,
                ];
            }
            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),

                ]
            );
        }
    }
    public function getOvertimeCompesation(Request $request)
    {
        try {
            $pageSize = $request->page_size ?? 10;
            $user_id = $request->user()->id;
            // $postion = Overtimecompesation::orderBy('created_at', 'DESC')->paginate($pageSize);
            $pageSize = $request->page_size ?? 10;
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');

            if ($request->has('from_date') && $request->has('to_date')) {

                $data = Overtimecompesation::where('user_id', $user_id)->whereDate('overtimecompesations.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('overtimecompesations.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
            } else {
                $data = Overtimecompesation::where('user_id', $user_id)
                    ->orderBy('created_at', 'DESC')
                    ->paginate($pageSize);
            }

            return response()->json(

                $data,
                200
            );
        } catch (Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    // get 
    public function getcounter(Request $request)
    {
        try {
            //code...
            // get user by token with timetable
            $use_id = $request->user()->id;
            $todayDate = Carbon::now()->format('m/d/Y');

            $user_id = $request->user()->id;
            // $employee = User::find($use_id);
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $todayDate = Carbon::now()->format('m/d/Y');
            $pageSize = $request->page_size ?? 10;
            if ($request->has('from_date') && $request->has('to_date')) {
                // get sick leave 

                $sick  = Leave::where('user_id', $user_id)
                    ->whereDate('checkins.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('checkins.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->where('checkout_status', 'late')
                    ->count();
                // $early  = Checkin::where('user_id',$user_id)
                // ->whereDate('checkins.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('checkins.created_at', '<=', date('Y-m-d',strtotime($toDate)))
                // ->where('checkout_status','early')
                // ->count();
                $data = Counter::where('user_id', '=', $use_id)->whereDate('counters.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('counters.created_at', '<=', date('Y-m-d', strtotime($toDate)))->first();
            } else {
                $data = Counter::where('user_id', '=', $use_id)->first();
            }
            $respone = [
                'message' => 'Success',
                'code' => 0,
                'data' => $data
            ];



            return response(
                $respone,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    // change holiday
    public function getChangeDayoff(Request $request)
    {
        try {

            $user_id = $request->user()->id;
            // $employee = User::find($use_id);
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $todayDate = Carbon::now()->format('m/d/Y');
            $pageSize = $request->page_size ?? 10;
            if ($request->has('from_date') && $request->has('to_date')) {
                // $late  = Checkin::where('user_id',$user_id)
                // ->whereDate('checkins.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('checkins.created_at', '<=', date('Y-m-d',strtotime($toDate)))
                // ->where('checkout_status','late')

                // ->count();
                // $early  = Checkin::where('user_id',$user_id)
                // ->whereDate('checkins.created_at', '>=', date('Y-m-d',strtotime($fromDate)))
                // ->whereDate('checkins.created_at', '<=', date('Y-m-d',strtotime($toDate)))
                // ->where('checkout_status','early')
                // ->count();

                $data  = Changedayoff::with('workday', 'holiday')->where('user_id', $user_id)->whereDate('changedayoffs.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('changedayoffs.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'DESC')
                    ->paginate($pageSize);
            } else {
                $data = Changedayoff::with('workday', 'holiday')->where('user_id', $user_id)

                    ->orderBy('created_at', 'DESC')
                    ->paginate($pageSize);
            }

            return response()->json(
                $data,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function addChangeholiday(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            $position = Position::find($ex->position_id);
            $todayDate = Carbon::now()->format('m/d/Y H:i:s');

            $validator = Validator::make($request->all(), [
                // 'user_id' => 'required',
                'type' => 'required',
                'reason' => 'required',
                'from_date' => 'required',
                'to_date' => 'required',
                // 'duration' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        'message' => $error,
                        'code' => -1,
                    ],
                    201
                );
            } else {

                // if(user choose change holiday)
                // if($request['holiday_id']){
                //     $Holiday = Holiday::find($request['holiday_id']);

                // }
                $pastDF = Carbon::parse($request['from_date']);
                $pastDT = Carbon::parse($request['to_date']);
                $duration_in_days =   $pastDT->diffInDays($pastDF);

                $duration = ($duration_in_days + 1);

                $data = Changedayoff::create([
                    'user_id' => $ex->id,
                    'type' => $request['type'],
                    'reason' => $request['reason'],
                    'from_date' => $request['from_date'],
                    'to_date' => $request['to_date'],
                    'duration' => $duration,
                    'date' => $todayDate,
                    'status' =>  "pending",
                    'holiday_id' => $request['holiday_id'],
                    'workday_id' => $request['workday_id']
                ]);

                $respone = [
                    'message' => 'Success',
                    'code' => 0,

                ];

                $notification = new Notice(
                    [
                        'notice' => "Notification Change Dayoff",
                        'noticedes' => "Employee name : {$ex->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Reason : " . $request['reason'] . "\n" . "From Date : " . $request['from_date']  . "\n" . "To Date :" . $request['to_date'] . "\n" . "\n" . "Duration :" . $duration . "\n",
                        'telegramid' => Config::get('services.telegram_id')
                    ]
                );
                $notification->notify(new TelegramRegister());
            }
            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
    // get public holiday
    public function getPH(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            // $position = Position::find($ex->position_id);
            // $todayDate = Carbon::now()->format('m/d/Y H:i:s' );
            $from_date = Carbon::now()->format('Y-m-d');
            $to_date = Carbon::now()->format('Y-m-d');

            // $result1 = Carbon::createFromFormat('Y-m-d',$from_date)->isPast();
            // $result2 = Carbon::createFromFormat('Y-m-d',  $to_date)->isPast();
            $ph = Holiday::whereDate('from_date', '>=', $from_date)
                ->whereDate('to_date', '>=', $to_date)->get();

            return response()->json(
                [
                    'code' => 0,
                    'message' => 'Success',
                    'data' => $ph
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
    public function getWorkday(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            $ex = User::find($user_id);

            $ph = Workday::all();

            return response()->json(
                [
                    'code' => 0,
                    'message' => 'Success',
                    'data' => $ph
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
    public function editChangeholiday(Request $request, $id)
    {
        try {
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            $position = Position::find($ex->position_id);
            $todayDate = Carbon::now()->format('m/d/Y H:i:s');
            $data =  Changedayoff::find($id);
            if ($data) {
                $validator = Validator::make($request->all(), [
                    // 'user_id' => 'required',
                    'type' => 'required',
                    'reason' => 'required',
                    'from_date' => 'required',
                    'to_date' => 'required',
                    // 'duration' => 'required'
                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->all()[0];
                    return response()->json(
                        [
                            'message' => $error,
                            'code' => -1,
                        ],
                        201
                    );
                } else {
                    $pastDF = Carbon::parse($request['from_date']);
                    $pastDT = Carbon::parse($request['to_date']);
                    $duration_in_days =   $pastDT->diffInDays($pastDF);

                    $duration = ($duration_in_days + 1);
                    if ($data->status == "pending") {
                        $data->user_id = $ex->id;
                        $data->type = $request['type'];
                        $data->reason = $request['reason'];
                        $data->from_date = $request['from_date'];
                        $data->to_date = $request['to_date'];
                        $data->holiday_id = $request['holiday_id'];
                        $data->workday_id = $request['workday_id'];
                        $data->date =  $todayDate;
                        $data->duration = $duration;

                        $data->update();
                    }

                    $respone = [
                        'message' => 'Success',
                        'code' => 0,


                    ];

                    // $notification = new Notice(
                    //     [
                    //         'notice' => "Notification Change Dayoff",
                    //         'noticedes' => "Employee name : {$ex->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Reason : " . $request['reason'] . "\n" . "From Date : " . $request['from_date']  . "\n" . "To Date :" . $request['to_date'] . "\n" . "\n" . "Duration :" . $duration . "\n",
                    //         'telegramid' => Config::get('services.telegram_id')
                    //     ]
                    // );
                    // $notification->notify(new TelegramRegister());
                }
            } else {
                $respone = [
                    'message' => 'No changeday off id found',
                    'code' => -1,


                ];
            }

            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
    public function deleteChangedayoff($id)
    {
        try {
            $data = Changedayoff::find($id);
            if ($data) {
                if ($data->status == "pending") {
                    $data->delete();
                    $respone = [
                        'message' => 'Success',
                        'code' => 0,
                    ];
                } else {
                    $respone = [
                        'message' => 'Cannot delete change dayoff that completed',
                        'code' => -1,
                    ];
                }
            } else {
                $respone = [
                    'message' => 'No dayoff id found',
                    'code' => -1,
                ];
            }
            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),

                ]
            );
        }
    }
    // request leaveout
    // 
    public function getLeaveout(Request $request)
    {
        try {

            $user_id = $request->user()->id;
            // $employee = User::find($use_id);
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $todayDate = Carbon::now()->format('m/d/Y');
            $pageSize = $request->page_size ?? 10;
            if ($request->has('from_date') && $request->has('to_date')) {


                $data  = Leaveout::where('user_id', $user_id)->whereDate('leaveouts.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('leaveouts.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'DESC')
                    ->paginate($pageSize);
            } else {
                $data = Leaveout::where('user_id', $user_id)

                    ->orderBy('created_at', 'DESC')
                    ->paginate($pageSize);
            }

            return response()->json(
                $data,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function addLeaveout(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            $position = Position::find($ex->position_id);
            $todayDate = Carbon::now()->format('m/d/Y');

            $validator = Validator::make($request->all(), [
                'reason' => 'required',
                // 'type' => 'required',
                // 'reason' => 'required',
                'time_in' => 'required',
                'time_out' => 'required',
                // 'duration' => 'required'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        'message' => $error,
                        'code' => -1,
                    ],
                    201
                );
            } else {
                $typedate = Workday::first();

                // $duration = ($duration_in_days + 1);
                $t1 = Carbon::parse($request['time_in']);
                $t2 = Carbon::parse($request['time_out']);
                $diff = $t1->diff($t2);
                $h = $diff->h;
                $mn = $diff->i;
                $duration = 0;
                $type = "";
                if ($mn <= 0) {
                    $mn = 0;
                    $type = "hour";
                    // store duration as hour
                    $duration = $h;
                } else {
                    // store duration as mn
                    $type = "minute";
                    $duration = ($h * 60) + $mn;
                }

                if ($typedate->type_date_time == "server") {
                    $data = Leaveout::create([
                        'user_id' => $ex->id,
                        'type' => $type,
                        'reason' => $request['reason'],
                        'time_in' => $request['time_in'],
                        'time_out' => $request['time_out'],
                        'duration' =>  $duration,
                        'status' =>  "pending",
                        'date' => $todayDate
                    ]);
                } else {
                    $data = Leaveout::create([
                        'user_id' => $ex->id,
                        'type' => $type,
                        'reason' => $request['reason'],
                        'time_in' => $request['time_in'],
                        'time_out' => $request['time_out'],
                        'duration' =>  $duration,
                        'status' =>  "pending",
                        'date' => $request['date'],
                        'created_at' => $request['created_at'],
                        'updated_at' => $request['created_at'],
                    ]);
                }

                $respone = [
                    'message' => 'Success',
                    'code' => 0,
                ];

                $notification = new Notice(
                    [
                        'notice' => "Notification Change Dayoff",
                        'noticedes' => "Employee name : {$ex->name}" . "\n" . "Position : {$position->position_name}" . "\n" . "Reason : " . $request['reason'] . "\n" . "From Date : " . $request['from_date']  . "\n" . "To Date :" . $request['to_date'] . "\n" . "\n" . "Duration :" . $request['duration'] . "\n",
                        'telegramid' => Config::get('services.telegram_id')
                    ]
                );
                $notification->notify(new TelegramRegister());
            }
            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
    public function editLeaveout(Request $request, $id)
    {
        try {
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            $position = Position::find($ex->position_id);
            $data =  Leaveout::find($id);
            $todayDate = Carbon::now()->format('m/d/Y');
            if ($data) {
                $validator = Validator::make($request->all(), [

                    'reason' => 'required',
                    'time_in' => 'required',
                    'time_out' => 'required',

                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->all()[0];
                    return response()->json(
                        [
                            'message' => $error,
                            'code' => -1,
                        ],
                        201
                    );
                } else {
                    $typedate = Workday::first();
                    $t1 = Carbon::parse($request['time_in']);
                    $t2 = Carbon::parse($request['time_out']);
                    $diff = $t1->diff($t2);
                    $h = $diff->h;
                    $mn = $diff->i;
                    $duration = 0;
                    $type = "";
                    if ($mn <= 0) {
                        $mn = 0;
                        $type = "hour";
                        // store duration as hour
                        $duration = $h;
                    } else {
                        // store duration as mn
                        $type = "minute";
                        $duration = ($h * 60) + $mn;
                    }

                    if ($data->status == "pending") {

                        if ($typedate->type_date_time == "server") {
                            $data->user_id = $ex->id;
                            // $data->type = $request['type'];
                            $data->reason = $request['reason'];
                            $data->time_in = $request['time_in'];
                            $data->time_out = $request['time_out'];
                            $data->duration = $duration;
                            $data->type = $type;
                            $data->date = $todayDate;
                            $data->update();
                        } else {
                            $data->user_id = $ex->id;
                            // $data->type = $request['type'];
                            $data->reason = $request['reason'];
                            $data->time_in = $request['time_in'];
                            $data->time_out = $request['time_out'];
                            $data->duration = $duration;
                            $data->type = $type;
                            $data->date = $request['date'];
                            $data->update();
                        }
                    }

                    $respone = [
                        'message' => 'Success',
                        'code' => 0,


                    ];
                }
            } else {
                $respone = [
                    'message' => 'No leaveout id found',
                    'code' => -1,


                ];
            }

            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
    public function deleteLeaveout(Request $request, $id)
    {
        try {
            $data = Leaveout::find($id);
            if ($data) {
                if ($data->status == "pending") {
                    $data->delete();
                    $respone = [
                        'message' => 'Success',
                        'code' => 0,
                    ];
                } else {
                    $respone = [
                        'message' => 'Cannot delete leaveout that completed',
                        'code' => -1,
                    ];
                }
            } else {
                $respone = [
                    'message' => 'No dayoff id found',
                    'code' => -1,
                ];
            }
            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {

            return response()->json(
                [
                    'message' => $e->getMessage(),

                ]
            );
        }
    }
    // chief department
    public function getleaveOutChief(Request $request)
    {
        try {
            $use_id = $request->user()->id;
            $ex = User::find($use_id);
            $pageSize = $request->page_size ?? 10;
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $todayDate = Carbon::now()->format('m/d/Y');
            if ($request->has('from_date') && $request->has('to_date')) {
                $user = Leaveout::select('leaveouts.*', 'users.name')
                    ->join('users', 'users.id', '=', 'leaveouts.user_id')
                    ->where('users.department_id', '=', $ex->department_id)
                    ->whereDate('leaveouts.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('leaveouts.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
            } else {
                $user = Leaveout::select('leaveouts.*', 'users.name')
                    ->join('users', 'users.id', '=', 'leaveouts.user_id')
                    ->where('users.department_id', '=', $ex->department_id)

                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
            }
            return response(
                $user,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function editLeaveOutChief(Request $request, $id)
    {
        try {
            //code...
            // get user by token with timetable
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            $data = Leaveout::find($id);

            $todayDate = Carbon::now()->format('m/d/Y');
            $timeNow = Carbon::now()->format('H:i:s');
            $leaveDuction = 0;
            $status = "";
            $message = "";
            if ($data) {
                $validator = Validator::make($request->all(), [
                    'status' => 'required',
                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->all()[0];
                    return response()->json(
                        [
                            'message' => $error,
                            'code' => -1,
                        ],
                        201
                    );
                } else {

                    if ($request["status"] == 'approved') {
                        $status = 'approved';
                    } else {
                        $status = 'rejected';
                    }
                    $data->status = $status;
                    $data->approve_by = $ex->name;
                    $query = $data->update();
                    $profile = User::find($data->user_id);
                    //  $startDate = date('Y-m-d',strtotime($data->from_date));

                    if ($data->status == "rejected") {
                        $message = "Your leaveout request has been rejected!";
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                        ];
                    }
                    if ($data->status == 'approved') {

                        $message = "your leaveout request has been approved!";
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                        ];
                    }
                    if ($profile->device_token) {
                        $url = 'https://fcm.googleapis.com/fcm/send';
                        $dataArr = array(
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'id' => $request->id,
                            'status' => "done",

                        );
                        $notification = array(
                            'title' => $message,
                            'text' => $message,
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
                            'to' => $profile->device_token,
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
            } else {
                $respone = [
                    'message' => 'No leave id found',
                    'code' => -1,


                ];
            }

            return response(
                $respone,
                200
            );
        } catch (Exception $e) {

            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    // security
    public function getleaveOutSecurity(Request $request)
    {
        try {
            $use_id = $request->user()->id;
            $ex = User::find($use_id);
            $pageSize = $request->page_size ?? 10;
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $todayDate = Carbon::now()->format('m/d/Y');
            if ($request->has('from_date') && $request->has('to_date')) {
                $user = Leaveout::with('user')
                    ->whereDate('leaveouts.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('leaveouts.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
            } else {
                $user = Leaveout::with('user')

                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
            }
            return response(
                $user,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function editLeaveOutSecurity(Request $request, $id)
    {
        try {
            //code...
            // get user by token with timetable
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            $data = Leaveout::find($id);
            $todayDate = Carbon::now()->format('m/d/Y');
            $timeNow = Carbon::now()->format('H:i:s');
            $leaveDuction = 0;
            $status = "";
            $message = "";
            $note = "";
            if ($data) {
                $validator = Validator::make($request->all(), [
                    'status' => 'required',
                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->all()[0];
                    return response()->json(
                        [
                            'message' => $error,
                            'code' => -1,
                        ],
                        201
                    );
                } else {

                    if ($request["status"] == 'Uncomplete') {
                        $status = 'uncompleted';
                    } else {
                        $status = 'completed';
                    }


                    if ($status == "uncompleted") {

                        $data->status = $status;
                        $data->check_by = $ex->name;
                        $data->note = $request["note"];
                        $query = $data->update();
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                        ];
                    } else {
                        $data->status = $status;
                        $data->check_by = $ex->name;
                        $data->note = $request["note"];
                        $query = $data->update();
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                        ];
                    }
                }
            } else {
                $respone = [
                    'message' => 'No leaveout id found',
                    'code' => -1,


                ];
            }

            return response(
                $respone,
                200
            );
        } catch (Exception $e) {

            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function calculateTime(Request $reqesut)
    {
        try {
            // $t1 = Carbon::parse('2016-07-05 12:29:16');
            // $t2 = Carbon::parse('2016-07-04 13:30:10');
            $t1 = Carbon::parse('17:30:00');
            $t2 = Carbon::parse('20:03:00');
            $diff = $t1->diff($t2);
            return response(
                [
                    'h' => $diff->h,
                    'mn' => $diff->i
                ],
                200
            );
        } catch (Exception $e) {

            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    // dayofff chief
    public function getDayoffChief(Request $request)
    {
        try {
            $use_id = $request->user()->id;
            $ex = User::find($use_id);
            $pageSize = $request->page_size ?? 10;
            $fromDate = $request->get('from_date');
            $toDate = $request->get('to_date');
            $todayDate = Carbon::now()->format('m/d/Y');
            if ($request->has('from_date') && $request->has('to_date')) {
                // $data  = Changedayoff::where('user_id', $user_id)->whereDate('changedayoffs.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                //     ->whereDate('changedayoffs.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                //     ->orderBy('created_at', 'DESC')
                //     ->paginate($pageSize);
                $user = Changedayoff::select('changedayoffs.*', 'users.name')
                    ->join('users', 'users.id', '=', 'changedayoffs.user_id')
                    ->where('users.department_id', '=', $ex->department_id)
                    ->whereDate('changedayoffs.created_at', '>=', date('Y-m-d', strtotime($fromDate)))
                    ->whereDate('changedayoffs.created_at', '<=', date('Y-m-d', strtotime($toDate)))
                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
            } else {
                $user = Changedayoff::select('changedayoffs.*', 'users.name')
                    ->join('users', 'users.id', '=', 'changedayoffs.user_id')
                    ->where('users.department_id', '=', $ex->department_id)

                    ->orderBy('created_at', 'DESC')->paginate($pageSize);
            }
            return response(
                $user,
                200
            );
        } catch (Exception $e) {
            //throw $th;
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function editDayoffChief(Request $request, $id)
    {
        try {
            //code...
            // get user by token with timetable
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            $data = Changedayoff::find($id);

            $todayDate = Carbon::now()->format('m/d/Y');
            $timeNow = Carbon::now()->format('H:i:s');
            $leaveDuction = 0;
            $status = "";
            $message = "";
            if ($data) {
                $validator = Validator::make($request->all(), [
                    'status' => 'required',
                ]);
                if ($validator->fails()) {
                    $error = $validator->errors()->all()[0];
                    return response()->json(
                        [
                            'message' => $error,
                            'code' => -1,
                        ],
                        201
                    );
                } else {

                    if ($request["status"] == 'approved') {
                        $status = 'approved';
                    } else {
                        $status = 'rejected';
                    }
                    $data->status = $status;
                    $data->approve_by = $ex->name;
                    $query = $data->update();
                    $profile = User::find($data->user_id);
                    //  $startDate = date('Y-m-d',strtotime($data->from_date));

                    if ($data->status == "rejected") {
                        $message = "Your leaveout request has been rejected!";
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                        ];
                    }
                    if ($data->status == 'approved') {

                        $message = "your leaveout request has been approved!";
                        $respone = [
                            'message' => 'Success',
                            'code' => 0,
                        ];
                    }
                    if ($profile->device_token) {
                        $url = 'https://fcm.googleapis.com/fcm/send';
                        $dataArr = array(
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'id' => $request->id,
                            'status' => "done",

                        );
                        $notification = array(
                            'title' => $message,
                            'text' => $message,
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
                            'to' => $profile->device_token,
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
            } else {
                $respone = [
                    'message' => 'No leave id found',
                    'code' => -1,


                ];
            }

            return response(
                $respone,
                200
            );
        } catch (Exception $e) {
            return response([
                'message' => $e->getMessage()
            ]);
        }
    }
    public function addMyReminder(Request $request)
    {
        try {
            $user_id = $request->user()->id;
            $ex = User::find($user_id);
            $validator = Validator::make($request->all(), [
                'is_reminder' => 'required',
                'remind_from_date' => 'required',
                'before_timein' => 'required',
                'before_timeout' => 'required',
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(
                    [
                        'message' => $error,
                        'code' => -1,
                    ],
                    201
                );
            } else {

                $ex->is_reminder = "true";
                $ex->remind_from_date = $request['remind_from_date'];
                $ex->before_timein= $request['before_timein'];
                $ex->before_timeout= $request['before_timeout'];
                $ex->update();


                

                $respone = [
                    'message' => 'Success',
                    'code' => 0,

                ];

                
            }
            return response()->json(
                $respone,
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ]
            );
        }
    }
}
