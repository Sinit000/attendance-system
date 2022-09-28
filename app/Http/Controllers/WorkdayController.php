<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Workday;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Button;
use Exception;
use Illuminate\Support\Str;

class WorkdayController extends Controller
{

    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        // 'unique' => 'This :attribute has already been taken.',
        // 'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Workday::orderBy('created_at', 'DESC')->get())
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.settings.workday');
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string',
            'working_day' => 'required|string',
            'off_day'     => 'required|string',

        ], $this->customMessages);

        $data = Workday::create([
            'name'           => strip_tags(request()->post('name')),
            'working_day'          => strip_tags(request()->post('working_day')),
            'off_day'         => strip_tags(request()->post('off_day')),
            'notes'       => strip_tags(request()->post('notes')),
            'type_date_time'         => 'server',
            

        ]);
        return response()->json($data);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = Workday::findOrFail($id);

        return response()->json($data);
    }

    public function update($id)
    {

        request()->validate([
            'name' => 'required|string',
            'working_day'      => 'required|string',
            'off_day'     => 'required|string',

        ], $this->customMessages);
        $data = Workday::findOrFail($id);

        $data->update([
            'name'           => strip_tags(request()->post('name')),
            'working_day'          => strip_tags(request()->post('working_day')),
            'off_day'         => strip_tags(request()->post('off_day')),
            'notes'       => strip_tags(request()->post('notes')),
        ]);

        return response()->json($data);
    }
    public function updateTime(Request $request, $date)
    {
        try {
            //code...
            $data = Workday::first();
            $day = Str::substr($date, 2, 2);
            $month = Str::substr($date, 0, 2);
            $year = Str::substr($date, 4, 4);
            $time = $month . '/' . $day . '/'. $year;
            // $time = (string)$month + "/" + (string)$day + "/" + (string)$year;
            // $comments =  Str::substr($request->$comment, 0, 9);
            $data->date_time =$time;
            $query=  $data->save();
            return response()->json([
                'code' => '0',
                'message' => 'Success',
                'date'=>$time
                

            ]);
        } catch (Exception $e) {
            // return response($e ,200);
            return response()->json(
                [
                    'message' => $e->getMessage(),
                    // 'data'=>[]
                ]
            );
        }



        // return response()->json( $query);
    }


    public function destroy($id)
    {
        $data = Workday::find($id);
        if ($data) {
            $emp = Department::where('workday_id', $data->id)->first();
            if ($emp) {
                $respone = [
                    'message' => 'Cannot delete this workday',
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

        // return response()->json($data);
    }
    public function showdate()
    {
        $data = Workday::first();

        // return response(
        //     [

        //         'data'=>$data->id
        //     ]
        // );
        return view('admin.settings.showdatetime', compact('data'));
    }
    public function updateDate(Request $r)
    {

        $i = $r->cid;
        $type = $r->type_date_time;
        $data = Workday::find($i);
        // return r  esponse(
        //     [
        //         'id'=>$i,
        //         'data'=>$data
        //     ]
        // );
        if ($data) {
            $data->type_date_time = $type;
            $querty = $data->save();
        }
        return redirect()->back()->with('success', "Datetime has been changed!");
    }
}
