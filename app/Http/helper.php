<?php
function apiResponse($success, $code, $reply, $extra = [])
{
    $response = [
    'status' => $code,
    'success' => $success,
    'message' => '',
    'errors' => [],
    'result' => [],
    'result_obj' => new ArrayObject(),
    'extra' => $extra ? $extra : new ArrayObject(),
    ];

    if ($code == 200) {
    $response['result_obj'] = $reply;
    } elseif ($code == 404 || $code == 202) {
    $response['result'] = $reply;
    } elseif ($code == 406) {
    $response['errors'] = apiErrors($reply);
    } else {
    $response['message'] = $reply;
    }
    return response()->json($response);
}


function apiErrors($errors = [])
{
    $error = [];
    if(!is_array($errors)){
        $errors = $errors->toArray();
    }

    foreach ($errors as $key => $value)
    {
        $error[] =['key' => $key, 'value' => $value[0]];
    }
    return $error;
}

function webResponse($success, $code, $reply, $extra = [])
{
    $response = [
        'status' => $code,
        'success' => $success,
        'message' => '',
        'errors' => [],
        'result' => [],
        'extra' => $extra ? $extra : new ArrayObject(),
    ];

    if ($code == 201) {
        $response['result'] = $reply;
    } elseif ($code == 406 ||  $code == 400 ) {
        $response['errors'] = webErrors($reply);
    } else {
        $response['message'] = $reply;
    }
    return response()->json($response);
}

function webErrors($errors = [])
{
    $error = [];
    if(!is_array($errors)){
        $errors = $errors->toArray();
    }

    foreach ($errors as $key => $value)
    {
        $error[$key] = $value[0];
    }
    return $error;
}


function UserRole($role=null){

    $roles = array( '1' => 'Admin',
                    '2' => 'Seller',
                    '3' => 'User' );

    if($role != null){
        return $roles[$role];
    }else{
        return $roles;
    }
}


function uploadImage($image, $folder)
{
    Storage::disk('local')->put('public/'.$folder, $image);
    $name = $folder.'/'.$image->hashName();
    return $name;
}

function uploadLargeFile($sourceFile, $folder)
{

    $disk = Storage::disk('s3');
    $filename = $folder.'/'.$sourceFile->hashName();
    
    $disk->put('public/'.$filename, fopen($sourceFile, 'r+'));
    dd($filename);
    return $filename;
}

function userNoctification($title='', $message='', $device_id)
{
    $url = 'https://fcm.googleapis.com/fcm/send';
    $api_key = 'AAAAM0RT_IU:APA91bFrNUv4cCvFL1QIu0xu-2N8WUpYvFoFwoHpHC7LBQprSmBSEaQ8PnwDyyKHiPNmKkSY9fhdoFcFw4p_tB3v7dhE5JDBKAINQ60n1AmSGskZmLvm4Qms2Bq3KA8TlIgT8IVuzWki';//env('FIREBASE_KEY');         
    $fields = array (
            'registration_ids' => array (
                $device_id
            ),
            "notification" => array(
            "title"=>$title,
            "body"=>$message
            )
        );

    //header includes Content type and api key
    $headers = array(
     'Content-Type:application/json',
     'Authorization:key='.$api_key
    );
           
           
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    print_r($result);die;
    return $result;
}

function nearestUser($latitude,$longitude){
    $users          =       \DB::table("users")->Join('user_roles','user_roles.user_id','users.id')
                                ->where('user_roles.role_id','3');
    $users          =       $users->select("*", \DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                            * cos(radians(users.latitude)) * cos(radians(users.longitude) - radians(" . $longitude . "))
                            + sin(radians(" .$latitude. ")) * sin(radians(users.latitude))) AS distance"));
    $users          =       $users->having('distance', '<', 10000);
    $users          =       $users->orderBy('distance', 'asc');
    $users          =       $users->get();
    return $users;
}

?>