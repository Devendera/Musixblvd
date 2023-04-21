
<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0"/> 
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>MusixBlvd Home</title>
	<link rel="icon" type="image/png" href="favicon.png"/>
    <!-- Bootstrap core CSS -->
	<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!-- Custom styles for this template -->
	<link  href="{{asset('assets/css/style.css')}}" rel="stylesheet">
	<link  href="{{asset('assets/css/responsive.css')}}" rel="stylesheet">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body>

	
@if (Auth::guard('web')->check() && Auth::guard('web')->user()->has_profile==1)
@include('webuserlayout.profile_header')
@else
@include('webuserlayout.user_header')
@endif





@yield('content')
	
@include('webuserlayout.user_footer')
	
	<!--/.Social buttons-->
	
	
	<script src="{{asset('assets/js/jquery-3.2.1.min.js')}}"></script>
	<script src="{{asset('assets/validation/dist/jquery.validate.js')}}"></script>
	<script src="{{asset('assets/validation/dist/additional-methods.min.js')}}"></script>
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?key=AIzaSyBccj6ZlVp5d-4K_LfC5cpudMnpg4zIUHE&libraries=places"></script>
	<script src="{{asset('assets/js/jquery.geocomplete.min.js')}}"></script>
	<script src="{{asset('assets/js/page-validation.js')}}"></script>
	<script src="{{asset('assets/js/popper.min.js')}}"></script>
	<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('assets/js/custom.js')}}"></script>
    <script>
		$(function() {
			// Set
			var main = $('.nm-dropdown .textfirst')
			var li = $('.nm-dropdown > ul > li.input-option')
			var inputoption = $(".nm-dropdown .option")
			var default_text = 'Select';
			
			// Animation
			main.click(function() {
				main.html(default_text);
				li.toggle('fast');
			});
			
			// Insert Data
			li.click(function() {
				// hide
				li.toggle('fast');
				var livalue = $(this).data('value');
				var lihtml = $(this).html();
				main.html(lihtml);
				inputoption.val(livalue);
			});
		});
	</script>
	 <script>
         jQuery('.edit-notification .dropdown-toggle').on('click', function (e) {
          $(this).next().toggle();
         });
         jQuery('.dropdown-menu.keep-open').on('click', function (e) {
          e.stopPropagation();
         });
         
         if(1) {
          $('body').attr('tabindex', '0');
         }
         else {
          alertify.confirm().set({'reverseButtons': true});
          alertify.prompt().set({'reverseButtons': true});
         }
         $(".rotate").click(function () {
			$(this).toggleClass("down");
		 }); 
      </script>
</body>
</html>
