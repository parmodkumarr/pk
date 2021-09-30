<?php

if(isset($_POST['email'])){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    include 'vendor/autoload.php';

    $ve = new hbattat\VerifyEmail($_POST['email'], 'technodeviser05@gmail.com');

    $is_valid_format =false;
    $is_valid_format_text ='false';

    $is_smtp_valid =false;
    $is_smtp_valid_text ='false';

    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $is_valid_format= true;
        $is_valid_format_text ='true';
    }

    if($ve->verify()){
        $is_smtp_valid =true;
        $is_smtp_valid_text ='true';
    }
    
    echo json_encode(["email"=>$_POST['email'],"is_smtp_valid"=>array('value'=>$is_smtp_valid,"text"=> $is_valid_format_text),"is_valid_format"=>array('value'=>$is_valid_format,"text"=> $is_valid_format_text)]);
}
