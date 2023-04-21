<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0,user-scalable=0"/>
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.1.1">
    <title>MusixBlvd Forget Password</title>
	<link rel="icon" type="image/png" href="{{asset('favicon.png')}}"/>
    <!-- Bootstrap core CSS -->
	<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{asset('assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
    <!-- Custom styles for this template -->
    <link  href="{{asset('assets/css/style.css')}}" rel="stylesheet">
	<body>
		<header class="bg-white border-bottom shadow-sm fixed-top">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<nav class="navbar navbar-expand-lg navbar-light my-2 my-md-0 d-flex">
							<a class="navbar-brand my-0 font-weight-normal" href="{{ url('/user-index') }}">
								<img src="{{asset('assets/images/logo.png')}}" width="80" height="80" alt="logo">
							</a>
							<a class="ml-md-auto ml-sm-auto mr-sm-2 mr-md-2 login-link order-lg-2" href="{{ url('/login') }}">log in</a>
							<a class="ml-md-3 ml-sm-3 btn btn-lg btn-primary signup-btn order-lg-2" href="{{ url('/register') }}">Sign up</a>
							<button class="navbar-toggler order-lg-3 ml-sm-3" type="button" data-toggle="collapse" data-target="#navbar-list-2" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>
							<div class="collapse navbar-collapse order-lg-1" id="navbar-list-2">
								<ul class="navbar-nav mr-auto">
																	<li class="nav-item active">
									<a class="nav-link" href="{{url('/user-index')}}">Home <span class="sr-only">(current)</span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/addproject')}}">projects</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/managers')}}">managers</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/studios')}}">studios</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#">studios map</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/connected-users')}}">connections</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/explore')}}">explore</a>
								</li>
								<li class="nav-item">
										<a class="nav-link" href="{{url('/faq')}}">FAQ</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/contact')}}">contact us</a>
								</li>
								</ul>
							</div>
						</nav>
					</div>
				</div>
			</div>
		</header>


		<div class="container-fluid">
			<div class="row">
				<div class="col-md-6  d-md-flex d-none pd0">
					<div class="bg-login">
						<div class="container h-100">
							<div class="row align-items-center h-100">
								<div class="mx-auto">
									<div class="justify-content-center">
										<div>
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
						<h2 class="pb-3 text-center mt-3 login-text mt-70">Forget Password</h2>
						@if(Session::has('flash_message_error'))
	                <div class="alert alert-danger alert-dismissible">

	                    <strong>{{ Session::get('flash_message_error') }}</strong>
	                </div>
             	@endif

		         @if(Session::has('flash_message_success'))
		            <div class="alert alert-success alert-block">
		                <button type="button" class="close" data-dismiss="alert">×</button>
		                <strong style = "color:green">{{ Session::get('flash_message_success') }}</strong>
		            </div>
		         @endif
						<div class="form-login ml-sm-3 mt-4">

                        <form method="post" action="{{ url('/saveNewPassword') }}" onsubmit="return validateForm()" >
    @csrf
								<div class="row">
									<div class="form-group col-md-12">
										<label for="email">Please Enter your email  </label>
										<input type="text" class="form-control" id="email" value ={{$email}} name="email" placeholder="Email" readonly>
										<span class="icon email-icon"></span>

									</div>
									<div id ="email_error"></div>
								</div>
								<div class="row">
									<div class="form-group col-md-12">
										<label for="email">Please Enter your Verification Code  </label>
										<input type="text" class="form-control" id="verification"  name="verification" placeholder="Verification Code" >
										<span class="icon email-icon"></span>

									</div>
									<div id ="verification_error"></div>
								</div>
								<div class="row">
									<div class="form-group col-md-12">
										<label for="password">Please Enter your new Passowrd  </label>
										<input type="password" class="form-control" id="password" name="password" placeholder="Password">
										<span class="icon email-icon"></span>
									</div>
                                    <div id ="password_error"></div>
								</div>
								<div class="row">
									<div class="form-group col-md-12">
										<label for="password">Please Enter Confirm Passowrd </label>
										<input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Confirm Password">
										<span class="icon email-icon"></span>
									</div>
                                    <div id ="cpassword_error"></div>
								</div>
								<div class="pb-2 mt-4">
									<button type="submit" class="btn btn-lg btn-primary btn-login w-100 mt-2">Submit</button>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
		<footer class="bg-black text-white text-center bordr-top">
			<!--Social buttons-->
			<div class="text-center">
				<ul class="list-inline">
					<li class="list-inline-item">
						<a href="#!" class="sbtn btn-large mx-1" title="Facebook">
							<i class="fa fa-facebook fa-2x"></i>
						</a>
					</li>
					<li class="list-inline-item">
						<a href="#!" class="sbtn btn-large mx-1" title="Linkedin">
							<i class="fa fa-linkedin fa-2x"></i>
						</a>
					</li>
					<li class="list-inline-item">
						<a href="#!" class="sbtn btn-large mx-1" title="Twitter">
							<i class="fa fa-twitter fa-2x"></i>
						</a>
					</li>
					<li class="list-inline-item">
						<a href="#!" class="sbtn btn-large mx-1" title="Youtube">
							<i class="fa fa-instagram fa-2x" aria-hidden="true"></i>
						</a>
					</li>
				</ul>
			</div>
			<div class="container">
				<div class="row text-center">
					<div class="col-md-12">
						<div class="menu mt-4 mb-4">
							<ul>
								<li>
									<a href="#">Home</a>
								</li>
								<li>
									<a href="#">Projects</a>
								</li>
								<li>
									<a href="#">Managers</a>
								</li>
								<li>
									<a href="#">Studio</a>
								</li>
								<li>
									<a href="#">Studio Map</a>
								</li>
								<li>
									<a href="#">Studio Map</a>
								</li>
								<li>
									<a href="#">Connections</a>
								</li>
								<li>
									<a href="#">Explore</a>
								</li>
								<li>
									<a href="#">Faq</a>
								</li>
								<li>
									<a href="#">Terms</a>
								</li>
								<li>
									<a href="#">Accounts</a>
								</li>
								<li>
									<a href="#">Contact Us</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="copyryt">
				<div class="text-center">
					<div class="col-md-12">
						<p>Copyright © 2021 MusixBlvd. Designed and Developed By Ficode Technologies.</p>
					</div>
				</div>
			</div>
		</footer>
		<!--/.Social buttons-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
        <script>
function validateForm() {

	hideAllErrors();
        var email = $("#email").val();
        var password = $("#password").val();
        var cPassword = $("#cpassword").val();
		var verification = $("#verification").val();

		if(verification == ""){
			$("#verification_error").addClass("text-danger").text("Please enter verification code");
			$("#verification").focus();
            return false;
		}
		else if(password == ""){
			$("#password_error").addClass("text-danger").text("Please enter password ");
			$("#password").focus();
            return false;
		}
		else if(cPassword == ""){
			$("#cpassword_error").addClass("text-danger").text("Please enter confirm password ");
			$("#cpassword").focus();
            return false;
		}
		else if(cPassword != password){
			$("#cpassword_error").addClass("text-danger").text("Password and Confirm Password must be same ");
			$("#cpassword").focus();
            return false;
		}

}
function hideAllErrors() {
	 $("#verification_error").removeClass("text-danger").text("");
	 $("#password_error").removeClass("text-danger").text("");
	 $("#cpassword_error").removeClass("text-danger").text("");
}
</script>
	</body>
</html>
