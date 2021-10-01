<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Language;
use App\Models\Category;
use App\Models\UserCategories;
use App\Models\SellerJob;
use Validator;
use Auth;
use App\Http\Requests\Api\Seller\SellerLocation;

class SettingController extends Controller
{
    public function LanguageList(Request $request)
    {
        $lag = Language::all();
        return apiResponse(true, 202,$lag);
    }

    public function CategoryList(Request $request)
    {
        $cate = Category::all();
        $user = auth()->user();

        $data=['user_id'=>$user->id,'user_type'=>2];
        $usercate = UserCategories::where($data)->pluck('category_id')->toArray();
        foreach($cate as $cat){
            $cat->selected = false;
            
            if(in_array($cat->id,$usercate)){
                $cat->selected =true;
            }
        }
        return apiResponse(true, 202,$cate);
    }
    
    public function chooseCategory(Request $request)
    {
        $rules =[
            'categories'=>'required'
        ];
        $validation = Validator::make($request->all(),$rules);

        if($validation->fails()) {
            return apiResponse(false,406,$validation->getMessageBag());
        }

        $categories = explode(",",$request->categories);
        $user = auth()->user();
        if(count($categories) >0){
            foreach($categories as $category){
                $data=['category_id'=>$category,'user_id'=>$user->id,'user_type'=>2];
                $cate = UserCategories::where($data)->first();
                if(!$cate){
                    UserCategories::create($data);
                }
            }
        }
        
        $data2['status'] ='3';
        $user = $user->update($data2);

        return apiResponse(true, 201,'Successfully Update');
    }

    public function UpdateLocation(SellerLocation $request)
    {
        $data = $request->all();
        $user = auth()->user();
        $job = SellerJob::where('seller_id',$user->id)->first();
        $job->update($data);
        $users = nearestUser($request->latitude,$request->latitude);
        $did = 'esY7EmKxQSawtslZHX4Qe4:APA91bEBs6dp6NoRDZnqgW3xf1mrNu_ubCZgGJ7hMZ1t1b32A4NumYPJl_OxfqYLgs4HezLkapABmGxb9EiXn9Fp6eO3jNeN8i8EEIa7MNQqIQ_xGGzuKO3pcAHVm3JNmb1uMt186VGZ';
        if(!empty($users)){
            foreach($users as $usr){
                userNoctification('ssssss','sssss',$did);
            }
        }
        return apiResponse(true, 201,'Successfully Update');
    }



}
