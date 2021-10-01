<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\UserCategories;
use App\Models\SellerCategories;
use App\Models\Language;
use App\Models\SellerWishList;
use DB;

class DashboardController extends Controller
{
    public function WishList(Request $request)
    {
        $user = auth()->user();

        $wishlist = SellerWishList::leftJoin('users','users.id','seller_wish_lists.user_id')
        ->leftJoin('categories','categories.id','seller_wish_lists.user_id')
        ->select('users.name as username','users.latitude as userlatitude','users.longitude as userlongitude','users.id as customer_id','categories.name as category_name','users.live_address as userliveaddress')
        ->where('seller_id',$user->id)->get();

        return apiResponse(true, 202,$wishlist);

    }

    public function getSelectCategory(Request $request)
    {
        $user = auth()->user();

        $usercate = DB::table('user_categories as t1')
            ->select('users.name as username','users.latitude as userlatitude','users.longitude as userlongitude','users.id as customer_id','categories.name as category_name')
            ->selectRaw(DB::raw('( 111.045 * acos( cos( radians(' .$user->latitude . ') ) * cos( radians(users.latitude) ) * cos( radians(users.longitude) - radians(' .$user->longitude . ') ) + sin( radians(' .$user->latitude. ') ) * sin( radians(users.latitude) ) ) ) AS distance')
            ->Join('user_categories as t2', function ($join) {
                $join->on('t1.category_id', '=', 't2.category_id')
                ->where('t2.user_type', '=', '3');
            })->leftJoin('users','users.id','t2.user_id')
            ->leftJoin('categories','categories.id','t2.category_id')
            ->where('t1.user_id', '=',$user->id)
            ->where('t1.user_type', '=','2')
            ->get()->toArray();

        return apiResponse(true, 202,$usercate);
    }

    public function getSelectCategory2(Request $request)
    {
        $user = auth()->user();

        $usercate = SellerCategories::where('user_id','=',$user->id)->pluck('category_id')->toArray();

        $usercate = UserCategories::leftJoin('users','users.id','user_categories.user_id')
                    ->leftJoin('categories','categories.id','user_categories.category_id')
                    ->select('user_categories.*','users.name as username','users.latitude as userlatitude','users.longitude as userlongitude','users.id as user_id','categories.name as category_name')
                    ->get();
        foreach($usercate as $cate){
            $cate->location ='Leisure Valley, 10B, Sector 10, Chandigarh, India';
            $cate->notification_off ='http://192.168.0.2/WalkShop/public/api/user/select-category';
            $cate->add_favorite ='http://192.168.0.2/WalkShop/public/api/user/select-category';
        }

        return apiResponse(true, 202,$usercate);
    }
}
