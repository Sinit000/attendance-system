<?php

namespace App\Notifications;


class PushNotification {

    public function notify($request){
        $url = 'https://fcm.googleapis.com/fcm/send';
        $dataArr = array(
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'id' =>$request-> id,
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
    public function notifyAllSpecificuser($request){
        $url = 'https://fcm.googleapis.com/fcm/send';
            $dataArr = array(
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'id' => 1,
                'status' => "done",

            );
            $multi = [
                "fCpLuBgQRWmXAGGETvG_Av:APA91bFrxpAPBEwsp5eaGyLhviPuwViAOlywlIoCQazhKoGlQYOWMUZatMW-pFbUbX88OhxPHMaHcEkB3LGoHPSc-ykhpTSAXw7jcB99SioJHJwtdzvJssvdRpIuU21zFkNeCEYlR1-h",
                "enYWDFbtSxa2aBVaxq-i7r:APA91bGE6Sd6qPIuFGMz4ChfcIxy-8Cx5CVJTG7g-HoOKTFdwYPD1BKU4HWkOPgcnEPx_vkV27ChvFSTLhhXcAf9O-H7Ft0AqqvyAw892iSkXJMhGpMLqmfqreGvYfQdXb417U5SxpBK"
            ];
            $notification = array(
                'title' => "Hi Sotheara",
                'text' => "Hi",
               
                'sound' => 'default',
                'badge' => '1',
            );
            // "registration_ids" => $firebaseToken,
            $arrayToSend = array(
                "priority" => "high",
                
                // 'to' =>$multi,
                'registration_ids'=>[
                    
                    "enYWDFbtSxa2aBVaxq-i7r:APA91bGE6Sd6qPIuFGMz4ChfcIxy-8Cx5CVJTG7g-HoOKTFdwYPD1BKU4HWkOPgcnEPx_vkV27ChvFSTLhhXcAf9O-H7Ft0AqqvyAw892iSkXJMhGpMLqmfqreGvYfQdXb417U5SxpBK"
                ],
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
    public function notifySpecificuser($request){
        $url = 'https://fcm.googleapis.com/fcm/send';
            $dataArr = array(
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'id' => $request['id'],
                'status' => "done",

            );
            
            $notification = array(
                'title' => $request['title'],
                'text' => $request['text'],
               
                'sound' => 'default',
                'badge' => '1',
            );
            // "registration_ids" => $firebaseToken,
            $arrayToSend = array(
                "priority" => "high",
                
                'to' =>$request['device_token'],
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
}