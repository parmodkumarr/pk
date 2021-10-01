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
            <li class="breadcrumb-item active" aria-current="page"> <?php echo isset($category) ?'Edit Category' : 'Add Category' ?> </li>
        </ol>
    </nav>
    <div class="d-flex justify-content-between w-100 flex-wrap">
        <div class="mb-3 mb-lg-0">
            <h1 class="h4"><?php echo isset($category) ?'Edit Category' : 'Add Category' ?></h1>
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
             @if(isset($category))
              {{ Form::model($category, ['route' => ['admin.categories.update', $category->id], 'method' => 'patch','files'=> true,'class'=>'ajax-submit','id'=>'categoryform']) }}
            @else
                {{ Form::open(['route' => 'admin.categories.store','files'=> true,'class'=>'ajax-submit','id'=>'categoryform']) }}
            @endif
              <div class="row col-lg-6 col-sm-4">
                  <div class="mb-4">
                    <label for="name">Category Name</label>
                    {{ Form::text('name',old('name'),['class'=>'form-control','placeholder'=>'Enter name','id'=>'name','aria-describedby'=> 'name_error']) }}
                    @if($errors->has('name'))
                      <small id="name_error" class="form-text text-muted">{{ $errors->first('name') }}</small>
                    @endif
                  </div>


                  <div class="mb-4">
                    @if(isset($category->image))
                      <img src="{{url('storage/'.$category->image)}}" width="150" id="categoryimage">
                    @else
                      <img src="" width="150" id="categoryimage">
                    @endif

                    {{ Form::file('image',['class'=>'form-control','id'=>'image','onchange'=>'readImgURL(this,"categoryimage")','accept'=>'image/*','aria-describedby'=> 'image_error']) }}
                      @if($errors->has('image'))
                        <small id="image_error" class="form-text text-muted">{{ $errors->first('name') }}</small>
                      @endif
                  </div>
                  <div class="mb-4">
                    <label for="description">Description</label>
                      {{ Form::textarea('description',old('description'),['class'=>'form-control','id'=>'description','cols'=>5,'rows'=>4,'aria-describedby'=> 'description_error']) }}
                      @if($errors->has('description'))
                      <small id="description_error" class="form-text text-muted">{{ $errors->first('description') }}</small>>
                      @endif
                  </div> 
              </div>
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