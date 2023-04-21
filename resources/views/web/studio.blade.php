
@extends('webuserlayout.user_design')

@section('content')
<style>
input[type="file"] {
  display: block;
}



</style>
<div class="d-flex text-center">
		<div class="col-md-6 col-sm-9 mx-auto mt-5 pt-2">
			<h1 class="manager-reg-heading mt-2">Welcome as Studio!</h1>
		</div>
	</div>
	<div class="col-sm-9 mx-auto">
		<p class="man-reg-sub-heading text-center"> Please fill your details below</p>
	</div>

	<div class="container pt-4 mb-5">
		<form method="post" action= "{{ url('/registerStudio') }}"  onSubmit="return validateForms()" enctype="multipart/form-data" id="studioUploadForm">{{ csrf_field() }}
				<div class="bg-white rounded-xl pt-4 pb-5 shadow-sm form-home form-login form-register">
				<div class="col-md-12 mt-4">
					<div class="row">
						<div class="form-group col-md-4 home pd-right">
							<label for="Studio Name">Studio Name</label>
							<input type="text" class="form-control" id="studioname" name ="studioname" placeholder="Studio Name">
						</div>
						<div class="form-group col-md-4 home pd-right">
							<label for="Address">Address</label>
							<input type="hidden" class="form-control" id="longitude" name="longitude">
							<input type="hidden" class="form-control" id="latitude" name="latitude">
							<input type="text" class="form-control" id="geoaddress" name="address" value="{{@$userData->address}}" placeholder="Address">
						</div>
						<div class="form-group col-md-4 home">
							<label for="Booking Email">Booking Email</label>
							<input type="text" class="form-control" id="bookingemail" name="bookingemail" placeholder="Booking Email">
						</div>
						<div class="form-group col-md-4 home form-register  pd-right">
							<label for="Hourly Rate">Hourly Rate</label>
							<input type="text" class="form-control" id="hourlyrate" name="hourlyrate" placeholder="Hourly Rate">
						</div>
						<div class="form-group col-md-4 home pd-right">
						<label for="Performance Rights Organization">Performance Rights Organization</label>
							<select class="form-control" id="pro" name="pro">
								<option value="">Select PRO</option>
								<option value="N/A">N/A</option>
								<option value="BMI">BMI</option>
								<option value="SESAC">SESAC</option>
								<option value="ASCAP">ASCAP</option>
								<option value="SAG-AFTRA">SAG-AFTRA</option>
							</select>
						</div>
						<div class="form-group col-md-4 home">
							<label for="option">Select One Option</label>
							<fieldset class="form-group col-md-12 radios independent welcome-stud">
								<div class="row">
									<div class="col-lg-6 col-md-12 col-sm-4 mt-2 pl-0">
										<div class="form-check rdio">
											<input class="form-check-input" type="radio" name="gridRadios" id="gridRadios5" value="option1">
											<div class="radiobuttons">
												<div class="rdio rdio-primary radio-inline">
													<input name="radio" value="1" id="radio1" type="radio">
													<label class="form-check-label" for="radio1">Residential</label>
												</div>

											</div>
										</div>
									</div>
									<div class="col-lg-6 col-md-12 col-sm-4 mt-2 pl-0">
										<div class="form-check rdio">
											<input class="form-check-input" type="radio" name="gridRadios" id="gridRadios6" value="option2" checked="checked">
											<div class="radiobuttons">
												<div class="rdio rdio-primary radio-inline">
													<input name="radio" value="2" id="radio2" type="radio">
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

				<div class="mt-3">
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
																<span> <output id='result' />
                                                                <div style="color:red;" id="limit_error"></div></span>
																<label for='files' class="file-upload ml-2">Click Here</label><span class="ml-1 upload-text margin-top2"> to Upload Images</span>
                                                               <input id='files' type='file' name="files[]" multiple/>
															</span>
														<span class="float-right mt-2 pt-1 img-w-100">+Add Only 3 Images</span>
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
				</div>
			<div class="col-md-12">
			<div class="d-flex">
				<div class="pb-2 mt-4 pt-4 align-self-center mx-auto col-md-6">
					<button type="submit" class="btn btn-lg btn-primary btn-login w-100 mt-2">Register</button>
				</div>
			</div>
		</div>
		</form>
	</div>
	@endsection
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script>
		$(document).ready(function() {
  if (window.File && window.FileList && window.FileReader) {
    $("#files").on("change", function(e) {
      var files = e.target.files,
        filesLength = files.length;
		var output = document.getElementById("result");
		if(filesLength>1){
			if(filesLength<4){
				$("#files-error").css("display", "none");	
      for (var i = 0; i < filesLength; i++) {
        var f = files[i]
        var fileReader = new FileReader();
        fileReader.onload = (function(e) {
          var file = e.target;
		  var div = document.createElement("div");
          div.innerHTML = "<span class=\"pip\">" +
            "<img class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
            "<span class=\"remove\">×</span>" +
            "</span>";
          output.insertBefore(div, null);
          $(".remove").click(function(){
            $(this).parent(".pip").remove();
          });
        });
        fileReader.readAsDataURL(f);
      }}else{
		$("#limit_error").addClass("").text("Maximum  3 images allowed");
	  }}
	  else{
		$("#limit_error").addClass("").text("Please Select 3 images ");
	  }
      console.log(files);
    });
  } else {
	$("#limit_error").addClass("").text("Something went wrong please try again later");
  }
});</script>
