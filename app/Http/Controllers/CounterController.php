<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use Illuminate\Http\Request;

class CounterController extends Controller
{
   
    public function index()
    {
        $data = Counter::with('user','user.position')->orderBy('created_at', 'DESC')->get();
        if (request()->ajax()) {
            return datatables()->of($data)
            ->addColumn('action', 'admin.users.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        // $counter = Counter::all();
        // $duration =0;
        // for($i=0; $i< count($counter);$i++){
        //     // if($counter[$i]['total_ph'] >0){
        //     //      $duration = $counter[$i]['total_ph'] - $duration;
        //     //      $counter[$i]['total_ph'] = 4;
        //     // }
        //     $counter[$i]['total_ph'] = 4;
            
        //     $query = $counter[$i]->update();
             
        // }
        // return response()->json([
        //     'data'=>$data
        // ]);
        return view('admin.settings.counter');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
