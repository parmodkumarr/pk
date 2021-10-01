<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SellerJob;
use App\Http\Requests\Api\Seller\StartJob;

class JobController extends Controller
{
    function TodayjobStart(StartJob $request){
        $imagearr=[];
        $user = auth()->user();
        $path ='seller/'.$user->id.'/';
        $images ='';
        $vedio ='';
        $audio ='';

        if(is_array($request->image)){
            foreach($request->image as $image){
                $imgpath = uploadImage($image,$path);
                array_push($imagearr, $imgpath);
            }  
        }

        $images = implode(',', $imagearr);

        if(isset($request->vedio)){
            $vedio = uploadImage($request->vedio,$path);
        }

        if(isset($request->audio)){
            $audio = uploadImage($request->audio,$path);
        }
        $data =$request->only('latitude','longitude','job_status');
        $data['image']= $images;
        $data['vedio']= $vedio;
        $data['audio']= $audio;
        $data['seller_id']= $user->id;
        SellerJob::updateOrCreate(['seller_id'=>$user->id],$data);

        return apiResponse(true, 201,'Successfully Update');
    }
}
