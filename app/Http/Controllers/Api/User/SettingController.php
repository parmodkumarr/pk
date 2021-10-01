<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Language;
use App\Models\Category;
use App\Models\UserCategories;
use Validator;
use Auth;
use App\Http\Requests\Api\User\UserLocation;

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

        $data=['user_id'=>$user->id,'user_type'=>3];
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
                $data=['category_id'=>$category,'user_id'=>$user->id,'user_type'=>3];
                $cate = UserCategories::where($data)->first();
                if(!$cate){
                    UserCategories::create($data);
                }
            }
        }

        $data2['status'] = ['3'];
        $user = $user->update($data2);
        
        return apiResponse(true, 201,'Successfully Update');
    }

    public function UpdateLocation(UserLocation $request)
    {
        $data = $request->all();
        $user = auth()->user();
        $user->update($data);
        
        return apiResponse(true, 201,'Successfully Update');
    }
}
