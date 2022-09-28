<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;

class UploadControler extends Controller
{
    public function upload(Request $request){
        try {

            // file(file):keywork in postman, if put photo , file(photo)
            $result = $request->file('file')->store('uploads/employee','photo');
            //    $request->file('photo')->store('uploads/employee','photo');
                $respone = [
                    'message'=>'Sucess',
                    'code'=>0,
                    'profile_url'=> $result

                ];


            return response()->json(
                $respone ,200
            );

        } catch (Exception $e) {
            //throw $th;
            return response()->json(
             [
                 'message'=>$e->getMessage(),
                 // 'data'=>[]
             ]
         );
        }
    }
    public function index()
    {
        //
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
