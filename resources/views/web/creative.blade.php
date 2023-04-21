
@extends('webuserlayout.user_design')

@section('content')
	<?php use Illuminate\Support\Facades\Input;?>
<div class="d-flex text-center">
		<div class="col-md-6 col-sm-9 mx-auto mt-5 pt-2">
			<h1 class="manager-reg-heading mt-2">Welcome as Creative!</h1>
		</div>
	</div>
	<div class="col-sm-9 mx-auto">
		<p class="man-reg-sub-heading text-center"> Please fill your details below</p>
	</div>

	<div class="container pt-4">
		<div class="bg-white rounded-xl pt-4 pb-1 shadow-sm form-home form-login form-register">
			@if($errors->any())
			<div class="alert alert-danger">

              <strong>{{ implode('', $errors->all(':message')) }}</strong>
            </div>

			@endif
			@if(Session::has('flash_message_error'))
	            <div class="alert alert-success alert-block">
	                <button type="button" class="close" data-dismiss="alert">×</button>
	                <strong style = "color:red">{{ Session::get('flash_message_error') }}</strong>
	            </div>
	         @endif
			<form method="post" id="creativeFrm" action= "{{ url('/register-creative') }}" enctype="multipart/form-data">{{ csrf_field() }}
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
														<form>

															<span class="click-to-upload manager-edit-profile font-14-weight500 mt-2">

									<span class="mt-20"><img class="rounded-xl" src="assets/images/marc.jpg" width="66" height="66" id="addPreview"></span><label class="file-upload main-text-color ml-2" for="fileUpload">Click Here  <span class="ml-1 margin-top2 upload-text"> to Upload Profile Image</span></label>

									<input class="custom-file-input prevFile" type="file" id="fileUpload" name="fileUpload"  onchange="loadFile(event)">
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
					<hr>
				</div>

				<div class="col-md-12 mt-4">
					<div class="row">
						<div class="form-group col-md-4 home pd-right">
							<label for="First Name">First Name</label>
							<input type="text" class="form-control" id="firstName" placeholder="First Name" name="firstName" value="{{ Input::old('firstName') }}">
						</div>
						<div class="form-group col-md-4 home pd-right">
							<label for="Last Name">Last Name</label>
							<input type="text" class="form-control" id="lastName" placeholder="Last Name"name="lastName" value="{{ Input::old('lastName') }}">
						</div>
						<div class="form-group col-md-4 home">
							<label for="Website">Website</label>
							<input type="text" class="form-control" id="company" placeholder="Website" name="website" value="{{ Input::old('website') }}">
						</div>
						<div class="form-group col-md-4 home form-register  pd-right">
							<label for="Stage">Stage</label>
							<input type="text" class="form-control" id="fb" placeholder="Stage" name="stage" value="{{ Input::old('stage') }}">
						</div>
						<div class="form-group col-md-4 home form-register  pd-right">
							<label for="Gender">Gender</label>
							<select class="form-control" name="gender">
								<option value="">Select Gender</option>
								@foreach($genders as $gender)
								<option value="{{$gender->id}}"  {{ (Input::old('gender') == $gender->id ? "selected":"") }}>{{$gender->title}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-4 home form-register">
							<label for="Type">Type</label>
							<select class="form-control" name="type">
								<option value="">Select Type</option>
								@foreach($types as $type)
								<option value="{{$type->id}}"  {{ (Input::old('type') == $type->id ? "selected":"") }}>{{$type->title}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-4 home form-register pd-right">
							<label for="Type">Performance Rights Organization</label>
							<select class="form-control" name="pro">
								<option value="">Select PRO</option>
								<option value="N/A">N/A</option>
								<option value="BMI">BMI</option>
								<option value="SESAC">SESAC</option>
								<option value="ASCAP">ASCAP</option>
								<option value="SAG-AFTRA">SAG-AFTRA</option>
							</select>
						</div>
						<div class="form-group col-md-4 home form-register pd-right">
							<label for="Type">Craft</label>
							<select class="form-control" name="craft">
								<option value="">Select Craft</option>
								@foreach($crafts as $craft)
								<option value="{{$craft->id}}"  {{ (Input::old('craft') == $craft->id ? "selected":"") }}>{{$craft->title}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-4 home form-register">
							<label for="Type">Genre</label>
							<select class="form-control" name="genre">
								<option value="">Select Genre</option>
								@foreach($genres as $genre)
								<option value="{{$genre->id}}" {{ (Input::old('genre') == $genre->id ? "selected":"") }}>{{$genre->title}}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="row">
						<div class="col-md-12">
							<h5 class="pb-1 mt-3 stram-text">Currently Streaming On <span class="float-right mt-1"> <a href="javascript:void(0)" class="stram-link addMore"> Add Link</a></span></h5>
						</div>
					</div>
				</div>
				<div class="col-md-12 mt-3 fieldGroup">
					<div class="row">
						<div class="form-group col-md-3 pd-right form-home form-login form-register">
							<div class="nm-dropdown home drop-down">
								<select class="form-control" name="platform[]">
									<option value="">Select Platform</option>
									@foreach($platforms as $platform)
									<option value="{{$platform->id}}">{{$platform->title}}</option>
									@endforeach
								</select>
							</div>
							<!-- End This -->
						</div>
						<div class="form-group col-lg-8  col-md-11 pd-right pr-r15 form-home form-login form-register width-r90">
							<input type="text" class="form-control" id="link" placeholder="https://www.Facebook.com/in/rajat-kumar-05576919" name="link[]">
						</div>
						<div class="form-group col-md-1 d-flex justify-content-center align-items-center width-r10">
							<a href="#" class="remove"><img src="assets/images/delete.svg"></a>
						</div>
					</div>
				</div>
				<div class="col-md-12 mt-3 fieldGroupCopy" style="display: none;">
					<div class="row">
						<div class="form-group col-md-3 pd-right form-home form-login form-register">
							<div class="nm-dropdown home drop-down">
								<select class="form-control" name="platform[]">
									<option value="">Select Platform</option>
									@foreach($platforms as $platform)
									<option value="{{$platform->id}}">{{$platform->title}}</option>
									@endforeach
								</select>
							</div>
							<!-- End This -->
						</div>
						<div class="form-group col-lg-8  col-md-11 pd-right pr-r15 form-home form-login form-register width-r90">
							<input type="text" class="form-control" id="link" placeholder="https://www.Facebook.com/in/rajat-kumar-05576919" name="link[]">
						</div>
						<div class="form-group col-md-1 d-flex justify-content-center align-items-center width-r10">
							<a href="#" class="remove"><img src="assets/images/delete.svg"></a>
						</div>
					</div>
				</div>
				<div id="phone_error"></div>
				<div class="col-md-12">
					<div class="row">
						<div class="form-group col-md-6">
							<label for="influencers">Influencers </label>
							<textarea class="form-control" name="influencers" rows="5" cols="40" placeholder="Sample Message">{{ Input::old('influencers') }}</textarea>
						</div>
						<div class="form-group col-md-6">
							<label for="Social Media Links">Social Media Links </label>
							<input type="text" class="form-control" name="social_media_links" placeholder="Social Media Links" value="{{ Input::old('social_media_links') }}">
						</div>
					</div>
				</div>
				<div class="form-group col-md-12 two-radio">
					<label for="country">Independent or Under Contract? </label>
				</div>
				<fieldset class="form-group col-md-12 radios dep-ind">
					<div class="row">
						<div class="col-lg-2 col-md-3 col-sm-4">
							<div class="form-check rdio">
								<div class="rdio rdio-primary radio-inline">
									<input name="status" value="1" id="radio1" type="radio"  {{ (Input::old('status') == 1 ? "checked":"") }}>
									<label class="form-check-label" for="radio1">Independent</label>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-3 col-sm-4">
							<div class="form-check rdio">
								<div class="rdio rdio-primary radio-inline">
									<input name="status" value="2" id="radio2" type="radio" style="margin-left: -4px !important;"  {{ (Input::old('status') == 2 ? "checked":"") }}>
									<label  class="form-check-label" for="radio2">Under Contract</label>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
				</div>
				<div class="col-md-12">
					<div class="d-flex">
						<div class="pb-5 mt-5  align-self-center mx-auto col-md-6">
							<button type="submit" class="btn btn-lg btn-primary btn-login w-100 ">Register</button>
						</div>
					</div>
				</div>
			</form>
			
	</div>
@endsection
