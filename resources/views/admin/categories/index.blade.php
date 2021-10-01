@extends('layouts.app')

@section('content')
<div class="py-4">
   <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
      <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
         <li class="breadcrumb-item">
            <a href="{{route('admin.dashboard')}}">
               <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
               </svg>
            </a>
         </li>
         <li class="breadcrumb-item active" aria-current="page">Categories List</li>
      </ol>
   </nav>
   <div class="d-flex justify-content-between w-100 flex-wrap">
      <div class="mb-3 mb-lg-0">
         <h1 class="h4">List of categories</h1>
         <!-- <p class="mb-0">Dozens of reusable components built to provide buttons, alerts, popovers, and more.</p> -->
      </div>
      <!-- <div>
         <a href="https://themesberg.com/docs/volt-bootstrap-5-dashboard/components/tables/" class="btn btn-outline-gray-600 d-inline-flex align-items-center">
            <svg class="icon icon-xs me-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
               <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
            </svg>
            Bootstrap Tables Docs
         </a>
      </div> -->
   </div>
</div>
<div class="card border-0 shadow mb-4">
   <div class="card-body">
      <div class="table-responsive">
         <table class="table table-centered table-nowrap mb-0 rounded tableCategories">
            <thead class="thead-light">
               <tr>
                  <th class="border-0 rounded-start">#</th>
                  <th class="border-0">name</th>
                  <th class="border-0">image</th>
                  <th class="border-0">Action</th>
               </tr>
            </thead>
            <tbody>
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection

@push('styles')
<link type="text/css" href="{{asset('assets/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{asset('assets/js/jquery.dataTables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/js/dataTables.bootstrap5.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">

  $(document).ready(function() {
    $('.tableCategories').DataTable({
      "order": [[ 0, "desc" ]],
        processing: true,

        serverSide: true,

        ajax: "{{ route('admin.categories.index') }}",

        columns: [

            {data: 'id', name: 'id'},

            {data: 'name', name: 'name'},

            {data: 'image', name: 'image'},

            {

                data: 'action', 

                name: 'action', 

                orderable: true, 

                searchable: true

            },

        ]

    });

  } );



  $.ajaxSetup({

          headers: {

              'X-CSRF-TOKEN': "{{ csrf_token() }}"

          }

      });

  function deleteData(e){

    var table = $('.tableCategories').DataTable();

    var id = e.getAttribute('data-id');

    var url = e.getAttribute('data-url');

      swal({

          title: "Are you sure?",

          text: "Once deleted, you will not be able to recover this imaginary file!",

          icon: "warning",

          buttons: true,

          dangerMode: true,

      })

      .then((willDelete) => {

          if (willDelete) {

              $.ajax({

                  url : url,

                  type : "POST",

                  data : {'_method' : 'DELETE'},

                  success: function(){

                      swal({

                          title: "Success!",

                          text : "Post has been deleted \n Click OK to refresh the page",

                          icon : "success",

                      }).then(function(){

                          $(e).closest("tr").remove();

                      });

                  },

                  error : function(){

                      swal({

                          title: 'Opps...',

                          text : "Something Wrong",

                          type : 'error',

                          timer : '1500'

                      })

                  }

              })

          } else {

          swal("Your imaginary file is safe!");

          }

      });

  }

</script>

@endpush