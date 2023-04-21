
@extends('webuserlayout.profile_design')

@section('content')
	<div class="container pt-4 mb-5 mt-5">		
		<form method="post" action= "{{ url('/updateStudioProfile') }}" enctype="multipart/form-data" id="studioUploadUpdateForm">{{ csrf_field() }}
		<div class="bg-white rounded-xl pt-4 pb-1 shadow-sm form-home form-login form-register">
		<div class="col-md-12">
               <h5 class="profile-manager bordr-botm pb-3">Edit Profile</h5>
            </div>
				<div class="col-md-12 mt-4">
					<div class="row">
						<div class="form-group col-lg-4 col-md-6 home pd-right">
							<label for="Studio Name">Studio Name</label>
							<input type="text" class="form-control" id="studioname" name ="studioname" value="{{$userData->name}}" placeholder="Studio Name">
						</div>
						<div class="form-group col-lg-4 col-md-6 home pd-right">
							<label for="Address">Address</label>
							<input type="hidden" class="form-control" id="longitude" name="longitude">
							<input type="hidden" class="form-control" id="latitude" name="latitude">
							<input type="text" class="form-control" id="geoaddress" name="address" value="{{$userData->address}}" placeholder="Address">
						</div>
						<div class="form-group col-lg-4 col-md-6 home">
							<label for="Booking Email">Booking Email</label>
							<input type="text" class="form-control" id="bookingemail" name="bookingemail" value="{{$userData->booking_email}}" placeholder="Booking Email">
						</div>
						<div class="form-group col-lg-4 col-md-6 home form-register  pd-right">
							<label for="Hourly Rate">Hourly Rate</label>
							<input type="text" class="form-control" id="hourlyrate" name="hourlyrate" value="{{$userData->hourly_rate}}" placeholder="Hourly Rate">
						</div>
						<div class="form-group col-lg-4 col-md-6 home pd-right">
							<label for="Performance Rights Organization">Performance Rights Organization</label>
								<select class="form-control" id="pro" name="pro">
									<option value="">Select PRO</option>
									<option value="N/A" {{ $userData->pro == 'N/A' ? 'selected' : '' }}>N/A</option>
									<option value="BMI" {{ $userData->pro == 'BMI' ? 'selected' : '' }}>BMI</option>
									<option value="SESAC" {{ $userData->pro == 'SESAC' ? 'selected' : '' }}>SESAC</option>
									<option value="ASCAP" {{ $userData->pro == 'ASCAP' ? 'selected' : '' }}>ASCAP</option>
									<option value="SAG-AFTRA" {{ $userData->pro == 'SAG-AFTRA' ? 'selected' : '' }}>SAG-AFTRA</option>
								</select>
						</div>
						<div class="form-group col-lg-4 col-md-6 home">
							<label for="option">Select One Option</label>
							<fieldset class="form-group col-md-12 radios studio-edit">
								<div class="row">
									<div class="col-md-6 col-sm-3 mt-2 pd-left">
										<div class="form-check rdio">
											<input class="form-check-input" type="radio" name="gridRadios" id="gridRadios5" value="option1">
											<div class="radiobuttons">
												<div class="rdio rdio-primary radio-inline"> 
													<input name="radio" value="1" id="radio1" type="radio"  <?php if($userData->type=="1"){ echo "checked";}?>>
													<label class="form-check-label" for="radio1">Residential</label>
												</div>										
												
											</div>
										</div>
									</div>
									<div class="col-md-6 col-sm-3 mt-2 pd-left">
										<div class="form-check rdio">
											<input class="form-check-input" type="radio" name="gridRadios" id="gridRadios6" value="option2" checked="checked">
											<div class="radiobuttons">
												<div class="rdio rdio-primary radio-inline"> 
													<input name="radio" value="2" id="radio2" type="radio"  <?php if($userData->type=="2"){ echo "checked";}?>>
													<label  class="form-check-label" for="radio2">Commercial</label>
												</div>														
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</div>			
		</div>
		<div class="col-md-12">
			<div class="d-flex">
				<div class="pb-2 mt-4 pt-4 align-self-center mx-auto col-md-6">
					<button type="submit" class="btn btn-lg btn-primary btn-login w-100 mt-2">Update</button>
				</div>
			</div>
		</div>
		</form>
		
	</div>
	@endsection
