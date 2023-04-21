@extends('webuserlayout.profile_design')

@section('content')
<style>
    #formdiv{
        text-align: center;
}
    </style>
				<div class="col-md-6">
					<div class="max-fit">

						@if(Session::has('flash_message_error'))
	                <div class="alert alert-danger alert-dismissible">

	                    <strong>{{ Session::get('flash_message_error') }}</strong>
	                </div>
             	@endif

		         @if(Session::has('flash_message_success'))
		            <div class="alert alert-success alert-block  alert-dismissible">
		                <button type="button" class="close" data-dismiss="alert">×</button>
		                <strong style = "color:green">{{ Session::get('flash_message_success') }}</strong>
		            </div>
		         @endif
						<div class="form-login ml-sm-3 mt-4" id="formdiv">

                        <form method="post" action="{{ url('/saveChangePassword') }}" onsubmit="return validateForm()" >
    @csrf
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

        <script>
function validateForm() {

	hideAllErrors();

        var password = $("#password").val();
        var cPassword = $("#cpassword").val();

	 if(password == ""){
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

	 $("#password_error").removeClass("text-danger").text("");
	 $("#cpassword_error").removeClass("text-danger").text("");
}
</script>
	@endsection
