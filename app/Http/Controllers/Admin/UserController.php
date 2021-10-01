<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Http\Requests\Admin\AdminUser;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $data = User::latest()->get();

            return DataTables::of($data)->addColumn('action', function ($data)
            {
                $edit = route('admin.users.edit', $data->id);
                $delete = route('admin.users.destroy', $data->id);
                $button = '';
                $button = '<a name="edit" id="' . $data->id . '" class="edit btn btn-primary btn-sm" href="' . $edit . '">Edit</a>';
                $button .= '&nbsp;&nbsp;&nbsp;<button type="button" class="delete btn btn-danger btn-sm" onclick="deleteData(this)" data-id="' . $data->id . '" data-url="' . $delete . '">Delete</button>';
                
                return $button;
            })->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.addoredit');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminUser $request)
    {
        $data = $request->except(['_token']);

        if($request->hasFile('image')){
          $file = $request->file('image');
          $data['image']=uploadImage($file,'users/image');
        }
        
        $user = User::create($data);

        $extra['redirect'] = back()->getTargetUrl();

        if($user){
            return webResponse(true, 200, 'Added successfully.', $extra);
        }else{
            return webResponse(false, 401,'Something Wrong');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.addoredit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(AdminUser $request,$id)
    {

        $data = $request->except(['_token','_method','role_id']);

        if($request->hasFile('image')){
          $file = $request->file('image');
          $data['image']=uploadImage($file,'users/image');
        }
        $user = User::where('id', $id)->update($data);
        $extra['redirect'] = back()->getTargetUrl();

        if($user){
            return webResponse(true, 200, 'Updated successfully.', $extra);
        }else{
            return webResponse(false, 401,'Something Wrong');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = User::find($id);
        if($cat->delete()){
            return 1;
        }else{
            return 0;

        }
    }
}
