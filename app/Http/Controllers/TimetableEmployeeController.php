<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Employee;
use App\Models\Timetable;
use App\Models\TimetableEmployee;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TimetableEmployeeController extends Controller
{

    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        // 'unique' => 'This :attribute has already been taken.',
        // 'max' => ':Attribute may not be more than :max characters.',
    ];
    public function index()
    {
            $ex1 = TimetableEmployee::all();
            foreach($ex1 as $key=>$val){
                    $ex2 =  User::where('id',$val->user_id)->first();
                    $time = Timetable::where('id',$val->timetable_id)->first();
                    $val->emplyee = $ex2;
                    $val->timetable = $time;
            }
            //
            // $data = Employee::with('timetable')->paginate( $pageSize);
            // $data = Employee::find(1)->timetable;

            if (request()->ajax()) {
                return datatables()->of($ex1)
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
            }
            return view('admin.schedule.schedule');

    }
    public function getComponent(){
        $data = User::whereNotIn('id', [1])->orderBy('created_at', 'DESC')->get();
        $work = Timetable::all();
        if($data){
            return response()->json([
                "status"=>200,
                "data"=>$data,
                "time"=>$work
            ]);
        }else{
            return response()->json([
                "status"=>404,
                "data"=>"Data not found!"
            ]);
        }
    }


    public function edit($id)
    {
        $data = TimetableEmployee::find($id);
        $timetable = Timetable::all();
        $user= User::whereNotIn('id', [1])->get();
        return response()->json(['data'=>$data,'timetable'=>$timetable,'user'=>$user]);



    }
    public function update(Request $r,$id)
    {
        request()->validate([
            'user_id' => 'required|string',
            'timetable_id'      => 'required|string',


        ], $this->customMessages);
        $data = TimetableEmployee::find($id);
        $data->update([
            'user_id'           => strip_tags(request()->post('user_id')),
            'timetable_id'          => strip_tags(request()->post('timetable_id')),
        ]);
        return response()->json($data);

    }

    public function delete($id){
        try {
            $data = TimetableEmployee::find($id);
            if($data){
                $emp =Checkin ::where('employee_id', $data->employee_id)->first();
                if($emp){
                    $respone = [
                        'message'=>'Cannot delete this timetable',
                        'code'=>-1,

                    ];
                }else{
                    $data->delete();
                    $respone = [
                        'message'=>'Success',
                        'code'=>0,
                    ];
                }
            }else{

                    $respone = [
                        'message'=>'No schedule id found',
                        'code'=>-1,
                    ];
            }
            return response($respone,200);
        } catch (Exception $e) {

                return response()->json(
                [
                    'message'=>$e->getMessage(),

                ]
            );
        }


    }

    public function create()
    {
        $employee = Employee::all();
        $timetable = Timetable::all();
        return view('admin.employee.create_employee_schedule',compact('employee','timetable'));
    }


    public function store(Request $request)
    {
        request()->validate([
            'user_id' => 'required|string',
            'timetable_id'      => 'required|string',


        ], $this->customMessages);

        $data = TimetableEmployee::create([
            'user_id'           => strip_tags(request()->post('user_id')),
            'timetable_id'          => strip_tags(request()->post('timetable_id')),

        ]);
        return response()->json($data);
    }

    public function show($id)
    {
        //
    }


    public function destroy($id)
    {
        $data = TimetableEmployee::find($id);
        if($data){
             // check if position id belong to employee table
             $emp = Checkin::where('user_id', $data->user_id)->first();
             if($emp){
                 $respone = [
                     'message'=>'cannot delete schedule who already used by employee',
                     'code'=>-1,
                 ];
             }else{
                 $data->delete();
                 $respone = [
                     'message'=>'Success',
                     'code'=>0,
                 ];
             }
        }else{
            $respone = [
                'message'=>'No schedule id found',
                'code'=>-1,

            ];
        }
        return response()->json(
            $respone ,200
        );
    }
}
