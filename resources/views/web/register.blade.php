@extends('webuserlayout.user_design') @section('content')
<header class="bg-white border-bottom shadow-sm fixed-top">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<nav class="navbar navbar-expand-lg navbar-light my-2 my-md-0 d-flex">
					<a class="navbar-brand my-0 font-weight-normal" href="{{ url('/user-index') }}"> <img src="assets/images/logo.png" width="80" height="80" alt="logo"> </a> <a class="ml-md-auto ml-sm-auto mr-sm-2 mr-md-2 login-link order-lg-2" href="{{ url('/login') }}">log in</a> <a href="{{ url('/register') }}" class="ml-md-3 ml-sm-3 btn btn-lg btn-primary signup-btn order-lg-2">Sign up</a>
					<!--<a class="ml-md-3 ml-sm-3 btn btn-lg btn-primary signup-btn order-lg-2" href="register.html">Sign up</a> -->
					<button class="navbar-toggler order-lg-3 ml-sm-3" type="button" data-toggle="collapse" data-target="#navbar-list-2" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
					<div class="collapse navbar-collapse order-lg-1" id="navbar-list-2">
						<ul class="navbar-nav mr-auto">
							<li class="nav-item active"> <a class="nav-link" href="{{url('/user-index')}}">Home <span class="sr-only">(current)</span></a> </li>
							<li class="nav-item"> <a class="nav-link" href="{{url('/addproject')}}">projects</a> </li>
							<li class="nav-item"> <a class="nav-link" href="{{url('/managers')}}">managers</a> </li>
							<li class="nav-item"> <a class="nav-link" href="{{url('/studios')}}">studios</a> </li>
							<li class="nav-item"> <a class="nav-link" href="#">studios map</a> </li>
							<li class="nav-item"> <a class="nav-link" href="{{url('/connected-users')}}">connections</a> </li>
							<li class="nav-item"> <a class="nav-link" href="{{url('/explore')}}">explore</a> </li>
							<li class="nav-item"> <a class="nav-link" href="/faq">faq</a> </li>
							<li class="nav-item"> <a class="nav-link" href="{{url('/contact')}}">contact us</a> </li>
						</ul>
					</div>
				</nav>
			</div>
		</div>
	</div>
</header>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-6 d-md-flex d-none pd0">
			<div class="bg-login login-higt">
				<div class="container h-100">
					<div class="row align-items-center h-100">
						<div class="mx-auto">
							<div class="justify-content-center">
								<div>
									<div class="text-white login-wel">Welcome to</div>
									<div class="text-white brand-name">MusixBlvd</div>
									<p class="tag-line mt-4">Discover the World's Top Artists, Managers
										<br> and Recording Studios</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="max-fit">
				<h2 class="pb-3 text-center mt-3 login-text mt-70">Register to Create Account</h2> @if(Session::has('flash_message_error'))
				<div class="alert alert-danger  alert-dismissible"> <strong>{{ Session::get('flash_message_error') }}</strong> </div> @endif @if(Session::has('flash_message_success'))
				<div class="alert alert-success alert-block alert-dismissible">
					<button type="button" class="close" data-dismiss="alert">×</button> <strong style="color:green">{{ Session::get('flash_message_success') }}</strong> </div> @endif
				<div class="form-login form-register ml-sm-3 mt-4">
					<form method="post" action="{{ url('/register') }}" id="register_form" enctype="multipart/form-data">{{ csrf_field() }}
						<div class="row">
							<div class="form-group col-md-12">
								<label for="email">Email </label>
								<input type="text" class="form-control" id="email" name="email" placeholder="Email"> </div>
							<div id="email_error"></div>
						</div>
						<div class="row">
							<div class="col-md-6 pd-right">
								<div class="form-group mt-2">
									<label for="create password">Create Password </label>
									<input type="password" class="form-control" id="password" name="password" placeholder="Create Password"> </div>
								<div id="password_error"></div>
							</div>
							<div class="col-md-6">
								<div class="form-group mt-2">
									<label for="confirm password">Confirm Password </label>
									<input type="password" class="form-control" id="confirmPassword" name="sub" placeholder="Confirm Password"> </div>
								<div id="cpassword_error"></div>
							</div>
							<div id="notsame_error"></div>
						</div>
						<div class="row">
							<div class="form-group col-md-12">
								<label for="country">Select one Option </label>
							</div>
							<fieldset class="form-group col-md-12 radios register-login">
								<div class="row">
									<div class="col-md-4 col-sm-3">
										<div class="form-check rdio">
											<input class="form-check-input" type="radio" name="gridRadios" id="gridRadios1" value="option1" checked>
											<div class="radiobuttons">
												<div class="rdio rdio-primary radio-inline">
													<input name="type" value="1" id="radio1" type="radio">
													<label class="form-check-label" for="radio1">Creative</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4 col-sm-3">
										<div class="form-check rdio">
											<input class="form-check-input" type="radio" name="gridRadios" id="gridRadios2" value="option2" checked>
											<div class="radiobuttons">
												<div class="rdio rdio-primary radio-inline">
													<input name="type" value="2" id="radio2" type="radio">
													<label class="form-check-label" for="radio2">Manager</label>
												</div>
											</div>
										</div>
									</div>
									<div class="col-md-4 col-sm-5 pd0 ml5 studio-record">
										<div class="form-check rdio">
											<input class="form-check-input" type="radio" name="gridRadios" id="gridRadios3" value="option3">
											<div class="radiobuttons">
												<div class="rdio rdio-primary radio-inline">
													<input name="type" value="3" id="radio3" type="radio">
													<label class="form-check-label" for="radio3">Recording Studio</label>
												</div>
											</div>
										</div>
									</div>
									<div id="type_error"></div>
								</div>
							</fieldset>
						</div>
						<div class="row">
							<div class="form-group col-md-12 select-long">
								<label for="country">Country </label>
								<select class="form-control country" name="country" id="country" data-url="{{URL::to('/')}}">
									<option value="">Select</option>
									@foreach($countries as $country)
									<option value="{{$country->id}}">{{$country->name}}</option>
									@endforeach 
								</select>
							</div>
							<div id="country_error"></div>
						</div>
						<div class="row">
							<div class="col-md-6 pd-right">
								<div class="form-group mt-2 home">
									<label for="state">State</label>
									<select class="form-control" id="response" name="state" id="state">
										<option value="">Please Select Country First</option>
									</select>
								</div>
								<div id="state_error"></div>
							</div>
							<div class="col-md-6">
								<div class="form-group mt-2">
									<label for="city">City </label>
									<input type="text" class="form-control" id="city" name="city" placeholder="City"> </div>
								<div id="city_error"></div>
							</div>
						</div>
						<div class="pb-2 mt-4">
							<button type="submit" class="btn btn-lg btn-primary btn-login w-100 mt-2">REGISTER</button>
						</div>
					</form>
					<div class="p-4 m-3 text-center have-account"> <span class="account">Already have an account?</span> <a class="join" href="{{ url('/login') }}">Login</a> </div>
				</div>
			</div>
		</div>
	</div>
</div> @endsection