<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Role;
use App\Models\User;
use App\Models\Workday;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Button;
use Exception;
use Maatwebsite\Excel\Row;

class DepartmentControler extends Controller
{

    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {

        // $data = Department::with('location')->orderBy('created_at', 'DESC')->get();
        $ex1 = Department::with('location')->get();
        $user = User::all();
        $ex = "";
        // $user = User::all();
        // if($ex1->)
        // for ($i = 0; $i < count($ex1); $i++) {
        //     if($ex1[$i]['manager'] ==  $user[$i]['id']){
        //         $ex1->chief = $user[$i]['name'];
        //     }else{
        //         $ex1->chief = "";
        //     }
        // }
        foreach ($ex1 as $key => $val) {
            // $ex2 =  User::get();
            // if department have manger , find manager id by user 
            if ($val->manager) {
                $ex2 =  User::where('id', $val->manager)->first();
                $val->chief = $ex2->name;
            } else {
                // if department don't have manager , give value ="" 
                $val->chief = "";
            }
        }
        if (request()->ajax()) {
            return datatables()->of($ex1)
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        // return response()->json(
        //     [
        //         'data'=>$ex1,

        //     ]
        // );
        return view('admin.settings.department');
    }

    public function getComponent()
    {
        $data = Location::all();
        $user = User::whereNotIn('id', [1])->get();
        if ($data) {
            return response()->json([
                "status" => 200,
                "location" => $data,
                "user" => $user
                // "work"=>$work
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
        // $work = Workday::all();
        $location = Location::all();
        return view('admin.department.create_department', compact('location'));
    }


    public function store(Request $request)
    {
        request()->validate([
            'department_name' => 'required|string',
            // 'workday_id'      => 'required|string',
            'location_id'     => 'required|string',
        ], $this->customMessages);
        $department = Department::first();
        $userChief = "";
        if ($department) {

            // check if the new  department have chief department or not
            if ($request->manager) {
                $userChief = $request->manager;
                $x = Department::where('manager', '=', $request->manager)->first();
                if ($x) {
                    // remove the user from this department
                    $x->manager = Null;
                    $x->save();
                }
                // go to (1)
            } else {
                $userChief = Null;
            }
            $data = Department::create([
                'department_name'           => strip_tags(request()->post('department_name')),
                'manager'          => $userChief,
                'location_id'         => strip_tags(request()->post('location_id')),
                'notes'       => strip_tags(request()->post('notes')),

            ]);
            //  // check the user (1)
            $user = User::find($request->manager);
            // check old deparment have this user to be their chief 

            if ($user) {
                $user->department_id = $data->id;
                $user->save();
                // check usr role to know this user have role chief department already or not
            }
            // $role = Role::find($user->role_id);



        } else {
            $data = Department::create([
                'department_name'           => strip_tags(request()->post('department_name')),
                // 'workday_id'          => strip_tags(request()->post('workday_id')),
                'location_id'         => strip_tags(request()->post('location_id')),
                'notes'       => strip_tags(request()->post('notes')),

            ]);
        }

        return response()->json($department);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $mydata = Department::with('location')->where('id', $id)->first();
        $location = Location::all();
        // $work = Workday::all();
        $user = User::whereNotIn('id', [1])->get();
        // return response()->json($data);
        return response()->json(['data' => $mydata, 'mylocation' => $location, 'user' => $user]);
    }
    public function update(Request $request, $id)
    {
        request()->validate([
            'department_name' => 'required|string',
            // 'workday_id'      => 'required|string',
            'location_id'     => 'required|string',
        ], $this->customMessages);
        $data = Department::find($id);
        $data->department_name = $request->department_name;
        // $data->workday_id   = $request->workday_id;
        $data->location_id   = $request->location_id;
        $data->notes   = $request->notes;
        $userChief = "";
        $oldDepartment = "";
        $oldChiefId = "";
        $case = "";
        $code = "";
        $mgs = "";

        if ($request->manager) {
            // if the department before have manager
            $managerId = $data->manager;
            if ($data->manager) {
                if ($managerId == $request->manager) {
                    // if manager id and request the same
                    $userChief = $request->manager;
                    $case = "1";
                    $code = 0;
                } else {
                    $case = "01";
                    $code = -1;
                    $mgs = "Sorry, please change user role first";
                    // $user = User::find($request->manager);
                    // $oldDepartment=$user->department_id;
                    // $oldChiefId=$user->id;
                    // $userChief= $request->manager;
                    // //set new department automatically to user
                    // $user->department_id =  $data->id;
                    // // $user->save();
                    // // check user old department and remove manager=Null 
                    // $x = Department::where('manager','=',$oldChiefId)->first();
                    // if($x){
                    //     $x->manager= Null;
                    //     // $x->save();
                    //     $case ="2";
                    // }else{
                    //     $case ="3";
                    // }


                }
            } else {
                // $case = "002";
                // $code = -1;
                // $mgs = "Sorry, please change user role first";
                $userChief = $request->manager;
                $user = User::find($request->manager);
                $oldDepartment = $user->department_id;
                $oldChiefId = $user->id;
                // //set new department automatically to user
                $user->department_id =  $data->id;
                // $userChief = $request->manager;
                $user->save();
                // // check user old department and remove manager=Null  if user 
                $x = Department::find($oldDepartment);
                if($x->manager){
                    if ($x->manager == $oldChiefId) {
                        $x->manager = Null;
                        $x->save();
                        $case = "4";
                        $code = 0;
                    } else {
                        $case = "5";
                        $code = 0;
                    }
                }else{
                    $case = "006";
                        $code = 0;
                }
                // check if user used to be chief or not
                
            }


            // check manager id the same or not
        } else {

            // case department have chief and now don't have ,let remove user that belong to this position before
            if ($data->manager) {
                $userChief = Null;
                // $findUser = User::find($data->manager);
                $code = -1;
                $mgs = "Sorry, please change user role first";
                $case = "6";
            } else {
                // case department original don't have chief
                $userChief = Null;
                $code = 0;
                $case = "7";
            }
        }
        if ($code == 0) {
            $data->manager =  $userChief;
            $query = $data->update();
            return response()->json([
                'code' => 0,
                'message' => 'Data has been updated successfully!',
                'case'=>$case
            ]);
        } else {
            return response()->json([
                'code' => -1,
                'message' => $mgs,
                'case'=>$case
            ]);
        }




        // return response()->json([
        //     'case'=>$case,
        //     'old_chief'=>$oldChiefId,
        //     'new_chief'=> $userChief,
        //     'code'=>$code
        // ]);
        // return response()->json($query);
    }

    public function delete($id)
    {
        try {
            $data = Department::find($id);
            if ($data) {
                $emp = User::where('department_id', $data->id)->first();
                if ($emp) {
                    $respone = [
                        'message' => 'Cannot delete this department',
                        'code' => -1,
                        //  'data'=> $emp,
                    ];
                } else {
                    $data->delete();
                    $respone = [
                        'message' => 'Success',
                        'code' => 0,
                    ];
                }
            }

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
        $data = Department::find($id);
        if ($data) {
            $emp = User::where('department_id', $data->id)->first();
            if ($emp) {
                $respone = [
                    'message' => 'Cannot delete this department',
                    'code' => -1,
                    //  'data'=> $emp,
                ];
            } else {
                $data->delete();
                $respone = [
                    'message' => 'Success',
                    'code' => 0,
                ];
            }
        }
        return response(
            $respone,
            200
        );
    }
}
