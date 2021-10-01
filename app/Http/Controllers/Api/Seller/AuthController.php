<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRoles;
use Validator;
use Auth;
use App\Http\Requests\Api\Seller\SellerRegisration;
use App\Http\Requests\Api\Seller\SellerLogin;
use App\Http\Requests\Api\User\UserPhoneVerifyRequest;
use App\Http\Requests\Api\Seller\ChangePassword;

class AuthController extends Controller
{
    public function sellerRegisration(SellerRegisration $request)
    {
        $credentials = $request->only('phone');
        $data =$request->all();
        $data['phone_otp'] =$otp = rand(1000, 9999);
        $user = User::where($credentials)->first();

        if(isset($data['image'])){
            \Storage::disk('local')->put('public/userprofile', $data['image']);
            $name = 'public/userprofile/'.$data['image']->hashName();
            $data['image'] = $name;
        }

        $data['password'] = Hash::make($data['password']);

        if($user){
            $user->update($data);
        }else{
            $user = User::create($data);
        }
         return apiResponse(true, 200,$data);
    }

    public function SellerLogin(SellerLogin $request)
    {
        $field = 'email';
        if (is_numeric($request->input('email'))) {
            $field = 'phone';
        } elseif (filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }

        $request->merge([$field => $request->input('email')]);

        $credentials = $request->only($field);

        $user = User::where($credentials)->first();

        $userrole = UserRoles::where(['user_id'=>$user->id,'role_id'=>'2'])->first();

        if (!$userrole) {
            return apiResponse(false,401,'Invalid Credentials. Please Sign Up With Seller');
        }
        

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return apiResponse(false,401,'Invalid Credentials');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update(['device_id'=>$request->device_id]);

        $data['token']  = $token;
        $data['user']   = $user;

        return apiResponse(true, 200, $data);
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

        $userrole =['user_id'=>$user->id,'role_id'=>2];
        $role = UserRoles::where($userrole)->first();

        if(!$role){
            UserRoles::create($userrole);
        }
        
        $user->update(['device_id'=>$request->device_id,'status'=>'1']);

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
        $user = auth()->user();
        $data['status'] ='2';
        $user = $user->update($data);

        return apiResponse(true, 201,'Successfully Update');
    }

    public function changePassword(ChangePassword $request)
    {
        $user = auth()->user();

        if ((Hash::check(request('current_password'), $user->password)) == false) {

            return apiResponse(false,401,'Please Check your old password.');

        } else if ((Hash::check(request('new_password'), $user->password)) == true) {

            return apiResponse(false,401,'Please enter a password which is not similar then current password.');

        } else {

            $user->update(['password' => Hash::make(request('new_password'))]);

            return apiResponse(true, 201,'Successfully Update');
        }
    }
}
