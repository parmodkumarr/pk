@extends('layouts.app')
@section('content')
 <div class="py-4">
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item">
                <a href="{{route('admin.dashboard')}}">
                    <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page"> <?php echo isset($user) ?'Edit User' : 'Add User' ?> </li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4"><?php echo isset($user) ?'Edit User' : 'Add User' ?></h1>
            <!-- <p class="mb-0">Dozens of reusable components built to provide buttons, alerts, popovers, and more.</p> -->
        </div>
        <!-- <div>
            <a href="https://themesberg.com/docs/volt-bootstrap-5-dashboard/components/forms/" class="btn btn-outline-gray"><i class="far fa-question-circle me-1"></i> Forms Docs</a>
        </div> -->
    </div>
</div>

<div class="row">
  <div class="col-12 mb-4">
      <div class="card border-0 shadow components-section">
          <div class="card-body">     
             @if(isset($user))
              {{ Form::model($user, ['route' => ['admin.users.update', $user->id], 'method' => 'patch','files'=> true,'class'=>'ajax-submit','id'=>'AdminUser']) }}
            @else
                {{ Form::open(['route' => 'admin.users.store','files'=> true,'class'=>'ajax-submit','id'=>'categoryform']) }}
            @endif
              <div class="row col-lg-6 col-sm-4">
                  <div class="mb-4">
                    <label for="name">User Role</label>
                    {{ Form::select('role_id',UserRole(),old('role'),['class'=>'form-control','placeholder'=>'Select Role','id'=>'role_id','aria-describedby'=> 'role_id_error']) }}

                    @if($errors->has('role_id'))
                      <small id="role_id_error" class="form-text text-muted">{{ $errors->first('role_id') }}</small>
                    @endif
                  </div>

                  <div class="mb-4">
                    <label for="name">User Name</label>
                    {{ Form::text('name',old('name'),['class'=>'form-control','placeholder'=>'Enter name','id'=>'name','aria-describedby'=> 'name_error']) }}
                    @if($errors->has('name'))
                      <small id="name_error" class="form-text text-muted">{{ $errors->first('name') }}</small>
                    @endif
                  </div>


                  <div class="mb-4">
                    @if(isset($user->image))
                      <img src="{{url('storage/'.$user->image)}}" width="150" id="userimage">
                    @else
                      <img src="" width="150" id="userimage">
                    @endif

                    {{ Form::file('image',['class'=>'form-control','id'=>'image','onchange'=>'readImgURL(this,"userimage")','accept'=>'image/*','aria-describedby'=> 'image_error']) }}
                      @if($errors->has('image'))
                        <small id="image_error" class="form-text text-muted">{{ $errors->first('name') }}</small>
                      @endif
                  </div>
                  <div class="mb-4">
                    <label for="phone">Phone Number</label>
                      {{ Form::text('phone',old('phone'),['class'=>'form-control','id'=>'phone','cols'=>5,'rows'=>4,'aria-describedby'=> 'phone_error']) }}
                      @if($errors->has('phone'))
                      <small id="phone_error" class="form-text text-muted">{{ $errors->first('phone') }}</small>>
                      @endif
                  </div>
                  <div class="mb-4">
                    <label for="email">Email</label>
                      {{ Form::text('email',old('email'),['class'=>'form-control','id'=>'email','cols'=>5,'rows'=>4,'aria-describedby'=> 'email_error']) }}
                      @if($errors->has('email'))
                      <small id="email_error" class="form-text text-muted">{{ $errors->first('email') }}</small>>
                      @endif
                  </div>
              </div>
              {{Form::hidden('id', null)}}
                {!! Form::submit('Submit', ['class' => 'btn btn-primary submit_btn_new']) !!}
                {{ Form::close() }}
          </div>
      </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/script.js') }}"></script>
@endpush
@push('styles')
@endpush