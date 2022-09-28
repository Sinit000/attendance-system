<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CheckinController extends Controller
{
    public function checkin(){
        $todayDate = Carbon::now()->format('m/d/Y');
            $data=Checkin::with('user','user.timetable')
            ->where('date','=',$todayDate)
            ->get();
        //      $response =[
        //         'data'=>$data
        //     ];
        // return response()->json($response);
        if (request()->ajax()) {
            return datatables()->of($data)
            ->addColumn('action', 'admin.users.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.attendance.checkin');
    }
    public function checkout(){
        $todayDate = Carbon::now()->format('m/d/Y');
            $data=Checkin::with('user','user.timetable')
            ->whereNotNull('checkout_time')
            ->where('date','=',$todayDate)
            ->get();
        if (request()->ajax()) {
            return datatables()->of($data)
            ->addColumn('action', 'admin.users.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.attendance.checkout');
    }
    public function late(){
        $todayDate = Carbon::now()->format('m/d/Y');
            $data=Checkin::with('user','user.timetable')
            ->where('checkin_status','late')
            ->where('date','=',$todayDate)
            ->get();
        if (request()->ajax()) {
            return datatables()->of($data)
            ->addColumn('action', 'admin.users.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.attendance.late');
    }
    public function overtime(){
        $todayDate = Carbon::now()->format('m/d/Y');
            $data=Checkin::with('user','user.timetable')
            ->where('checkout_status','good')
            ->where('date','=',$todayDate)
            ->get();
        if (request()->ajax()) {
            return datatables()->of($data)
            ->addColumn('action', 'admin.users.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.attendance.overtime');
    }
    public function absent(){
        $todayDate = Carbon::now()->format('m/d/Y');
            $data=Checkin::with('user','user.timetable')
            ->where('status','absent')
            ->where('date','=',$todayDate)
            ->get();
        if (request()->ajax()) {
            return datatables()->of($data)
            ->addColumn('action', 'admin.users.action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.attendance.absent');
    }

}
