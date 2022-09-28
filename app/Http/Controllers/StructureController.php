<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Structure;
use App\Models\Structuretype;
use Illuminate\Http\Request;

class StructureController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        // 'unique' => 'This :attribute has already been taken.',
        // 'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {
        $data = Structure::orderBy('created_at', 'DESC')->get();
        if (request()->ajax()) {
            return datatables()->of( $data)
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        // return response()->json([
        //     "status"=>200,
        //     "data"=>$data,
            
        // ]);

        return view('admin.payroll.structure');
    }

    public function create()
    {
        // $user = User::whereNotIn('id', [1])->get();
        // return view('admin.salary.create', compact('user'));
    }
    public function getComponent(){
        $data = Structuretype::all();
       
        if($data){
            return response()->json([
                "status"=>200,
                "data"=>$data,
                
            ]);
        }else{
            return response()->json([
                "status"=>404,
                "data"=>"Data not found!"
            ]);
        }
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string',
            'base_salary' => 'required|string',
            // 'currency' => 'required|string',
            // 'structure_type_id' => 'required|string',
        ], $this->customMessages);
        $bonus =0;
        $senorityMoney=0;
        $allowance=0;
        // if($request->bonus){
        //     $bonus= $request->bonus;
        // }else{
        //     $bonus= 0;
        // }
        if($request->allowance){
            $allowance= $request->allowance;
        }else{
            $allowance= 0;
        }
        // if($request->senority_salary){
        //     $senorityMoney= $request->senority_salary;
        // }else{
        //     $senorityMoney= 0;
        // }
        $data = Structure::create([
            'name'           => $request->name,
            'base_salary'           => $request->base_salary,
            // 'bonus'           => $bonus,
            'allowance'           => $allowance,
            // 'currency'           => strip_tags(request()->post('currency')),
            // 'structure_type_id'           => strip_tags(request()->post('structure_type_id')),
        ]);
        return response()->json($data);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Structure::findOrFail($id);
        // $type= Structuretype::all();

        return response()->json(['data'=>$data]);
    }

    public function update(Request $request,$id)
    {

        request()->validate([
            'name' => 'required|string',
            'base_salary' => 'required|string',
            // 'structure_type_id' => 'required|string',
            // 'currency' => 'required|string',
        ], $this->customMessages);
        $bonus =0;
        $senorityMoney=0;
        $allowance=0;
        // if($request->bonus){
        //     $bonus= $request->bonus;
        // }else{
        //     $bonus= 0;
        // }
        if($request->allowance){
            $allowance= $request->allowance;
        }else{
            $allowance= 0;
        }
        // if($request->senority_salary){
        //     $senorityMoney= $request->senority_salary;
        // }else{
        //     $senorityMoney= 0;
        // }
        $data = Structure::findOrFail($id);

        $data->update([
            'name'           => $request->name,
            'base_salary'           => $request->base_salary,
            // 'bonus'           => $bonus,
            'allowance'           => $allowance,
            // 'currency'           => strip_tags(request()->post('currency')),// 'senority_salary'           => $senorityMoney,
            // 'structure_type_id'           => strip_tags(request()->post('structure_type_id')),
           
        ]);

        return response()->json($data);
    }


    public function destroy($id)
    {
        // $data = Location::destroy($id);
        $data = Structure::find($id);
        if ($data) {
            $emp = Contract::where('structure_id', $data->id)->first();
            if ($emp) {
                $respone = [
                    'message' => 'Cannot delete this structure',
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
