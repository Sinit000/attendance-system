<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Checkout;
use App\Models\Employee;
use App\Models\Leave;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;
// use Barryvdh\DomPDF\Facade as PDF;
use PDF;
// use Barryvdh\DomPDF\PDF;
use DB;


class ReportController extends Controller
{

    public function index()
    {
        $todayDate = Carbon::now()->format('m/d/Y');

        $employ=Employee:: where('check_date',$todayDate)
        ->where('status','present')

        // ->whereNotNull('check_date')
        // whereNotNull('check_date')

        ->get();

        // $employee = Employee::join('checkins','checkins.employee_id','employees.id')
        // ->select('employees.name','employees.check_date')
        //  ->where('employees.check_date','=',$todayDate)
        //  ->where('checkins.date','=',$todayDate)
        // ->get();

        return view('admin.report');
    }
    public function preview()
    {
        $todayDate = Carbon::now()->format('m/d/Y');
        $checkin = Checkin::with('employee')->get();
        $leave = Leave::with('employee')->get();
        $employee = Employee::where('status', "absent")->get();
        return view('pdf_preview',compact('checkin','leave','employee','todayDate'));
    }
    public function getpdf(Request $request) {
        // retreive all records from db
        $todayDate = Carbon::now()->format('m/d/Y');
        $checkin = Checkin::with('employee')->get();
        $leave = Leave::with('employee')->get();
        $employee = Employee::all();
        // $checkin = Checkin::with('employee')->where('date', $todayDate)->get();
        // try {
        //     if($checkin){
        //         return response()->json([
        //             "code"=>0,
        //             "message"=>"Success",
        //             "data"=>$checkin,
        //             "leave"=>$leave,
        //             "employee"=>$employee,
        //         ]);
        //     }else{
        //         return response()->json([
        //             "code"=>-1,
        //             "message"=>"Data not found!"
        //         ]);
        //     }
        // } catch (Exception $e) {
        //     //throw $th;
        //     return response([
        //         'message'=>$e->getMessage()
        //     ]);
        // }


        // $mydata = compact('employee');
        // share data to view
        view()->share( compact('checkin','leave','employee'));
        // $pdf = PDF::loadView('pdf_view',  compact('checkin','leave','employee'));
        // $pdf = PDF::loadView('pdf_view',compact('data'));
        // download PDF file with download method
        // return $pdf->download('pdf_file.pdf');
        if($request->has('download')){
            $pdf = PDF::loadView('pdf_preview');
            return $pdf->download('report.pdf');
        }
        return view('pdf_preview');
      }
    public function get(){
        // $todayDate = Carbon::now()->format('m/d/Y');
        // return response(['today'=> $todayDate]);
        // $checkin = Checkin::with('employee','employee.timetable')->get();
        // $checkin = Checkin::all();

        // Model::whereNotNull('sent_at');
        // abset
        // $em=Employee::select('name')
        // ->where('check_date', '!=' ,$todayDate)
        // whereNotNull('check_date')

        // ->get();
        // $da= $em->updated_at;
        // select('updated_at')->get();
        // $now=  Str::substr($da, 0,10);
        // $update = $em->created_at;
        // present
        // $employee = Employee::join('checkins','checkins.employee_id','employees.id')
        // ->select('employees.name','employees.check_date')
        //  ->where('employees.check_date','=',$todayDate)
        //  ->where('checkins.date','=',$todayDate)
        // ->get();
        // leftJoin
        // $employee = Employee::select('employees.name')
        // ->innerjoin('checkins at check','check.employee_id','=','employees.id')
        // ->where('employees.status','=','')
        // ->where('employees.updated_at','=',$todayDate)
        // ->get();
        // return response(['today'=> $employee,'em'=>$em]);

        try {
            $todayDate = Carbon::now()->format('m/d/Y');
            $checkin=Checkin::with('employee','employee.timetable')
            ->where('date','=',$todayDate)
            ->get();
            // $checkin = Checkin::select('checkins.id','checkins.checkin_time','checkins.date','checkins.checkin_status','checkins.checkin_late','st.name')
            // ->where('checkins.checkin_status','=','late')
            // ->join('employees as st', 'st.id', '=', 'checkins.employee_id')
            // ->join('timetables as time','time.id', '=', 'checkins.timetable_id')
            // ->get();

            return  DataTables::of($checkin)
            ->addIndexColumn()
            // ->addColumn('checkbox', function($row){
            //     return '<input type="checkbox" name="checkbox" data-id="'.$row['id'].'"><label></label>';
            // })
            // ->rawColumns(['actions','checkbox'])
            ->make(true);
        } catch (Exception $e) {
            //throw $th;
            return response()->json(
             [
                 'message'=>$e->getMessage(),
                 // 'data'=>[]
             ]
            );
        }


    }
    public function checkin(){
        return view('admin.checkin');
    }
    public function checkout(){
        return view('admin.checkout');
    }
    public function getCheckout(){
        try {
            $todayDate = Carbon::now()->format('m/d/Y');
            $checkin=Checkin::with('employee','employee.timetable')
            ->whereNotNull('checkout_time')
            ->where('date','=',$todayDate)
            ->get();


            return  DataTables::of($checkin)
            ->addIndexColumn()


            ->rawColumns(['actions'])
            ->make(true);
        } catch (Exception $e) {
            //throw $th;
            return response()->json(
             [
                 'message'=>$e->getMessage(),
                 // 'data'=>[]
             ]
            );
        }

    }
    public function late(){
        return view('admin.latetime');
    }
    public function getlate(){
        // $checkin = Checkin::all();
        // $checkin = Checkin::select('checkins.id','checkins.checkin_time','checkins.date','checkins.checkin_status','checkins.checkin_late','st.name','time.on_duty_time')
        // ->where('checkins.checkin_status','=','late')
        // ->join('employees as st', 'st.id', '=', 'checkins.employee_id')
        // ->join('timetables as time','time.id', '=', 'checkins.timetable_id')
        // ->get();
        try {
            $checkin = Checkin::with('employee','employee.timetable')
         // ->whereNotNull('checkout_time')
            ->where('checkin_status','late')
            ->get();
            return  DataTables::of($checkin)
            ->addIndexColumn()


            ->rawColumns(['actions'])
            ->make(true);
        } catch (Exception $e) {
            //throw $th;
            return response()->json(
             [
                 'message'=>$e->getMessage(),
                 // 'data'=>[]
             ]
            );
        }

    }

    public function overtime(){
        return view('admin.overtime');
    }
    public function getovertime(){
        try {
            $checkin = Checkin::with('employee','employee.timetable')

            ->where('checkout_status','good')
            ->get();
            return  DataTables::of($checkin)
            ->addIndexColumn()


            ->rawColumns(['actions'])
            ->make(true);
        }  catch (Exception $e) {
            //throw $th;
            return response()->json(
             [
                 'message'=>$e->getMessage(),
                 // 'data'=>[]
             ]
            );
        }

    }


    public function getCheckin(){
        try {
            $todayDate = Carbon::now()->format('m/d/Y');
            $checkin=Checkin::with('employee','employee.timetable')
            ->where('date','=',$todayDate)
            ->get();
            // $checkin = Checkin::select('checkins.id','checkins.checkin_time','checkins.date','checkins.checkin_status','checkins.checkin_late','st.name')
            // ->where('checkins.checkin_status','=','late')
            // ->join('employees as st', 'st.id', '=', 'checkins.employee_id')
            // ->join('timetables as time','time.id', '=', 'checkins.timetable_id')
            // ->get();

            return  DataTables::of($checkin)
            ->addIndexColumn()
            // ->addColumn('checkbox', function($row){
            //     return '<input type="checkbox" name="checkbox" data-id="'.$row['id'].'"><label></label>';
            // })
            // ->rawColumns(['actions','checkbox'])
            ->make(true);
        } catch (Exception $e) {
            //throw $th;
            return response()->json(
             [
                 'message'=>$e->getMessage(),
                 // 'data'=>[]
             ]
            );
        }


    }
    public function leave(){
        return view('admin.leave');
    }



    public function getleave()
    {
       try {
        $checkin = Leave::select('leaves.id','leaves.reason','leaves.from_date','leaves.to_date','leaves.number','leaves.status','leaves.date','st.name')
        // ->where('checkins.checkout_status','=','good')
        ->join('employees as st', 'st.id', '=', 'leaves.employee_id')
        // ->join('timetables as time','time.id', '=', 'checkins.timetable_id')
        ->get();
        return  DataTables::of($checkin)
        ->addIndexColumn()


        ->rawColumns(['actions'])
        ->make(true);
       }catch (Exception $e) {

            return response()->json(
            [
                'message'=>$e->getMessage(),

            ]
            );
        }

    }
    public function editleave($id)
    {
        try {
            $data = Leave::find($id);
            if($data){
                return response()->json([
                    "status"=>200,
                    "data"=>$data
                ]);
            }else{
                return response()->json([
                    "status"=>404,
                    "data"=>"Data not found!"
                ]);
            }

        }  catch (Exception $e) {

            return response()->json(
             [
                 'message'=>$e->getMessage(),

             ]
            );
        }

    }
    public function updateleave(Request $request)
    {
        try {
            $todayDate = Carbon::now()->format('m/d/Y');
            $timeNow = Carbon::now()->format('H:i:s');

            //code...
            $i = $request->cid;
            $data = Leave::find($i);
            $status="";
            if($request->status == 'approve'){
               $status = 'approved';
            }else{
                $status = 'rejected';
            }
            $data->status = $status;
            $query = $data->save();
            $profile = Employee::find($data->employee_id);
            $startDate = date('Y-m-d',strtotime($data->from_date));
            $endDate = date('Y-m-d',strtotime($data->to_date));
            if($data->status=='approved'){
                // 03/23/22
                // end 23/03/22
                while($startDate<=$endDate)
                         {
                            $att=new Checkin();
                            $att->employee_id=$profile->id;
                            $att->status='leave';
                            $att->checkin_time = $timeNow;
                            $att->checkout_time = $timeNow;
                            $att->checkin_late = "0";
                            $att->checkin_status = "leave";
                            $att->date = $startDate;
                            $att->created_at=$startDate;
                            $att->save();
                            $startDate=date('m/d/Y',strtotime($startDate.'+1 day'));
                        }


            }

            //if Accept check datetime of leave adn compare with datetime .now
            // if($data->from_date == $todayDate){
            //     $profile->status_leave="leave";
            //     $profile->status="leave";
            //     $profile->check_date=$todayDate;
            // }elseif($data->from_date == $todayDate && $data->to_date !=$todayDate){
            //     $todate = $data->to_date;
            //     // if to date
            //     // 30/03/2022 ,
            //     if( $todate-$todayDate >0){
            //         $profile->status_leave="leave";
            //     }elseif($todate-$todayDate ==0){
            //         $profile->status='';
            //     }
            // }
            // $profile->save();



            if($query){
                return response()->json(['code'=>0, 'message'=>'Data have Been updated']);
            }else{
                return response()->json(['code'=>-1, 'message'=>'Something went wrong']);
            }
        } catch (Exception $e) {
           return response([
               'message'=> $e->getMessage()
           ]);
        }

    }

    public function deleteleave($id){
        try {
            $data = Leave::find($id);
            $data->delete();
            return response([
                'status'=>200,
                'message'=>"Success",
            ]);
        } catch (Exception $e) {
            return response([
                'message'=> $e->getMessage()
            ]);
         }


    }


    public function report(request $request)
    {
        $present=0;
        $absents=0;
        $leaves=0;
        $percentage=0;
        if(null!=$request->input('from') && null!=$request->input('to')){
        $from=$request->input('from');
        $to=$request->input('to');
         $attendance=DB::table('employees')->join('checkins','checkins.employee_id','=','employees.id')
         ->select('*')
         ->whereDate('checkins.created_at', '>=', date('Y-m-d',strtotime($from)))
         ->whereDate('checkins.created_at', '<=', date('Y-m-d',strtotime($to)))->get();
        //  $present=DB::table('checkins')
        //  ->whereDate('created_at', '>=', date('Y-m-d',strtotime($from)))
        //  ->whereDate('created_at', '<=', date('Y-m-d',strtotime($to)))->where('status','=','present')->count();
        //  $absents=DB::table('checkins')
        //  ->whereDate('created_at', '>=', date('Y-m-d',strtotime($from)))
        //  ->whereDate('created_at', '<=', date('Y-m-d',strtotime($to)))->where('status','=','absent')->count();
        //  $leaves=DB::table('checkins')
        //  ->whereDate('created_at', '>=', date('Y-m-d',strtotime($from)))
        //  ->whereDate('created_at', '<=', date('Y-m-d',strtotime($to)))->where('status','=','leave')->count();
        //  $total=DB::table('checkins')
        //  ->whereDate('created_at', '>=', date('Y-m-d',strtotime($from)))
        //  ->whereDate('created_at', '<=', date('Y-m-d',strtotime($to)))->count();
        //  if($total>0){
        //         $percentage=($present/$total)*100;
        //     }


                return view('admin.report')->with('attendance',$attendance);
            }
            // else{
            //     return view('admin.systemreport.index');
            // }


    }


    public function store(Request $request)
    {
        //
    }


    public function show()
    {
        $todayDate = Carbon::now()->format('m/d/Y');
        $myDate = '02/23/2022';
        $result = Carbon::createFromFormat('m/d/Y', $myDate)->isPast();
        // var_dump($result);
        return response(
            [   'today'=> $todayDate,
                'date'=>$result]
        );
    }
    public function EmployeeReportView(){
    	$data['employees'] = Employee::all();
    	return view('report.employee_report_view',$data);
    }
    public function systemReportView(){
    	// $data['employees'] = Employee::all();
    	return view('report.system_report');
    }
    // system report
    public function systemReportGet(Request $request){
        $from=$request->input('from');
        $data['month'] = date('m-Y', strtotime( $from));
        $to=$request->input('to');
        $data['employee']=Employee::all();
        // $data['leave']=Leave::where('employee_id',$employee_id)->get();
        $attendance['attendance']=DB::table('employees')->join('checkins','checkins.employee_id','=','employees.id')
        ->select('*')
        ->whereDate('checkins.created_at', '>=', date('Y-m-d',strtotime($from)))
        ->whereDate('checkins.created_at', '<=', date('Y-m-d',strtotime($to)))->get();
        // $attendance['attendance']=Checkin::with('employee')
        // ->whereDate('checkins.created_at', '>=', date('Y-m-d',strtotime($from)))
        //  ->whereDate('checkins.created_at', '<=', date('Y-m-d',strtotime($to)))->get();
        //  return response([
        //                 'status'=>200,
        //                 'data'=>$attendance,
        //             ]);
         $pdf = PDF::loadView('report.system_report_pdf', $attendance,$data);
        // $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('document.pdf');

    }
    // employee report
    public function AttendanceReportGet(Request $request){

    	$employee_id = $request->employee_id;

        $from=$request->input('from');
        $data['month'] = date('m-Y', strtotime( $from));
        $to=$request->input('to');
        if(	$employee_id){
            $data['employee']=Employee::find($employee_id);
            $data['leave']=Leave::where('employee_id',$employee_id)->get();
            $attendance['attendance']=Checkin::with('employee')->where('employee_id',$employee_id)
        ->whereDate('checkins.created_at', '>=', date('Y-m-d',strtotime($from)))
         ->whereDate('checkins.created_at', '<=', date('Y-m-d',strtotime($to)))->get();
        //  $attendance=
        //  DB::table('employees')->join('checkins','checkins.employee_id','=','employees.id')
        //  ->where('employees.id',1)
        //  ->select('*')
        //  ->whereDate('checkins.created_at', '>=', date('Y-m-d',strtotime('2022-04-26')))
        //  ->whereDate('checkins.created_at', '<=', date('Y-m-d',strtotime('2022-04-26')))->get();
        //  return response([
        //             'status'=>200,
        //             'data'=>$attendance,
        //         ]);
         $pdf = PDF::loadView('report.employee_report_pdf', $attendance,$data);
	    // $pdf->SetProtection(['copy', 'print'], '', 'pass');
	    return $pdf->stream('document.pdf');
        }

    	// if ($employee_id != '') {
    	// 	$where[] = ['employee_id',$employee_id];
    	// }
    	// $date = date('Y-m', strtotime($request->date));
    	// if ($date != '') {
    	// 	$where[] = ['date','like',$date.'%'];
    	// }

    // $singleAttendance = Checkin::with(['employee'])->where('' )->get();

    // if ($singleAttendance == true) {
    // 	$data['allData'] =  Checkin::with('employee')->where($where)->get();
    // 	// dd($data['allData']->toArray());

    // 	// $data['absents'] = EmployeeAttendance::with(['user'])->where($where)->where('attend_status','Absent')->get()->count();

    // 	// $data['leaves'] = EmployeeAttendance::with(['user'])->where($where)->where('attend_status','Leave')->get()->count();

    // 	// $data['month'] = date('m-Y', strtotime($request->date));
    //     return response([
    //         'status'=>200,
    //         'data'=>$data,
    //     ]);



    // }
    }



    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
