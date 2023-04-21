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
    <script src="{{asset('assets/js/page-validation.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
</body>
</html>
