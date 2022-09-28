<?php

namespace App\Http\Controllers;

use App\Models\Structure;
use App\Models\Structuretype;
use Illuminate\Http\Request;

class StructuretypeController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        // 'unique' => 'This :attribute has already been taken.',
        // 'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Structuretype::orderBy('created_at', 'DESC')->get())
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.payroll.structure_type');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string',
            

        ], $this->customMessages);

        $data = Structuretype::create([
            'name'           => strip_tags(request()->post('name')),
           

        ]);
        return response()->json($data);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Structuretype::findOrFail($id);

        return response()->json($data);
    }

    public function update($id)
    {

        request()->validate([
            'name' => 'required|string',
           

        ], $this->customMessages);
        $data = Structuretype::findOrFail($id);

        $data->update([
            'name'           => strip_tags(request()->post('name')),
           
        ]);

        return response()->json($data);
    }


    public function destroy($id)
    {
        // $data = Location::destroy($id);
        $data = Structuretype::find($id);
        if ($data) {
            $emp = Structure::where('structure_type_id', $data->id)->first();
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
