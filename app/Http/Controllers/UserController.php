<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\Counter;
use App\Models\Department;
use App\Models\Holiday;
use App\Models\Position;
use App\Models\Role;
use App\Models\Timetable;
use App\Models\TimetableEmployee;
use App\Models\User;
use App\Models\Workday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class UserController extends Controller
{

    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has alpready been taken.',
        'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(User::with('role', 'department', 'position')->whereNotIn('id', [1])->orderBy('created_at', 'ASC')->get())
                ->addColumn('action', 'admin.users.action')
                ->addColumn('image', 'admin.users.image')
                // ->addColumn('image', function ($row) {
                //     $url= 'uploads/employee/rXbGc2oXcxMmzXss8zasrQF59FjSSEs7ENjg4Yjy.jpg';
                //      return '<div>  <img src="uploads/employee/rXbGc2oXcxMmzXss8zasrQF59FjSSEs7ENjg4Yjy.jpg" alt="logo" width="50" ></div>';
                //      })
                ->rawColumns(['action', 'image'])
                ->addIndexColumn()
                ->make(true);
        }
        // $data = User::whereNotIn('id', [1])->get();
        // for($i=0;$i<count($data);$i++){
        //     $data[$i]['status']='false';
        //     $query = $data[$i]->update();

        // }
        return view('admin.users.index');
    }
    public function detail($id){
        $user = User::with('department','position','timetable')->where('id','=',$id)->first();
        $dayoff = Workday::find( $user->workday_id);
        $workDay = explode(',', $dayoff->working_day);
        $offDay = explode(',', $dayoff->off_day);
        return view('admin.users.user_detail',compact('user','workDay','offDay'));
        // return response()->json([
        //     'user'=>$user,
        //     'work_day'=> $workday,
        //     'off_day'=>$offDay
        // ]);
    }

    public function create()
    {
        $position = Position::all();
        $department = Department::all();
        $timetable = Timetable::all();
        // $role = Role::all();
        $role = Role::whereNotIn('id', [1])->orderBy('created_at', 'DESC')->get();
        $workday = Workday::all();
        return view('admin.users.create', compact('position', 'department', 'timetable', 'role','workday'));
    }

    public function store(Request $request)
    {

        request()->validate([
            'gender' => 'required|string',
            'name'      => 'required|string',
            'username'     => 'required|string',
            'password'  => 'required|string',
            'position_id'     => 'required|string',
            'department_id'  => 'required|string',
            'workday_id'     => 'required|string',
            'timetable_id'     => 'required|string',
            'role_id'     => 'required|string',
        ], $this->customMessages);
        $manager= "";
        // $todayDate = Carbon::now()->format('m/d/Y');
        $data = new User();
       
        $data->name = $request->name;
        $data->gender = $request->gender;
        $data->nationality = $request->nationality;
        $data->dob = $request->dob;
        $data->email = $request->email;
        $data->office_tel = $request->office_tel;
        $data->card_number = $request->card_number;
        $data->address = $request->address;
        $data->employee_phone = $request->employee_phone;
        $data->merital_status = $request->merital_status;
        $data->minor_children = $request->minor_children;
        $data->spouse_job = $request->spouse_job;
        $data->username = $request->username;
        $data->password = bcrypt($request->password);
        $data->role_id = $request->role_id;
        $data->timetable_id = $request->timetable_id;
        $data->workday_id = $request->workday_id;
        $data->status = 'false';
        // check user role
        // $findRole = Role::find( $request->role_id);
        // if(str_contains($findRole,'Cheif')){
        //     $manager= ""
        // }
        

        // $data->is_manager = "false";
        // $data->role_id= $request->role_id; 
        $data->position_id = $request->position_id;
        $data->department_id = $request->department_id;
        if ($request->profile_url) {
            $uploadedFileUrl = Cloudinary ::upload($request->file('profile_url')->getRealPath(),[
                'folder'=>'employee'
            ])->getSecurePath();
            $data->profile_url= $uploadedFileUrl;
            // $data->profile_url = $request->file('profile_url')->store('uploads/employee', 'photo');
            // add key photo  and store in folder uploads/customers and keyword is photo in filesystem.php in config
            // $data['profile_url'] = $r->file('image')->store('uploads/employee','photo');
        }
         $data->save();
        $findRole = Role::find( $request->role_id);
        if(str_contains($findRole,'Cheif')){
            $depart = Department::find($request->department_id);
            $depart->manager= $data->id;
            $depart->save();
        }
        $holiday = Holiday::orderBy('from_date', 'ASC')->get();
        
        $total=0;
        $result1  ="";
        $result2 ="";
        for($i=0 ;$i < count($holiday);$i++){
            $result1 = Carbon::createFromFormat('Y-m-d', $holiday[$i]['from_date'])->isPast();
            $result2 = Carbon::createFromFormat('Y-m-d', $holiday[$i]['to_date'])->isPast();
            if($result1==false || $result2==false){
                $total += $holiday[$i]['duration'];
            }else{
                $total=0;
            }
        }
        // create counter for user
        $c = Counter::create([
            'user_id'           => $data->id,
            'ot_duration'          => '0',
            'total_ph'         => $total,
            'hospitality_leave'       => '7',
            'marriage_leave'=>'3',
            'peternity_leave'=>'7',
            'maternity_leave'=>'1 month',
            'funeral_leave'=>'3'
        ]);
        return response()->json([
            'code'=>0,
            // 'total'=>count($holiday),
            // 'total_ph'=>$total,
            'message'=>'Date has been successfully added!'
        ]);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        // $data = User::findOrFail($id);
        $data = User::find($id);
        $position = Position::all();
        $department = Department::all();
        $timetable = Timetable::all();
        // $role = Role::all();
        $role = Role::whereNotIn('id', [1])->orderBy('created_at', 'DESC')->get();
        $workday = Workday::all();
        return view('admin.users.edit', compact('data','position', 'department', 'timetable', 'role','workday'));
    }

    public function update(Request $request, $id)
    {

        request()->validate([
            'gender' => 'required|string',
            'name'      => 'required|string',
            'position_id'     => 'required|string',
            'department_id'  => 'required|string',
            'workday_id'     => 'required|string',
            'timetable_id'     => 'required|string',
            'role_id'     => 'required|string',
        ], $this->customMessages);

        $data = User::find($id);
        $roleId ="";
        $manager ="";
        $userId ="";
        $case ="";
        $roleId= $data->role_id;
        $departmentId = $data->department_id;
        if ($data) {
            $data->name = $request->name;
            $data->gender = $request->gender;
            $data->nationality = $request->nationality;
            $data->dob = $request->dob;
            $data->office_tel = $request->office_tel;
            $data->card_number = $request->card_number;
            $data->address = $request->address;
            $data->employee_phone = $request->employee_phone;
            $data->merital_status = $request->merital_status;
            $data->minor_children = $request->minor_children;
            $data->spouse_job = $request->spouse_job;
            $data->email = $request->email;
            $data->position_id = $request->position_id;
            $data->department_id = $request->department_id;
            $data->role_id = $request->role_id;
            $data->timetable_id = $request->timetable_id;
            $data->workday_id = $request->workday_id;
            
        }
        if ($request->file('profile_url')) {
            
            if($data->profile_url){
                $string = $data->profile_url;
                $token = explode('/', $string);
                $token2 = explode('.', $token[sizeof($token)-1]);
                Cloudinary::destroy('employee/'.$token2[0]);
            }
            // $image = $data->profile_url;
            // $public_id = Cloudinary::getPublicId($data->profile_url);
            // $url = Cloudinary::getUrl($public_id);
            // $url = Storage::disk('cloudinary')->fileExists($image_url);
            //    check if file exist
            // if (File::exists($image)) {
            //     File::delete($image);
            // }
           
            $uploadedFileUrl = Cloudinary ::upload($request->file('profile_url')->getRealPath(),[
                'folder'=>'employee'
            ])->getSecurePath();
            $data->profile_url= $uploadedFileUrl;
            // $data['profile_url'] = $request->file('profile_url')->store('uploads/employee', 'photo');
        }
       
        $userId= $data->id;
        // if have image
         $data->update();
        //  check if user change role , to find id chief department
         if( $roleId==$request->role_id && $departmentId== $request->department_id){
            // check department , in case change department so remove cheif department before , insert update other department
            $case = "0";

         }elseif($roleId==$request->role_id && $departmentId != $request->department_id){
            // let change department chief
            $de= Department::find($departmentId);
            $de->manager=null;
            $de->save();
            $case = "1";
            // find new department 
            $new = Department::find($request->department_id);
            $new->manager= $userId;
            $new->save();
         }elseif($roleId!=$request->role_id && $departmentId == $request->department_id){
            // change only role, check if new role is chief department
            $findRole = Role::find( $request->role_id);
            if(str_contains($findRole,'Cheif')){
                $depart = Department::find($request->department_id);
                $depart->manager=  $userId;
                $manager=$userId;
                $depart->save();
                $case = "2";
            }else{
                $case = "3";
            }
         }
         else{
            // before role :accountant , and change to chief department
            // deferent role and different department
            // deferent role Id 
            // $case = "4";
            $findRole = Role::find( $request->role_id);
            if(str_contains($findRole,'Cheif')){
                $depart = Department::find($request->department_id);
                $depart->manager=  $userId;
                $manager=$userId;
                $depart->save();
                $case = "4";
            }else{
                $case = "5";
            }
         }
         
         return response()->json([
             'code'=>0,
             'message'=>'Date has been successfully updated!',
            //  'role_id'=>$roleId,
            //  're_role_id'=>$request->role_id,
            //  'manger_id'=>$manager,
            //  'department_id'=> $departmentId,
            //  're_department_id'=> $request->department_id,
            //  'case'=>$case
         ]);
        // return redirect()->back()->with('success', "Data has been save successfully!");
    }


    public function destroy($id)
    {
        $data = User::find($id);
        if ($data) {
            // check if position id belong to employee table
            $emp = Checkin::where('user_id', $data->id)->first();

            if ($emp) {
                $respone = [
                    'message' => 'cannot delete employee who already checkin',
                    'code' => -1,
                ];
            } else {
               if($data->profile_url){
                    $string = $data->profile_url;
                    $token = explode('/', $string);
                    $token2 = explode('.', $token[sizeof($token)-1]);
                    Cloudinary::destroy('employee/'.$token2[0]);
               }
               $data->delete();
                // if (DB::table('users')
                //     ->where('id', '=', $id)->delete()
                // ) {
                //     if ($data) {
                        
                       
                // //remove image
                //         // $image = $data->profile_url;
                //         // check if file exist
                //         // if (File::exists($image)) {
                //         //     File::delete($image);
                //         // }
                //     }
                // }

                $respone = [
                    'message' => 'Success',
                    'code' => 0,
                ];
            }
        } else {
            $respone = [
                'message' => 'No employee id found',
                'code' => -1,

            ];
        }
        return response()->json(
            $respone,
            200
        );


        // return response()->json($data);
    }

    public function changePassword()
    {
        $user = User::findOrFail(auth()->user()->id);

        return view('admin.users.changePassword', compact('user'));
    }

    public function updatePassword()
    {
        $user = User::findOrFail(auth()->user()->id);

        request()->validate([
            'current_password' => 'required|string',
            'password' => "required|string|confirmed",
        ], $this->customMessages);

        if (Hash::check(request()->post('current_password'), $user->password)) {
            $user->password = bcrypt(request()->post('password'));
            $user->password_changed_at = now();
            $user->save();
        } else {
            return redirect()->back()->withErrors(['current_password' => 'Your entered password is wrong, try again!']);
        }
    }
    public function changeUserPassword()
    {

        // $user = User::findOrFail(auth()->user()->id);
        $data['employees'] = User::whereNotIn('id', [1])->get();
        return view('admin.users.user_change_password', $data);
    }

    public function updateUserPassword(Request $request)
    {
        // $user = User::findOrFail(auth()->user()->id);

        request()->validate([
            'user_id' => "required",
            'password' => "required|string|confirmed",
            'password_confirmation' => "required"
        ], $this->customMessages);
        $user = User::find($request["user_id"]);
        if ($user) {
            $user->password = bcrypt(request()->post('password'));
            $user->password_changed_at = now();
            $user->save();
            return redirect()->back()->with('success', "Password has been changed!");
        } else {
            return redirect()->back()->withErrors(['confirm_password' => 'Your entered password is wrong, try again!']);
        }
    }
}
