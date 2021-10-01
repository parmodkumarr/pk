<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRoles;
use App\Models\UserCategories;
use Validator;
use Auth;
use App\Http\Requests\Api\User\UserLoginRequest;
use App\Http\Requests\Api\User\UserPhoneVerifyRequest;
use App\Http\Requests\Api\User\UserProfile;

class AuthController extends Controller
{
    public function MobileLogin(UserLoginRequest $request)
    {
        $credentials = $request->only('phone');
        //$credentials['user_role'] = 3;
        $data =$credentials;
        $data['phone_otp'] =$otp = rand(1000, 9999);
        $user = User::where($credentials)->first();
        if($user){
            $user->update($data);
        }else{
            $user = User::create($data);
        }
        return apiResponse(true, 200,$data);
    }
    
    public function loginverify(UserPhoneVerifyRequest $request)
    {
        $credentials = $request->only('phone', 'phone_otp');
        $token = null;
        $user = User::where($credentials)->first();

        if(!$user){
            return apiResponse(false,401,'Invalid Credentials');
        }

        //if($user->status =='1'){
            $token = $user->createToken('auth_token')->plainTextToken;
       // }
            
        $userrole =['user_id'=>$user->id,'role_id'=>3];
        $role = UserRoles::where($userrole)->first();

        if(!$role){
            UserRoles::create($userrole);
        }

        $user->update(['device_id'=>$request->device_id]);

        $data['token'] = $token;
        $data['user_status'] = $user->status;

        return apiResponse(true, 200, $data);
    }

    public function getAuthUser(Request $request)
    {
        $user = auth()->user();
        return apiResponse(true, 200, $user);
    }

    public function UserUpdate(UserProfile $request)
    {
        $data = $request->all();
        if(isset($data['image'])){
            \Storage::disk('local')->put('public/userprofile', $data['image']);
            $name = 'public/userprofile/'.$data['image']->hashName();
            $data['image'] = $name;
        }

        $user = auth()->user();
        if($user->status == 0){
            $data['status'] =1;
        }
        $user->update($data);

        return apiResponse(true, 200, $user);
    }

    public function chooseLanguage(Request $request)
    {   
        $rules =[
            'language_iso'=>'required'
        ];
        $validation = Validator::make($request->all(),$rules);

        if($validation->fails()) {
            return apiResponse(false,406,$validation->getMessageBag());
        }

        $data = $request->all();
        $data['status'] = ['2'];
        $user = auth()->user();
        $user = $user->update($data);

        return apiResponse(true, 201,'Successfully Update');
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return apiResponse(true, 201,'successfully signed out');
    }

}
