<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HolidayController extends Controller
{
    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {
        $data = Holiday::orderBy('from_date', 'ASC')->get();
        $total=0;
        for($i=0 ;$i < count($data);$i++){
            $result1 = Carbon::createFromFormat('Y-m-d', $data[$i]['from_date'])->isPast();
            $result2 = Carbon::createFromFormat('Y-m-d', $data[$i]['to_date'])->isPast();
            if($result1==false || $result2==false){
                $total += $data[$i]['duration'];
                // $total = $data[$i]['from_date'];
            }else{
                $total=0;
            }
        }
        
        if (request()->ajax()) {
            return datatables()->of(Holiday::orderBy('from_date', 'ASC')->get())
            ->addColumn('action', 'admin.users.action')
            ->addColumn('holiday_status', 'admin.settings.holiday_status')
            ->rawColumns(['action','holiday_status'])
            ->addIndexColumn()
            ->make(true);
        }
        // return response()->json([
        //     'data'=>$data,
        //     // 'tota'=>$total
        // ]);
        return view('admin.settings.holiday');
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|string',
            'from_date'      => 'required',
            'to_date'     => 'required',
            // 'notes'     => 'required|string',

        ], $this->customMessages);
        $pastDF=Carbon::parse( $request->from_date);
        $pastDT=Carbon::parse(  $request->to_date);
        $duration_in_days =   $pastDT ->diffInDays( $pastDF);
        // out put 1 ,but reality 2 
        // $duration_in_days = $request->to_date->diffInDays($request->from_date);
        // $duration= $duration_in_days;
        $duration= ($duration_in_days +1);
        $data = Holiday::create([
            'name'           => strip_tags(request()->post('name')),
            'from_date'          => strip_tags(request()->post('from_date')),
            'to_date'         => strip_tags(request()->post('to_date')),
            'status'       => "pending",
            'duration'       => $duration,
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
        $data = Holiday::findOrFail($id);

        return response()->json($data);
    }

    public function update(Request $request,$id)
    {

        request()->validate([
            'name' => 'required|string',
            'from_date'      => 'required|string',
            'to_date'     => 'required|string',

        ], $this->customMessages);
        $data = Holiday::findOrFail($id);
        $pastDF=Carbon::parse( $request->from_date);
        $pastDT=Carbon::parse(  $request->to_date);
        $duration_in_days =   $pastDT ->diffInDays( $pastDF);
        $duration= ($duration_in_days +1);
        // check status , if complete canot update
        $data->update([
            'name'           => strip_tags(request()->post('name')),
            'from_date'          => strip_tags(request()->post('from_date')),
            'to_date'         => strip_tags(request()->post('to_date')),
            'status'       => "pending",
            'duration'=>$duration,
            'notes'       => strip_tags(request()->post('notes')),
        ]);

        return response()->json($data);
    }


    public function destroy($id)
    {
        $data = Holiday::destroy($id);

        return response()->json($data);
    }
}
