
@extends('webuserlayout.user_design')

@section('content')
<div class="d-flex text-center">
		<div class="col-md-6 col-sm-9 mx-auto mt-5 pt-2">
			<h1 class="manager-reg-heading mt-2">Welcome as Manager!</h1>
		</div>
	</div>
	<div class="col-sm-9 mx-auto">
		<p class="man-reg-sub-heading text-center"> Please fill your details below</p>
	</div>
	<div class="container pt-4  mb-5">		
			<form method="post" action= "{{ url('/registerManager') }}"  enctype="multipart/form-data" id="manager_form">{{ csrf_field() }}
			<div class="bg-white rounded-xl pt-4 pb-1 shadow-sm form-home form-login form-register">
				<div class="mt-1">
					<div class="col-md-12 card border-0 rounded-md">
						<div class="card-body manager-upload-img">
							<div class="row align-items-center">
								<div class="col-md-12">
									<div class="mx-auto">
										<div class="justify-content-center">
											<div class="d-flex justify-content-start">
												<div class="col-md-12">
													<div class="create-click">


															<span class="click-to-upload font-14-weight500 mt-2">
																<span><img src="assets/images/image-upload.svg" width="39" height="31" id="blah"></span>
																<label class="file-upload main-text-color ml-2" for="fileUpload">Click Here</label> <span class="ml-1 margin-top2 upload-text"> to Upload Profile Image</span>

																<input class="custom-file-input" type="file" id="fileUpload" name="profile">


															</span>


														<span class="float-right mt-2 pt-1">Upload Only JPG, PNG</span>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id ="img_error"></div>
					</div>
				</div>

				<div class="col-md-12 mt-4">
				<hr>
				</div>

				<div class="col-md-12 mt-4">
					<div class="row">
						<div class="form-group col-md-4 home pd-right">
							<label for="First Name">First Name</label>
							<input type="text" class="form-control" name="firstname" id="firstName" placeholder="First Name">
							<div id="firstname_error"></div>
						</div>
						<div class="form-group col-md-4 home pd-right">
							<label for="Last Name">Last Name</label>
							<input type="text" class="form-control" name="lastname" id="lastName" placeholder="Last Name">
							<div id="lastname_error"></div>
						</div>
						<div class="form-group col-md-4 home">
							<label for="Management Company">Management Company</label>
							<input type="text" class="form-control" name="company" id="company" placeholder="Management Company">
							<div id="company_error"></div>
						</div>
						<div class="form-group col-md-4 home form-register  pd-right">
							<label for="Facebook">Facebook</label>
							<input type="text" class="form-control" name="facebook" id="fb" placeholder="Facebook">
							<div id="fb_error"></div>
						</div>
						<div class="form-group col-md-4 home pd-right">
							<label for="Instagram">Instagram</label>
							<input type="text" class="form-control" name="instagram" id="instagram" placeholder="Instagram">
						    <div id="instagram_error"></div>
						</div>
						<div class="form-group col-md-4 home">
							<label for="Twitter">Twitter</label>
							<input type="text" class="form-control" name="twitter" id="twitter" placeholder="Twitter">
							<div id="twitter_error"></div>
						</div>
					</div>
				</div>

		</div>
		<div class="col-md-12">
			<div class="d-flex">
				<div class="pb-2 mt-4 pt-4 align-self-center mx-auto col-md-6">
					<button type="submit" class="btn btn-lg btn-primary btn-login w-100 mt-2">Register</button>
				</div>
			</div>
			</form>
		</div>
	</div>

<script>
	fileUpload.onchange = evt => {
const [file] = fileUpload.files
 if (file) {
blah.src = URL.createObjectURL(file)
  }
}
</script>
@endsection