@extends('layouts.app')
@section('content')
<div class="row">
   <div class="col-12 col-xl-12">
      <div class="card card-body border-0 shadow mb-4">
         <h2 class="h5 mb-4">Your Profile</h2>
         {{ Form::model($user, ['route' => ['admin.profileUpdate'], 'method' => 'post']) }}
            @csrf
            <div class="row">
               <div class="col-md-6 mb-3">
                  <div class="form-group">
                     <label for="first_name">Name</label> 
                     {{ Form::text('name',old('name'),['class'=>'form-control','placeholder'=>'Enter Name','id'=>'name']) }}
                     @if($errors->has('name'))
                        <div class="error">{{ $errors->first('name') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-md-6 mb-3">
                  <div class="form-group">
                     <label for="email">Email</label>
                      {{ Form::text('email',old('email'),['class'=>'form-control','placeholder'=>'Enter email','id'=>'email']) }}
                     @if($errors->has('email'))
                        <div class="error">{{ $errors->first('email') }}</div>
                     @endif
                   </div>
               </div>
            </div>
            <div class="row">
               <div class="col-md-6 mb-3">
                  <div class="form-group">
                     <label for="phone">Phone</label> 
                     {{ Form::text('phone',old('phone'),['class'=>'form-control','placeholder'=>'+12-345 678 910','id'=>'phone']) }}
                     @if($errors->has('phone'))
                         <div class="error">{{ $errors->first('phone') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-md-6 mb-3">
                  <div class="form-group">
                     <label for="address">Address</label> 
                     {{ Form::textarea('address',old('address'),['class'=>'form-control','id'=>'address','cols'=>5,'rows'=>4]) }}
                     @if($errors->has('address'))
                         <div class="error">{{ $errors->first('address') }}</div>
                     @endif
                  </div>
               </div>
            </div>
            <div class="mt-3"><button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">Save</button></div>
         </form>
      </div>
   </div>
</div>
@endsection