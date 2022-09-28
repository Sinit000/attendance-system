<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Exception;

class PositionController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Position::orderBy('created_at', 'DESC')->get())
            ->addColumn('action', 'admin.users.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.settings.position');
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        request()->validate([
            'position_name' => 'required|string',
        ], $this->customMessages);

        $data = Position::create([
            'position_name'  => strip_tags(request()->post('position_name')),
            'type'          => strip_tags(request()->post('type')),

        ]);
        return response()->json($data);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Position::findOrFail($id);

        return response()->json($data);
    }

    public function update($id)
    {

        request()->validate([
            'position_name' => 'required|string',


        ], $this->customMessages);
        $data = Position::findOrFail($id);

        $data->update([
            'position_name'           => strip_tags(request()->post('position_name')),
            'type'          => strip_tags(request()->post('type')),
        ]);

        return response()->json($data);
    }


    public function destroy($id)
    {
        // $data = Position::destroy($id);
        $data = Position::find($id);
        if($data){
            $emp = User::where('position_id', $data->id)->first();
            if($emp){
                $respone = [
                    'message'=>'Cannot delete this position',
                    'code'=>-1,
                    // 'data'=> $emp,
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

        // return response()->json($data);
    }
}
