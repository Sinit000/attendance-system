<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Student;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function getStudent(Request $request){
        try {
            $pageSize = $request->page_size ??10;
            $department = Category::paginate($pageSize);
            return response()->json(
              $department 
            );

        } catch (Exception $e) {
            //throw $th;
            return response()->json([
                'message'=>$e->getMessage()
            ]);
        }
    }
    public function addStudent(Request $request){
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone'=>'required',
                'address'=>'required'

            ]);
            if ($validator->fails()) {
                $error=$validator->errors()->all()[0];
                return response()->json(
                    [
                        // 'status'=>'false',
                        'message'=>$error,
                        'code'=>-1,
                        // 'data'=>[]
                    ],201
                );

            }else{
                $data = Category::create([
                    'name'=>$request['name'],
                    'phone'=>$request['phone'],
                    'address'=>$request['address'],

                ]);
                $respone = [
                    'message'=>'Success',
                    'code'=>0,

                ];

                // $save = new Employee();
                // return response($respone ,200);

            }
            return response()->json(
                $respone ,200
            );
        } catch (Exception $e) {
            // return response($e ,200);
            return response()->json(
                [
                    'message'=>$e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }
    }
    public function editDepartment(Request $request,$id){
        try {
            $data = Category::find($id);
            if( $data ){
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'phone'=>'required',
                    'address'=>'required'

                ]);
                if ($validator->fails()) {
                    $error=$validator->errors()->all()[0];
                    return response()->json(
                        [
                            // 'status'=>'false',
                            'message'=>$error,
                            'code'=>-1,
                            // 'data'=>[]
                        ],201
                    );

                }else{
                    $data->name=$request['name'];
                    $data->phone=$request['phone'];
                    $data->address=$request['address'];
                    $data->update();
                    $respone = [
                        'message'=>'Success',
                        'code'=>0,

                    ];

                }
            }else{
                $respone = [
                    'message'=>'No department id found',
                    'code'=>-1,

                ];
            }


            return response()->json(
                $respone ,200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message'=>$e->getMessage(),
                ]
            );
        }
    }
    public function deleteDepartment($id){
        try {
             $data = Category::find($id);
             if($data){
                $data->delete();
                $respone = [
                    'message'=>'Success',
                    'code'=>0,
                ];
                 // $em = Position::with('employee');
             }else{
                 $respone = [
                     'message'=>'No department id found',
                     'code'=>-1,
                 ];
             }
             return response()->json(
                 $respone ,200
             );
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
}
