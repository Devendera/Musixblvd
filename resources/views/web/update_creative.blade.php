@extends('webuserlayout.user_design')
@section('content')
@php
$nameArr = explode(" ", $user['name']);
$fName = $nameArr[0];
$lName = $nameArr[1];
@endphp
	<div class="container mt-5 pt-4">		
			@if($errors->any())
			<div class="alert alert-danger alert-desktop">
              <strong>{{ implode('', $errors->all(':message')) }}</strong>
            </div>
			@endif
			@if(Session::has('flash_message_success'))
	            <div class="alert alert-success alert-desktop">
	                <button type="button" class="close" data-dismiss="alert">×</button>
	                <strong>{{ Session::get('flash_message_success') }}</strong>
	            </div>
	         @endif
			@if(Session::has('flash_message_error'))
	            <div class="alert alert-success alert-block alert-desktop">
	                <button type="button" class="close" data-dismiss="alert">×</button>
	                <strong style = "color:red">{{ Session::get('flash_message_error') }}</strong>
	            </div>
	         @endif
			
			<form method="post" id="creativeEditFrm" action= "{{ url('/update-creative') }}" enctype="multipart/form-data">{{ csrf_field() }}
			<div class="bg-white rounded-xl pt-4 pb-1 shadow-sm form-home form-login form-register">
	
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
														<form>

															<span class="click-to-upload font-14-weight500 mt-2">

									<span class="mt-20"><img class="rounded-xl" src="{{ $user->img !='' ? $user->img : 'assets/images/marc.jpg' }}" width="66" height="66" id="updatePreview"></span>						<label class="file-upload main-text-color ml-2" for="fileUpload">Click Here</label> <span class="ml-1 margin-top2 upload-text"> to Upload Profile Image</span>

									<input class="custom-file-input" type="file" id="fileUpload" name="fileUpload" onchange="loadPreFile(event)">

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
							<input type="text" class="form-control" id="firstName" placeholder="First Name" name="firstName" value="{{ $fName }}">
						</div>
						<div class="form-group col-md-4 home pd-right">
							<label for="Last Name">Last Name</label>
							<input type="text" class="form-control" id="lastName" placeholder="Last Name"name="lastName" value="{{ $lName }}">
						</div>
						<div class="form-group col-md-4 home">
							<label for="Website">Website</label>
							<input type="text" class="form-control" id="company" placeholder="Website" name="website" value="{{$user->creative->website}}">
						</div>
						<div class="form-group col-md-4 home form-register  pd-right">
							<label for="Stage">Country</label>
							<select class="form-control country" name="country" id="country" data-url="{{URL::to('/')}}">
							  <option>Select Country</option>
							    <?php foreach($countries as $country){ ?>
						    	 <option value="{{$country->id}}" <?php if ($country->id == $user->country) { ?>selected="selected"<?php } ?>>{{$country->name}}</option>
							    <?php } ?>
							</select>
						</div>
						<div class="form-group col-md-4 home form-register  pd-right">
							<label for="state">State</label>
							<select class="form-control" id="response" name="state" id="state">
							<?php foreach($state as $value){ ?>
								<option value="{{$value->name}}" <?php if ($value->name == $user->state) { ?>selected="selected"<?php } ?>>{{$value->name}}</option>
							<?php } ?>	
							</select>
						</div>
						<div class="form-group col-md-4 home form-register  pd-right">
							<label for="Stage">City</label>
							<input type="text" class="form-control" id="city" placeholder="City" name="city" value="{{$user->city}}">
						</div>
						<div class="form-group col-md-4 home form-register  pd-right">
							<label for="Stage">Stage</label>
							<input type="text" class="form-control" id="fb" placeholder="Stage" name="stage" value="{{$user->creative->stage}}">
						</div>
						<div class="form-group col-md-4 home form-register  pd-right">
							<label for="Gender">Gender</label>
							<select class="form-control" name="gender">
								<option>Select Gender</option>
								@foreach($genders as $gender)
								<option value="{{$gender->id}}" {{$user->creative->gender == $gender->id ? 'selected' : '' }}>{{$gender->title}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-4 home form-register">
							<label for="Type">Type</label>
							<select class="form-control" name="type">
								<option>Select Type</option>
								@foreach($types as $type)
								<option value="{{$type->id}}" {{$user->creative->type == $type->id ? 'selected' : '' }}>{{$type->title}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-4 home form-register pd-right">
							<label for="Type">Performance Rights Organization</label>
							<select class="form-control" name="pro">
								<option value="">Select PRO</option>
								<option value="N/A" {{$user->creative->pro == 'N/A' ? 'selected' : '' }}>N/A</option>
								<option value="BMI" {{$user->creative->pro == 'BMI' ? 'selected' : '' }}>BMI</option>
								<option value="SESAC" {{$user->creative->pro == 'SESAC' ? 'selected' : '' }}>SESAC</option>
								<option value="ASCAP" {{$user->creative->pro == 'ASCAP' ? 'selected' : '' }}>ASCAP</option>
								<option value="SAG-AFTRA" {{$user->creative->pro == 'SAG-AFTRA' ? 'selected' : '' }}>SAG-AFTRA</option>
							</select>
						</div>
						<div class="form-group col-md-4 home form-register pd-right">
							<label for="Type">Craft</label>
							<select class="form-control" name="craft">
								<option value="">Select Craft</option>
								@foreach($crafts as $craft)
								<option value="{{$craft->id}}" {{$user->creative->craft == $craft->id ? 'selected' : '' }}>{{$craft->title}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group col-md-4 home form-register">
							<label for="Type">Genre</label>
							<select class="form-control" name="genre">
								<option value="">Select Genre</option>
								@foreach($genres as $genre)
								<option value="{{$genre->id}}" {{$user->creative->genre == $genre->id ? 'selected' : '' }}>{{$genre->title}}</option>
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

				@if(!empty($user->platforms))
				@foreach($user->platforms as $k=>$v)
				<div class="col-md-12 mt-3 fieldGroup">
					<div class="row">
						<div class="form-group col-md-3 pd-right form-home form-login form-register">
							<div class="nm-dropdown home drop-down">
								<select class="form-control" name="platform[]">
									<option value="">Select Platform</option>
									@foreach($platforms as $platform)
									<option value="{{$platform->id}}" {{$v->id == $platform->id ? 'selected' : ''}}>{{$platform->title}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group col-lg-8  col-md-11 pd-right pr-r15 form-home form-login form-register width-r90">
							<input type="text" class="form-control"  id="link" placeholder="https://www.Facebook.com/in/rajat-kumar-05576919" name="link[]" value="{{$v->url}}">
						</div>
						<div class="form-group col-md-1 d-flex justify-content-center align-items-center width-r1">
							<a href="#" class="remove"><img src="assets/images/delete.svg"></a>
						</div>
						<!-- End This -->

					</div>
				</div>
				@endforeach
				@else
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
						<div class="form-group col-md-1 d-flex justify-content-center align-items-center width-r1">
							<a href="#" class="remove"><img src="assets/images/delete.svg"></a>
						</div>
					</div>

				</div>
				@endif

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
				<div class="col-md-12">
					<div class="row">
						<div class="form-group col-md-6">
							<label for="influencers">Influencers </label>
							<textarea class="form-control" name="influencers" rows="5" cols="40" placeholder="Sample Message" value="{{$user->creative->influencers}}">{{$user->creative->influencers}}</textarea>
						</div>
						<div class="form-group col-md-6">
							<label for="Social Media Links">Social Media Links </label>
							<input type="text" class="form-control" name="social_media_links" placeholder="Social Media Links" value="{{$user->creative->social_media_links}}">
						</div>
					</div>
				</div>


				<div class="form-group col-md-12">
					<label for="country">Independent or Under Contract? </label>
				</div>
				<fieldset class="form-group col-md-12 radios">
					<div class="row">
						<div class="col-md-3 col-lg-2 col-sm-3">
							<div class="form-check rdio">
								<div class="rdio rdio-primary radio-inline">
									<input name="status" value="1" id="radio1" type="radio" {{$user->creative->status == 1 ? 'checked' : '' }}>
									<label class="form-check-label" for="radio1">Independent</label>
								</div>
							</div>
						</div>
						<div class="col-md-3 col-lg-2 col-sm-3">
							<div class="form-check rdio">
								<div class="rdio rdio-primary radio-inline">
									<input name="status" value="2" id="radio2" type="radio" {{$user->creative->status == 2 ? 'checked' : '' }} style="margin-left: -4px !important;">
									<label  class="form-check-label" for="radio2">Under Contract</label>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
					</div>
				<div class="col-md-12">
					<div class="d-flex">
						<div class="pb-4 mt-3 pt-4 align-self-center mx-auto col-md-6">
							<button type="submit" class="btn btn-lg btn-primary btn-login w-100 mt-2">Update</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	@endsection
