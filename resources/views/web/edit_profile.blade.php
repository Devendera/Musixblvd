@extends('webuserlayout.profile_design')
@section('content')
<div class="container pt-4 mt-4">		

			<form method="post" action= "{{ url('/updateManager') }}" id="manager_edit_form" enctype="multipart/form-data">{{ csrf_field() }}
			<div class="bg-white rounded-xl mt-4 pt-4 pb-1 shadow-sm form-home form-login form-register">
						<div class="col-md-12">
				<div class="bordr-botm">
					<h5 class="pb-1 profile-manager">Edit Profile</h5>
				</div>
			</div>
				<div class="mt-4">
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
																<span><img src="{{url('public/img/users/')}}/{{$userData->img}}" id="blah" width="39" height="31"></span>
																<label class="file-upload main-text-color ml-2" for="fileUpload">Click Here</label>
																<span class="ml-1 margin-top2 upload-text"> to Upload Profile Image</span>
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
					</div>
				</div>
				
				<div class="col-md-12 mt-4">
					<div class="row">
					<div class="form-group col-md-4 home pd-right">
                              <label for="First Name">First Name</label>
                              <input type="text"class="form-control" id="firstName" name="firstname" value=<?php print_r($nameArr[0]);?> >
							  <div id="firstname_error"></div>
							</div>
                           <div class="form-group col-md-4 home pd-right">
                              <label for="Last Name">Last Name</label>
                              <input type="text" class="form-control" id="lastName" name="lastname"value=<?php print_r($nameArr[1]);?> >
							  <div id="lastname_error"></div>
							</div>
						<div class="form-group col-md-4 home">
							<label for="Management Company">Management Company</label>
							<input type="text" class="form-control" id="company" name="company" placeholder="Management Company" value={{$userData->management_company}}>
							<div id="company_error"></div>
						</div>
						<div class="form-group col-md-4 home form-register  pd-right">
							<label for="Facebook">Facebook</label>
							<input type="text" class="form-control" placeholder="Facebook" id="fb" name="facebook" value= {{$userData->facebook}} >
							<div id="fb_error"></div>
						</div>
						<div class="form-group col-md-4 home pd-right">
							<label for="Instagram">Instagram</label>
							<input type="text" class="form-control" id="instagram" placeholder="Instagram" name="instagram" value= {{$userData->instagram}} >
							<div id="instagram_error"></div>
						</div>
						<div class="form-group col-md-4 home">
							<label for="Twitter">Twitter</label>
							<input type="text" class="form-control" id="Twitter" placeholder="Twitter" name="twitter" value= {{$userData->twitter}} >
						<div id="twitter_error"></div>
						</div>
					</div>
				</div>
					</div>
				<div class="col-md-12">
			<div class="d-flex">
				<div class="pb-4 mt-4 pt-4 align-self-center mx-auto col-md-6">
					<button type="submit" class="btn btn-lg btn-primary btn-login w-100">Update</button>
				</div>
			</div>
		</div>
			</form>
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
