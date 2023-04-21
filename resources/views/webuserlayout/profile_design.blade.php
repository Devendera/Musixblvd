<html lang="en">
 <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0"/> 
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>MusixBlvd Home</title>
	<link rel="icon" type="image/png" href="{{asset('favicon.png')}}"/>
    <!-- Bootstrap core CSS -->
	<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!-- Custom styles for this template -->
    <link  href="{{asset('assets/css/style.css')}}" rel="stylesheet">
    <link  href="{{asset('assets/css/responsive.css')}}" rel="stylesheet">
   <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body <?php $class = session('lang'); ?> class="<?php echo $class; ?>">
 <!---- Youtube Modal -->
 <div id="youtubeModal" class="modal fade" role="dialog">
      <div class="modal-dialog agent-dialog">
         <!-- Modal content-->
         <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Connected To Youtube</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
	   <form  method="post" id="youtubeForm" action="{{ url('/getStreaming') }}" enctype="multipart/form-data">{{ csrf_field() }}
      <div class="modal-body agent-body">
                     <div class="form-group home">
                     <label for="Studio Name">Channel Url</label>
                     <input type="text" class="form-control" id="channel" name="channel" placeholder="Please Enter Channel Url">
                  </div>
      </div>
      <div class="modal-footer">
      <button class="btn btn-sm btn-primary btn-edit mt-3" type="submit">Submit</button>
      </div>
      </form>
      </div>
      </div>
      </div>
      <!---- Spotify Modal -->
      <div id="spotifyModal" class="modal fade" role="dialog">
      <div class="modal-dialog agent-dialog">
         <!-- Modal content-->
         <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Connected To Spotify</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body agent-body">
      <form  method="post" id="spotifyForm" action="{{ url('/getSpotify') }}" enctype="multipart/form-data">{{ csrf_field() }}
                     <div class="form-group home">
                     <label for="Studio Name">Please Enter Username</label>
                     <input type="text" class="form-control" id="spotify" name="spotify" placeholder="Please enter username">
                  </div>
      </div>
      <div class="modal-footer">
      <button class="btn btn-sm btn-primary btn-edit mt-3" type="submit">Submit</button>
      </div>
      </form>
      </div>
      </div>
      </div>
@if (Auth::guard('web')->check() &&  Auth::guard('web')->user()->has_profile==1)
@include('webuserlayout.profile_header')
@else
@include('webuserlayout.user_header')
@endif
    @yield('content')
    @include('webuserlayout.user_footer')
    <script src="{{asset('assets/js/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('assets/validation/dist/jquery.validate.js')}}"></script>
    <script src="{{asset('assets/validation/dist/additional-methods.min.js')}}"></script>
    <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBccj6ZlVp5d-4K_LfC5cpudMnpg4zIUHE&libraries=places"></script>
    <script src="{{asset('assets/js/jquery.geocomplete.min.js')}}"></script>
    <script src="{{asset('assets/js/page-validation.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
</body>
</html>
