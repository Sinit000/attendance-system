<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Timetable;
// use App\Http\Requests\EmployeeRec;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\ModelNotFoundException;
// use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class EmployeeController extends Controller
{
    public function emplyeeschedule($id){
        $employee = Employee::find($id);
        $timetable = Timetable::find($employee->id);
        // $x = $employee->working_day;
        // $y = $employee->off_day;
        // return response()->json([
        //     'timetalbe_id'=>$employee->id,
        //     'data'=>$timetable
        // ]);

        return view('admin.employee.em_schedule',compact('employee','timetable'));
    }
   public function index(){
       return view('admin.employee.employee');
   }
   public function get(){
       $data = Employee::with('timetable','position');

    // $data = Employee::select('employees.id','employees.name','employees.gender','employees.status','st.position_name','time.timetable_name')
    // ->join('positions as st', 'st.id', '=', 'employees.position_id')
    // ->join('timetables as time', 'time.id', '=', 'employees.timetable_id')
    // ->get();
    // if($data){
    //     return response()->json([
    //         "status"=>200,
    //         "data"=>$data
    //     ]);
    // }else{
    //     return response()->json([
    //         "status"=>404,
    //         "data"=>"Data not found!"
    //     ]);
    // }
    // $data = Employee::all();
    // dd($data);
    return  DataTables::of($data)
    ->addIndexColumn()
    // ->addColumn('actions', function($row){
    //     return '<div class="btn-group">
    //                     <button class="btn btn-sm btn-primary" data-id="'.$row['id'].'" id="editBtn">Edit</button>
    //                   <button class="btn btn-sm btn-danger" data-id="'.$row['id'].'" id="deleteBtn">Delete</button>
    //             </div>';
    // })
    // ->addColumn('checkbox', function($row){
    //     return '<input type="checkbox" name="country_checkbox" data-id="'.$row['id'].'"><label></label>';
    // })

    ->rawColumns(['actions'])
    ->make(true);

}
    public function create(){
        $position=DB::table('positions')->get();
        $department=DB::table('departments')->get();
        $timetable=DB::table('timetables')->get();
        return view('admin.employee.create',compact('position','department','timetable'));
        // return view('admin.employee.create');
    }

    public function store(Request $request)
    {
        
        try {
            $todayDate = Carbon::now()->format('m/d/Y');
            $data = new Employee();
            $data->no = $request->no;
            $data->name= $request->name;
            $data->gender = $request->gender;
            $data->nationality= $request->nationality;
            $data->dob = $request->dob;
            $data->office_tel= $request->office_tel;
            $data->card_number= $request->card_number;
            $data->employee_phone= $request->employee_phone;
            $data->email = $request->email;
            // $data->profile_url= $request->profile_url;
            $data->address= $request->address;
            $data->username= $request->username;
            $data->password = bcrypt($request->password);
            $data->role= 2;
            $data->position_id= $request->position_id;
            $data->store_id= 1;
            $data->department_id= $request->department_id;
            // $data->timetable_id= $request->timetable_id;
            $data->working_day= $request->working_day;
            $data->off_day= $request->off_day;
            $data->status= "register";
            // $data->check_date = $todayDate;
            // if have image
            if($request->profile_url){
                $data->profile_url = $request->file('profile_url')->store('uploads/employee','photo');
                // add key photo  and store in folder uploads/customers and keyword is photo in filesystem.php in config
                // $data['profile_url'] = $r->file('image')->store('uploads/employee','photo');
            }
            $query = $data->save();
            if($query){
                return response(

                )->json(['code'=>0,'message'=>'New data has been successfully saved']);

            }else{
                return response()->json(['code'=>1,'message'=>'Something went wrong']);
            }
        } catch (Exception $e) {
            //throw $th;
            // echo($th);
            return response(
                [
                    'message'=>$e->getMessage()
                ]
            );
        }

    }
    public function edit($id)
    {
        $employee = Employee::find($id);
        $position = DB::table('positions')->get();
        $department = DB::table('departments')->get();
        $timetable = DB::table('timetables')->get();
        return view('admin.employee.edit',compact('employee','position','department','timetable'));
        // $data = Employee::find($id);
        // if($data){
        //     return response()->json([
        //         "status"=>200,
        //         "data"=>$data
        //     ]);
        // }else{
        //     return response()->json([
        //         "status"=>404,
        //         "data"=>"Data not found!"
        //     ]);
        // }
    }
    public function update(Request $r)
    {
       try {
           //code...
           $em = DB::table('employees')
           ->where('id','=',$r->id)->first();
           if($em)
                    $data =array(
               // 'id'=>$r->id,
               'no'=>$r->no,
               'name'=>$r->name,
               'gender'=>$r->gender,
               'nationality'=>$r->nationality,
               'dob'=>$r->dob,
               'office_tel'=>$r->office_tel,
               'card_number'=>$r->card_number,
               'employee_phone'=>$r->employee_phone,
               'email'=>$r->email,
            //    'profile_url'=>$r->profile_url,
               // 'address'=>$r->address,
               // 'username'=>$r->username,
               // 'password'=>bcrypt($r->password),
               'store_id'=>1,
            //    'timetable_id'=>$r->timetable_id,
               'position_id'=>$r->position_id,
               'working_day'=>$r->working_day,
               'off_day'=>$r->off_day,
               'department_id'=>$r->department_id,
            //    'status'=>$r->status==null?'register':$r->status,
            //    'check_date'=>$todayDate

           );
            if($r->file('profile_url')){
                $image = $em->profile_url;
            //    check if file exist
                if(File::exists($image)) {
                    File::delete($image);
                }
                $data['profile_url'] = $r->file('profile_url')->store('uploads/employee','photo');   
            }
                //    if($r->profile_url){
                // $name = $r->file('profile_url')->getClientOriginalName();

               // add key photo  and store in folder uploads/customers
              
                   // $data['profile_url'] = $fileName->store('uploads/employee','photo');
                //    }
           $query=DB::table('employees')
           ->where('id','=',$r->id)
           ->update($data);
           if($query){
            return response(

            )->json(['code'=>0,'message'=>'New data has been successfully updated']);

        }else{
            return response()->json(['code'=>1,'message'=>'Something went wrong']);
        }
            //    if($query){
            //     flash()->success('Success','Schedule has been created successfully !');
            //     return redirect('/employee')->with('success','One recorded has added successfully!');
            //     //    return response()->json(['code'=>0, 'message'=>'Data have Been updated'])->redirect('dashboard.admin.employee');
            //    }else{
            //        return response()->json(['code'=>1, 'message'=>'Something went wrong']);
            //    }
        //    }
       } catch (Exception $e) {
        return response()->json(
            [
                // 'status'=>'false',
                'message'=>$e->getMessage()
                // 'data'=>[]
            ]
        );
       }
    }
    public function delete($id){
        // $message = " One record has been deleted successfully!";
        // $message = " Failed";
       try {
           //code...
           $data = DB::table('employees')
           ->where('id','=',$id)->first();

          if( DB::table('employees')
           ->where('id','=',$id)->delete())
           {
               if($data)
               {
                   //remove image
                   $image = $data->profile_url;
                   // check if file exist
                   if(File::exists($image)) {
                       File::delete($image);
                   }
               }
               $message = "Success!";
           }
           return response()->json([
               'status'=>200,
               'message'=>"Success",
           ]);
       } catch (ModelNotFoundException $e) {
           //throw $th;
           return response([
               'code'=>1,
               'error'=>$e,
           ]);
       }


        // return redirect('employee')->with('success',$message);
        // $data = Employee::find($id);
        // $data->delete();
        // return response()->json([
        //     'status'=>200,
        //     'message'=>"Success",
        // ]);
    }
    public function schedual(){
        return view("admin.employee.schedual");
    }
    public function geteployee(){
        $data = Employee::all();
        // $timetable = Timetable::all();
        if($data){
            return response()->json([
                "status"=>200,
                "data"=>$data,
                // "timetable"=>$timetable
            ]);
        }else{
            return response()->json([
                "status"=>404,
                "data"=>"Data not found!"
            ]);
        }
    }





    public function destroy(Employee $employee)
    {
        $employee->delete();
        flash()->success('Success','Employee Record has been Deleted successfully !');
        return redirect()->route('employees.index')->with('success');
    }
}
