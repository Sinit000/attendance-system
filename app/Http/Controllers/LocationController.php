<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Location;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Button;
// use Illuminate\Http\Request;
use Exception;

class LocationController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        // 'unique' => 'This :attribute has already been taken.',
        // 'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Location::orderBy('created_at', 'ASC')->get())
                ->addColumn('action', 'admin.users.action')
                ->addColumn('qr', 'admin.settings.qrcode')
                ->rawColumns(['action','qr'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.settings.location');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string',
            'lat'      => 'required|string',
            'lon'     => 'required|string',

        ], $this->customMessages);

        $data = Location::create([
            'name'           => strip_tags(request()->post('name')),
            'lat'          => strip_tags(request()->post('lat')),
            'lon'         => strip_tags(request()->post('lon')),
            'address_detail'       => strip_tags(request()->post('address_detail')),
            'notes'       => strip_tags(request()->post('notes')),

        ]);
        return response()->json($data);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Location::findOrFail($id);

        return response()->json($data);
    }

    public function update($id)
    {

        request()->validate([
            'name' => 'required|string',
            'lat'      => 'required|string',
            'lon'     => 'required|string',

        ], $this->customMessages);
        $data = Location::findOrFail($id);

        $data->update([
            'name'           => strip_tags(request()->post('name')),
            'lat'          => strip_tags(request()->post('lat')),
            'lon'         => strip_tags(request()->post('lon')),
            'address_detail'       => strip_tags(request()->post('address_detail')),
            'notes'       => strip_tags(request()->post('notes')),
        ]);

        return response()->json($data);
    }


    public function destroy($id)
    {
        // $data = Location::destroy($id);
        $data = Location::find($id);
        if ($data) {
            $emp = Department::where('location_id', $data->id)->first();
            if ($emp) {
                $respone = [
                    'message' => 'Cannot delete this location',
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
