<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix'=>'user','namespace'=>'Api\User'], function(){
    Route::post('login', 'AuthController@MobileLogin');
    Route::post('mobile/verify', 'AuthController@loginverify');
    
    Route::group(['middleware'=>['auth:sanctum','user']], function(){
        Route::post('user-logout', 'AuthController@logout');

        Route::post('get/userprofile', 'AuthController@getAuthUser');
        Route::post('update/userprofile', 'AuthController@UserUpdate');
        Route::post('choose-language', 'AuthController@chooseLanguage');
        Route::post('choose-category', 'SettingController@chooseCategory');
        Route::post('language-list', 'SettingController@LanguageList');
        Route::post('category-list', 'SettingController@CategoryList');
        Route::post('update-location', 'SettingController@UpdateLocation');
        Route::post('nearseller-list', 'DashboardController@getSelectCategory');
        //extra
       // Route::post('nearseller-list2', 'DashboardController@getSelectCategory2');
        Route::post('nearestseller-notification', 'DashboardController@nearestSellerNotification');
        Route::post('addfavoriteseller', 'DashboardController@addFavoriteSeller');
        Route::post('favoritesellerlist', 'DashboardController@FavoriteSellerList');
        Route::post('add-wishlist', 'DashboardController@AddWishList');
        Route::post('find-near-seller', 'DashboardController@FindNearSellerList');

    });
});

Route::group(['prefix'=>'seller','namespace'=>'Api\Seller'], function(){
    Route::post('regisration', 'AuthController@sellerRegisration');
    Route::post('login', 'AuthController@SellerLogin');
    Route::post('mobile/verify', 'AuthController@loginverify');

    Route::group(['middleware'=>['auth:sanctum','seller']], function(){
        Route::post('get/userprofile', 'AuthController@getAuthUser');
        Route::post('update/userprofile', 'AuthController@UserUpdate');
        Route::post('choose-language', 'AuthController@chooseLanguage');
        Route::post('change-password', 'AuthController@changePassword');
        Route::post('choose-category', 'SettingController@chooseCategory');
        Route::post('language-list', 'SettingController@LanguageList');
        Route::post('category-list', 'SettingController@CategoryList');
        Route::post('update-location', 'SettingController@UpdateLocation');
        Route::post('wish-list', 'DashboardController@WishList');
        Route::post('start-job', 'JobController@TodayjobStart');
        //extra
        //Route::post('wish-list2', 'DashboardController@getSelectCategory2');
    });
    
});