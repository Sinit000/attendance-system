<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Leavetype;
use Illuminate\Http\Request;
use Exception;

class LeavetypeController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'max' => ':Attribute may not be more than :max characters.',
    ];
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Leavetype::orderBy('created_at', 'ASC')->get())
            ->addColumn('action', 'admin.users.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.settings.leavetype');
    }

    public function getComponent()
    {
        $data = Leavetype::all();
        return response()->json([
            'data'=>$data
        ]);
        
    }


    public function store(Request $request)
    {

        request()->validate([
            'leave_type' => 'required|string',
            'duration' => 'required|string',
           
        ], $this->customMessages);
        $data = new Leavetype();
        $parentId =0;
        $data->leave_type   = $request->leave_type;
        $data->notes=$request->notes;
        $scope = "0";
        if($request->duration){
            $scope = $request->duration;
        }
        if($request->parent_id){
            $parentId =$request->parent_id;
        }
        $data->duration   = $scope;
        $data->parent_id   = $parentId;

        $query = $data->save();
        return response()->json($query);

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $type = Leavetype::all();
        $data = Leavetype::findOrFail($id);
            // return response()->json($data);
            return response()->json(['data'=>$data,'type'=>$type]);

    }

    public function update(Request $request, $id)
    {
        try {
            request()->validate([
                'leave_type' => 'required|string',
                'duration' => 'required|string',
            ], $this->customMessages);
            $data = Leavetype::findOrFail($id);
            $parentId =0;
            $data->leave_type   = $request->leave_type;
            $data->notes=$request->notes;
            $scope = "0";
            if($request->duration){
                $scope = $request->duration;
            }
            if($request->parent_id){
                $parentId =$request->parent_id;
            }
            $data->duration   = $scope;
            $data->parent_id   = $parentId;
            $query = $data->update();
            return response()->json($query);
        } catch (Exception $e) {

                return response()->json(
                [
                    'message'=>$e->getMessage(),
                ]
            );
        }
    }


    public function destroy($id)
    {
        // $data = Leavetype::destroy($id);
        $data = Leavetype::find($id);
        if($data){
            // check if position id belong to employee table
           //  $postion = Leavetype::with('leaves')->where('id',$id)->get();
            $emp = Leave::where('leave_type_id', $data->id)->first();
           if(!$emp){
            // check parent id
            if($data->parent_id==0){
                $data->delete();
                $respone = [
                    'message'=>'Success',
                    'code'=>0,
                ];
            }else{
                $respone = [
                    'message'=>'Cannot delete this leave type',
                    'code'=>-1,
                   //  'data'=> $emp,
                ];
            }
               
           }else{
               $respone = [
                   'message'=>'Cannot delete this leave type',
                   'code'=>-1,
                  //  'data'=> $emp,
               ];
           }

        }else{
            $respone = [
                'message'=>'No leavetype id found',
                'code'=>-1,
            ];
        }
        return response()->json(
            $respone ,200
        );
    }
}
