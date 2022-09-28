<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class NotificationController extends Controller
{

    protected $customMessages = [
        'required' => 'Please input the :attribute.',
        'unique' => 'This :attribute has already been taken.',
        'max' => ':Attribute may not be more than :max characters.',
    ];

    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Notification::orderBy('created_at', 'DESC')->get())
                ->addColumn('action', 'admin.users.action')
                ->rawColumns(['action'])
                ->addIndexColumn()
                ->make(true);
        }
        return view('admin.settings.notification');
    }
    public function getComponent()
    {
        $data = User::whereNotIn('id', [1])->orderBy('created_at', 'DESC')->get();

        if ($data) {
            return response()->json([
                "status" => 200,
                "data" => $data,

            ]);
        } else {
            return response()->json([
                "status" => 404,
                "data" => "Data not found!"
            ]);
        }
    }



    public function create()
    {
    }

    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ], $this->customMessages);
        if ($request->user_id) {
            $findEm = User::find($request->user_id);
            if ($findEm->device_token) {
                $url = 'https://fcm.googleapis.com/fcm/send';
                $dataArr = array(
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'id' => $request->id,
                    'status' => "done",

                );
                $notification = array(
                    'title' => $request->title,
                    'text' => $request->body,
                    // 'isScheduled' => "true",
                    // 'scheduledTime' => "2022-06-14 17:55:00",
                    'sound' => 'default',
                    'badge' => '1',
                );
                // "registration_ids" => $firebaseToken,
                $arrayToSend = array(
                    "priority" => "high",
                    // "token"=>"7|Syty8L1QioCvQDQpl0axkahssTg542OE5HNCOpke",
                    // 'to'=>"/topics/6|bY5aVLz32sZrYIGjqpCqDUsRzFxopG8LgyRi0UOo",  
                    'to' => $findEm->device_token,
                    // 'registration_ids'=>'6|bY5aVLz32sZrYIGjqpCqDUsRzFxopG8LgyRi0UOo',
                    'notification' => $notification,
                    'data' => $dataArr,
                    'priority' => 'high'
                );
                $fields = json_encode($arrayToSend);
                $headers = array(
                    'Authorization: key=' . "AAAAqP0mBoo:APA91bEHUWxz5ZkOeZXpeoMSYtjQMdY8WCQyZSi7I5ycQJ3T6yUhqofYZ5w3AjCpjYSLm54Z3xTR3rsT7cLQ_L1xk7VNhODQDXi4GpxfRaDUH8eoefKuegD9_gx3IxKHIsFlLp8dcHe8",
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                $result = curl_exec($ch);
                // var_dump($result);
                curl_close($ch);
            }
        } else 
        {
            $data = Notification::create([
                'title'  => strip_tags(request()->post('title')),
                'body'   => strip_tags(request()->post('body')),
            ]);
            $url = 'https://fcm.googleapis.com/fcm/send';
            $dataArr = array(
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'id' => $request->id,
                'status' => "done",

            );


            $notification = array(
                'title' => $request->title,
                'text' => $request->body,
                'isScheduled' => "true",
                'scheduledTime' => "06/27/2022 10:30 AM",
                'sound' => 'default',
                'badge' => '1',
            );
            // "registration_ids" => $firebaseToken,
            $arrayToSend = array(
                "priority" => "high",
                // "token"=>"7|Syty8L1QioCvQDQpl0axkahssTg542OE5HNCOpke",
                // 'to'=>"/topics/6|bY5aVLz32sZrYIGjqpCqDUsRzFxopG8LgyRi0UOo",  
                'to' => "/topics/all",
                // 'registration_ids'=>'6|bY5aVLz32sZrYIGjqpCqDUsRzFxopG8LgyRi0UOo',
                'notification' => $notification,
                'data' => $dataArr,
                'priority' => 'high'
            );
            $fields = json_encode($arrayToSend);
            $headers = array(
                'Authorization: key=' . "AAAAqP0mBoo:APA91bEHUWxz5ZkOeZXpeoMSYtjQMdY8WCQyZSi7I5ycQJ3T6yUhqofYZ5w3AjCpjYSLm54Z3xTR3rsT7cLQ_L1xk7VNhODQDXi4GpxfRaDUH8eoefKuegD9_gx3IxKHIsFlLp8dcHe8",
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            $result = curl_exec($ch);

            // var_dump($result);
            curl_close($ch);
        }



        return response()->json($data);
    }

    public function edit($id)
    {
        $data = Notification::findOrFail($id);
        $user= User::whereNotIn('id', [1])->get();
        return response()->json(['data'=>$data,'user'=>$user]);

        return response()->json($data);
    }

    public function update($id)
    {

        request()->validate([
            'title' => 'required|string',
            'body' => 'required|string',
        ], $this->customMessages);
        $data = Notification::findOrFail($id);

        $data->update([
            'title'  => strip_tags(request()->post('title')),
            'body'   => strip_tags(request()->post('body')),
        ]);

        return response()->json($data);
    }


    public function destroy($id)
    {
        $data = Notification::destroy($id);

        return response()->json($data);
    }
}
