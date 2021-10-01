<!-- <div class="toast custom_toast bg-danger show session">
  <div class="toast-header text-white bg-danger">
    <span class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></span>
  </div>
  <div class="toast-body text-white">dssssssssssssss</div>
</div> -->
<div class="ajaxtoast toast custom_toast bg-success show" style="display:none">
  <div class="toast-header text-white bg-success">
    <span class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></span>
  </div>
  <div class="toast-body text-white"></div>
</div>
@if ($message = Session::get('success'))
<div class="toast custom_toast bg-success show session">
  <div class="toast-header text-white bg-success">
    <span class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></span>
  </div>
  <div class="toast-body text-white">{{ $message }}</div>
</div>
@endif
@if ($message = Session::get('error'))
<div class="toast custom_toast bg-danger show">
  <div class="toast-header text-white bg-danger">
    <span class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></span>
  </div>
  <div class="toast-body text-white">{{ $message }}</div>
</div>
@endif
@if ($message = Session::get('warning'))
<div class="toast custom_toast bg-warning show session">
  <div class="toast-header text-white bg-warning">
    <span class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></span>
  </div>
  <div class="toast-body text-white">{{ $message }}</div>
</div>
@endif
@if ($message = Session::get('info'))
<div class="toast custom_toast bg-info show session">
  <div class="toast-header text-white bg-info">
    <span class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></span>
  </div>
  <div class="toast-body text-white">{{ $message }}</div>
</div>
@endif
@if ($message = Session::get('inffo'))
<div class="toast custom_toast bg-light show session">
  <div class="toast-header text-white bg-light">
    <span class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></span>
  </div>
  <div class="toast-body text-white">{{ $message }}</div>
</div>
@endif

@if ($message = Session::get('errors'))
  @php $top =15; @endphp
 @foreach ($message->all() as $error)
 <div class="toast custom_toast bg-danger show session" style="top:{{$top}}px">
  <div class="toast-header text-white bg-danger">
    <span class="btn-close"><i class="fa fa-times" aria-hidden="true"></i></span>
  </div>
  <div class="toast-body text-white">{{$error}}</div>
</div>
@php $top = $top+50; @endphp
 @endforeach
@endif


@push('styles')
@endpush
@push('scripts')
<script type="text/javascript">
  $('.btn-close').on('click',function() {
    $(this).parents('.show').css('display','none');
  });
	$(".session").fadeTo(3000, 500).slideUp(500, function(){
	    $(".session").slideUp(500);
	});
</script>
@endpush