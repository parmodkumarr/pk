<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\UserCategories;
use App\Models\SellerCategories;
use App\Models\Language;
use Validator;
use App\Models\SellerWishList;
use App\Http\Requests\Api\User\nearestSellerNotification;
use App\Http\Requests\Api\User\favoriteSeller;
use App\Http\Requests\Api\User\AddWishList;
use DB;

class DashboardController extends Controller
{
    public function getSelectCategory(Request $request)
    {
        $user = auth()->user();

        $usercate = DB::table('user_categories as t1')
            ->select('t1.*','users.name as sellername','users.latitude as sellerlatitude','users.longitude as sellerlongitude','t2.user_id as seller_id','categories.name as category_name')
            ->selectRaw(DB::raw('( 111.045 * acos( cos( radians(' .$user->latitude . ') ) * cos( radians(users.latitude) ) * cos( radians(users.longitude) - radians(' .$user->longitude . ') ) + sin( radians(' .$user->latitude. ') ) * sin( radians(users.latitude) ) ) ) AS distance'))
            ->Join('user_categories as t2', function ($join) {
                $join->on('t1.category_id', '=', 't2.category_id')
                ->where('t2.user_type', '=', '2');
            })->leftJoin('users','users.id','t2.user_id')
            ->leftJoin('categories','categories.id','t2.category_id')
            ->where('t1.user_id', '=',$user->id)
            ->where('t1.user_type', '=','3')
            ->get()->toArray();
        foreach($usercate as $cate){
            $cate->location ='Leisure Valley, 10B, Sector 10, Chandigarh, India';
            $cate->notification_off ='http://192.168.0.2/WalkShop/public/api/user/select-category';
            $cate->add_favorite ='http://192.168.0.2/WalkShop/public/api/user/select-category';
        }

        return apiResponse(true, 202,$usercate);
    }

    public function getSelectCategory2(Request $request)
    {
        $user = auth()->user();

        $usercate = UserCategories::where('user_id','=',$user->id)->pluck('category_id')->toArray();

        $usercate = SellerCategories::leftJoin('users','users.id','seller_categories.user_id')
                    ->leftJoin('categories','categories.id','seller_categories.category_id')
                    ->select('seller_categories.*','users.name as sellername','users.latitude as sellerlatitude','users.longitude as sellerlongitude','users.id as user_id','categories.name as category_name','seller_categories.user_id as seller_id')
                    ->get();
           // echo"<pre>";print_r($usercate);die;
        foreach($usercate as $cate){
            $cate->location ='Leisure Valley, 10B, Sector 10, Chandigarh, India';
            $cate->notification_off ='http://192.168.0.2/WalkShop/public/api/user/select-category';
            $cate->add_favorite ='http://192.168.0.2/WalkShop/public/api/user/select-category';
        }

        return apiResponse(true, 202,$usercate);
    }

    public function addFavoriteSeller(favoriteSeller $request)
    {
        $user = auth()->user();
        $ucate_id = $request->user_cat_id;
        $favorite = $request->favorite;

        $usercate = UserCategories::where('id','=',$ucate_id)->first();

        $usercate->update(['favorite'=>$favorite]);

        if($favorite =='1'){
            $msg ="Added into Favorite";
        }else{
            $msg ="Removed into Favorite";
        }

        return apiResponse(true, 201,$msg);
    }

    public function FavoriteSellerList()
    {
        $user = auth()->user();
        
        $favorite = DB::table('user_categories as t1')
            ->select('t1.*','users.name as sellername','users.latitude as sellerlatitude','users.longitude as sellerlongitude','t2.user_id as seller_id','categories.name as category_name')
            ->selectRaw(DB::raw('( 111.045 * acos( cos( radians(' .$user->latitude . ') ) * cos( radians(users.latitude) ) * cos( radians(users.longitude) - radians(' .$user->longitude . ') ) + sin( radians(' .$user->latitude. ') ) * sin( radians(users.latitude) ) ) ) AS distance'))
            ->Join('user_categories as t2', function ($join) {
                $join->on('t1.category_id', '=', 't2.category_id')
                ->where('t2.user_type', '=', '2');
            })->leftJoin('users','users.id','t2.user_id')
            ->leftJoin('categories','categories.id','t2.category_id')
            ->where('t1.user_id', '=',$user->id)
            ->where('t1.user_type', '=','3')
            ->where('t1.favorite', '=','1')
            ->get()->toArray();
            
        foreach($favorite as $fvrt){
            $fvrt->location='Leisure Valley, 10B, Sector 10, Chandigarh, India';
        }
        return apiResponse(true, 202,$favorite);
    }

    public function FindNearSellerList(Request $request)
    {
        $rules =[
            'distance_limt'=>'required'
        ];
        $validation = Validator::make($request->all(),$rules);

        if($validation->fails()) {
            return apiResponse(false,406,$validation->getMessageBag());
        }

        $user = auth()->user();
        
        $favorite = DB::table('user_categories as t1')
            ->select('t1.*','users.name as sellername','users.latitude as sellerlatitude','users.longitude as sellerlongitude','t2.user_id as seller_id','categories.name as category_name')
            ->selectRaw(DB::raw('( 111.045 * acos( cos( radians(' .$user->latitude . ') ) * cos( radians(users.latitude) ) * cos( radians(users.longitude) - radians(' .$user->longitude . ') ) + sin( radians(' .$user->latitude. ') ) * sin( radians(users.latitude) ) ) ) AS distance '))
            ->Join('user_categories as t2', function ($join) {
                $join->on('t1.category_id', '=', 't2.category_id')
                ->where('t2.user_type', '=', '2');
            })->leftJoin('users','users.id','t2.user_id')
            ->leftJoin('categories','categories.id','t2.category_id')
            ->where('t1.user_id', '=',$user->id)
            ->where('t1.user_type', '=','3')
            ->having('distance', '<', $request->distance_limt)
            ->orderBy('distance', 'asc')
            ->get()->toArray();
            
        foreach($favorite as $fvrt){
            $fvrt->location='Leisure Valley, 10B, Sector 10, Chandigarh, India';
        }
        return apiResponse(true, 202,$favorite);
    }

    public function nearestSellerNotification(nearestSellerNotification $request)
    {
        $user = auth()->user();
        $ucate_id = $request->user_cat_id;
        $noti_on = $request->noti_on;

        $usercate = UserCategories::where('id','=',$ucate_id)->first();

        $usercate->update(['noti_on'=>$noti_on]);

        if($noti_on =='1'){
            $msg ="Notification Actived";
        }else{
            $msg ="Notification Inactived";
        }

        return apiResponse(true, 201,$msg);
    }

    public function AddWishList(AddWishList $request)
    {
        $user = auth()->user();
        $data = $request->all();

        $data['user_id'] = $user->id;
        $insert = $data;
        $wishlist = SellerWishList::where($data)->first();
        if($wishlist){
            $insert['status'] = $wishlist->status++;
            $wishlist->update($insert);
        }else{
            $insert['status'] = 1;
            SellerWishList::create($insert);
        }

        return apiResponse(true, 201,'Added to wishlist');
    }


    public function getSelectCategorydummp(Request $request)
    {
       $lag = array(
        ['sellername'=>'parmod',
        'latitude'=>'30.87866',
        'longitude'=>'76.35099',
        'location'=>'Leisure Valley, 10B, Sector 10, Chandigarh, India',
        'notification_off'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'add_favorite'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'category_name'=>'vegetables'
        ],['sellername'=>'parmod',
        'latitude'=>'30.87866',
        'longitude'=>'76.35099',
        'location'=>'Leisure Valley, 10B, Sector 10, Chandigarh, India',
        'notification_off'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'add_favorite'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'category_name'=>'vegetables'
        ],
        ['sellername'=>'parmod',
        'latitude'=>'30.87866',
        'longitude'=>'76.35099',
        'location'=>'Leisure Valley, 10B, Sector 10, Chandigarh, India',
        'notification_off'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'add_favorite'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'category_name'=>'vegetables'
        ],
        ['sellername'=>'parmod',
        'latitude'=>'30.87866',
        'longitude'=>'76.35099',
        'location'=>'Leisure Valley, 10B, Sector 10, Chandigarh, India',
        'notification_off'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'add_favorite'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'category_name'=>'vegetables'
        ],
        ['sellername'=>'parmod',
        'latitude'=>'30.87866',
        'longitude'=>'76.35099',
        'location'=>'Leisure Valley, 10B, Sector 10, Chandigarh, India',
        'notification_off'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'add_favorite'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'category_name'=>'vegetables'
        ],
        ['sellername'=>'parmod',
        'latitude'=>'30.87866',
        'longitude'=>'76.35099',
        'location'=>'Leisure Valley, 10B, Sector 10, Chandigarh, India',
        'notification_off'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'add_favorite'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'category_name'=>'vegetables'
        ],
        ['sellername'=>'parmod',
        'latitude'=>'30.87866',
        'longitude'=>'76.35099',
        'location'=>'Leisure Valley, 10B, Sector 10, Chandigarh, India',
        'notification_off'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'add_favorite'=>'http://192.168.0.2/WalkShop/public/api/user/select-category',
        'category_name'=>'vegetables'
        ]
       );

       return apiResponse(true, 202,$lag);
    }
}
