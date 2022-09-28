<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeTimetable;
use App\Models\Timetable;
use App\Models\TimetableEmployee;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Button;
use Exception;

class TimetableController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        // 'unique' => 'This :attribute has already been taken.',
        // 'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Timetable::orderBy('created_at', 'DESC')->get())
            ->addColumn('action', 'admin.users.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.settings.timetable');
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        request()->validate([
            'timetable_name' => 'required|string',
            'on_duty_time'      => 'required|string',
            'off_duty_time'     => 'required|string',

        ], $this->customMessages);
        $data = new Timetable();
        $data->timetable_name   = $request->timetable_name;
        $data->on_duty_time   = $request->on_duty_time;
        $data->off_duty_time   = $request->off_duty_time;
        $late ='';
        $early='';
        if($request->late_minute){
            $late = $request->late_minute;
        }else{
            $late = "0";
        }
        if($request->early_leave){
            $early= $request->early_leave;
        }else{
            $early = "0";
        }
        $data->late_minute=$late;
        $data->early_leave= $early;
        $query = $data->save();
        return response()->json($query);
        // $data = Timetable::create([
        //     'timetable_name'           => strip_tags(request()->post('timetable_name')),
        //     'on_duty_time'          => strip_tags(request()->post('on_duty_time')),
        //     'off_duty_time'         => strip_tags(request()->post('off_duty_time')),
        //     'late_minute'       => strip_tags(request()->post('late_minute')),
        //     'early_leave'       => strip_tags(request()->post('early_leave')),

        // ]);
        return response()->json($data);


    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $data = Timetable::findOrFail($id);
        return response()->json($data);

    }
    public function update(Request $request,$id)
    {
        request()->validate([
            'timetable_name' => 'required|string',
            'on_duty_time'      => 'required|string',
            'off_duty_time'     => 'required|string',

        ], $this->customMessages);
        //code...
        // $i = $request->id;
        // $data = Timetable::find($id);
        $data = Timetable::findOrFail($id);
        $data->timetable_name   = $request->timetable_name;
        $data->on_duty_time   = $request->on_duty_time;
        $data->off_duty_time   = $request->off_duty_time;
        $late ='';
        $early='';
        if($request->late_minute){
            $late = $request->late_minute;
        }else{
            $late = "0";
        }
        if($request->early_leave){
            $early= $request->early_leave;
        }else{
            $early = "0";
        }
        $data->late_minute=$late;
        $data->early_leave= $early;
        $query = $data->update();

        return response()->json($query);
    }

    public function delete($id){


    }
    public function destroy($id)
    {
        $data = Timetable::find($id);
        if($data){
            $emp =TimetableEmployee ::where('timetable_id', $data->id)->first();
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
        }
        return response($respone,200);
    }
}
