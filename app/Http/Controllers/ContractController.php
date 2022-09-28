<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Payslip;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        // 'unique' => 'This :attribute has already been taken.',
        // 'max' => ':Attribute may not be more than :max characters.',
    ];
    public function index()
    {
        $data = Contract::with('user','structure')->orderBy('created_at', 'DESC')->get();
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

        return view('admin.payroll.contract');
    }

    public function getComponent(){
        $data = User::whereNotIn('id', [1])->orderBy('created_at', 'DESC')->get();
        $work = Structure::all();
        if($data){
            return response()->json([
                "status"=>200,
                "data"=>$data,
                "structure"=>$work
            ]);
        }else{
            return response()->json([
                "status"=>404,
                "data"=>"Data not found!"
            ]);
        }
    }

    public function create()
    {
        //
    }

   
    public function store(Request $request)
    {
        request()->validate([
            'ref_code'      => 'required|string',
            'user_id' => 'required|string',
            'structure_id'      => 'required|string',
            'start_date' => 'required|string',
            'end_date'      => 'required|string',
            'working_schedule' => 'required|string',
            


        ], $this->customMessages);

        $data = Contract::create([
            'ref_code'           => strip_tags(request()->post('ref_code')),
            'user_id'           => strip_tags(request()->post('user_id')),
            'structure_id'          => strip_tags(request()->post('structure_id')),
            'start_date'           => strip_tags(request()->post('start_date')),
            'end_date'          => strip_tags(request()->post('end_date')),
            'working_schedule'           => strip_tags(request()->post('working_schedule')),
            'status'          => "running",

        ]);
        return response()->json($data);
    }

   
    public function show($id)
    {
        //
    }

   
    public function edit($id)
    {
        $data = Contract::find($id);
        $user = User::whereNotIn('id', [1])->orderBy('created_at', 'DESC')->get();
        $work = Structure::all();
        return response()->json(['data'=>$data,'structure'=>$work,'user'=>$user]);
    }

    
    public function update(Request $request, $id)
    {
        request()->validate([
            'ref_code' => 'required|string',
            'user_id' => 'required|string',
            'structure_id'      => 'required|string',
            'start_date' => 'required|string',
            'end_date'      => 'required|string',
            'working_schedule' => 'required|string',
        ], $this->customMessages);

        $data = Contract::find($id);
        $data->update([
            'ref_code'           => strip_tags(request()->post('ref_code')),
            'user_id'           => strip_tags(request()->post('user_id')),
            'structure_id'          => strip_tags(request()->post('structure_id')),
            'start_date'           => strip_tags(request()->post('start_date')),
            'end_date'          => strip_tags(request()->post('end_date')),
            'working_schedule'           => strip_tags(request()->post('working_schedule')),
            'status'          => "running",
        ]);
        return response()->json($data);
    }

   
    public function destroy($id)
    {
        $data = Contract::find($id);
        if($data){
             // check if position id belong to employee table
             $emp = Payslip::where('contract_id', $data->contract_id)->first();
             if($emp){
                 $respone = [
                     'message'=>'cannot delete this contract who already running',
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
                'message'=>'No contract id found',
                'code'=>-1,

            ];
        }
        return response()->json(
            $respone ,200
        );
    }
}
