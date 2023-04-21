
@extends('webuserlayout.user_design')
@section('content')

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6 d-md-flex d-none pd0">
				<div class="bg-login login-higt">
				<div class="container">
				<div class="row align-items-center">
				<div class="mx-auto">
				<div class="justify-content-center">
				<div class="welcome-to">
				<div class="text-white login-wel">Welcome to</div>
				<div class="text-white brand-name">MusixBlvd</div>
				<p class="tag-line mt-4">Discover the World's Top Artists, Managers<br> and Recording Studios</p>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>
				<div class="col-md-6">
				<div class="max-fit">

				<h2 class="pb-3 text-center mt-3 login-text mt-70">Login to your Account</h2>
				@if(Session::has('flash_message_error'))
	                <div class="alert alert-danger alert-dismissible">

	                    <strong>{{ Session::get('flash_message_error') }}</strong>
	                </div>
             	@endif

		         @if(Session::has('flash_message_success'))
		            <div class="alert alert-success alert-block alert-dismissible">
		                <button type="button" class="close" data-dismiss="alert">×</button>
		                <strong style = "color:green">{{ Session::get('flash_message_success') }}</strong>
		            </div>
		         @endif
				<div class="form-login ml-sm-3 mt-4">

				<form  method="post" action="{{ url('/login') }}" id="loginForm" autocomplete="on" enctype="multipart/form-data">{{ csrf_field() }}
				<div class="row">
				<div class="form-group col-md-12">
				<label for="email">Email </label>
				<input type="text"  class="form-control" id="email" name="email" placeholder="Email">
				<span class="icon email-icon"><img src="assets/images/email.svg" alt="Password"></span>
				<div id="email_error" class="mt-1 v_error"></div>
				</div>
				<div class="form-group col-md-12">
				<label for="password">Password </label>
				<input type="password"  class="form-control" id="password" name="password" placeholder="Password">
				<span class="icon pass-icon"><img src="assets/images/password.svg" alt="Password"></span>
				<div id="password_error" class="mt-1 v_error"></div>
				</div>
				</div>
				<div class="d-flex align-items-center justify-content-end">
				<a class="forgot"  href="{{ url('/forgot-password') }}">Forgot Password?</a>
				</div>
				<div class="pb-2 mt-4">
				<button type="submit" class="btn btn-lg btn-primary btn-login w-100 mt-2">LOGIN</button>
				</div>
				</form>
				<div class="mt-4 p-5 text-center have-account">
				<span class="account">Don't have an Account?</span> <a class="join" href="{{ url('/register') }}">Join Here.</a>
				</div>
				</div>
				</div>
				</div>
				</div>
				</div>

			@endsection
