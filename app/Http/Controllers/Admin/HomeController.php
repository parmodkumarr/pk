<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Http\Requests\Admin\AdminUser;
class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    public function profile()
    {   $user = auth()->user();
        return view('admin.profile',compact('user'));
    }
    public function profileUpdate(AdminUser $request)
    {
        $data = $request->except(['_token']);;
        $user = auth()->user();
        $user = $user->update($data);
      
        return back()->with('success', 'User created successfully.');
    }
}
