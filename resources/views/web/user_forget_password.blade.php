@extends('webuserlayout.user_design')

@section('content')

<div class="container-fluid">
			<div class="row">
				<div class="col-md-6  d-md-flex d-none pd0">
					<div class="bg-login">
						<div class="container h-100 forget-page-height">
							<div class="row align-items-center h-100">
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
						<h2 class="pb-3 text-center mt-3 login-text mt-70">Forget Password</h2>
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

                        <form  method="post" action="{{ url('/forgot-password') }}" id="forgetPassword" enctype="multipart/form-data">{{ csrf_field() }}
								<div class="row">
									<div class="form-group col-md-12">
										<label for="email">Please Enter your email  </label>
										<input type="email" class="form-control" id="email" name="email" placeholder="Email">
										<span class="icon email-icon"><img src="assets/images/email.svg" alt="Password"></span>
									</div>
								</div>
								<div class="pb-5 mt-4">
									<button type="submit" class="btn btn-lg btn-primary btn-login w-100 mt-2">Submit</button>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
@endsection
