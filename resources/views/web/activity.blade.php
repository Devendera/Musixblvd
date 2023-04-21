@extends('webuserlayout.profile_design')
@section('content')
<div class="home-page mb-5">
   <div class="container">
      <div class="row">
         <div class="col-md-12 mt-5 pt-3">
            <div class="float-left">
               <span><img class="mt-1" src="assets/images/activity.svg"></span>
            </div>
            <div class="float-left ml-2">
               <h2 class="top-project-heading">
                  Activities                    
               </h2>
            </div>
         </div>
         <div class="col-md-12">
            <div class="pro-border-bottom"></div>
         </div>
      </div>
   </div>
   <div class="container mt-3 termsandcond mb-5 viewactivity">
      <div class="bg-white rounded-xl pt-2 pb-1 shadow-sm form-home">
         <div class="col-md-12 pr-4 pl-4">
            <ul class="activity-list">
              
            </ul>
            {{ csrf_field() }}
            <div id="post_data"></div>
            <div class="container" id="post">
               {{-- here loads posts --}}
            </div>
         </div>
      </div>
   </div>
</div>
<script src="{{asset('assets/js/jquery-3.2.1.min.js')}}"></script>
<script>
   $(document).ready(function () {
      $(document).on('click', '#load-more', function () {
         $.ajax({
            url: "post",
            success: function (datas) {
               
               $('#post').append(datas);
            }
         });
      });
   });
   
   $(document).ready(function(){
    var _token = $('input[name="_token"]').val();
    load_data('', _token);
    function load_data(id="", _token)
    {
     $.ajax({
      url:"{{ route('loadmore.load_data') }}",
      method:"POST",
      data:{id:id, _token:_token},
      success:function(data)
      {
       $('#load_more_button').remove();
       $("#post_data").append(data);
      }
     })
    }
    $(document).on('click', '#load_more_button', function(){
     var id = $(this).data('id');
     $('#load_more_button').html('<b>Loading...</b>');
     load_data(id, _token);
    });
   
   });
</script>
@endsection