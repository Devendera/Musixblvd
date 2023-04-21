@extends('webuserlayout.profile_design') @section('content')

<head>
	<meta name="csrf-token" content="{{ csrf_token() }}"> </head>
<div class="container mt-3 mb-5">
	<form method="post" id="project_mgt_form" enctype="multipart/form-data" action="{{url('/createproject')}}">{{ csrf_field() }}
		<div class="row">
			<section class="left-side mt-5 col-md-4 pd-right">
				<div class="bg-white left-sidebar rounded-xl shadow-sm pb-2">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="bordr-botm">
									<h5 class="pb-2 mt-3 profile-manager">{{ __('msg.projectinformation')}}</h5> </div>
							</div>
						</div>
						<div class="mt-4">
							<div class="card border-0 rounded-md">
								<div class="card-body project-upload-img">
									<div class="row align-items-center h-100">
										<div class="col-md-12">
											<div class="mx-auto">
												<div class="justify-content-center">
													<div class="d-flex justify-content-center ">
														<div>
															<div class="text-center"><img id="project-img" src="assets/images/image-upload.svg"></div>
															<div class="font-14-weight500 createprojectupload mt-2">
																<label class="file-upload" for="fileUpload">{{ __('msg.clickhere')}}  </label> <span class="font-12-res"> {{ __('msg.touploadimage')}}</span>
																<input class="custom-file-input" accept="jpg,jpeg,png" name="projectImage" type="file" id="fileUpload"> </div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="mt-3">
							<div class="row form-home home form-login form-register">
								<div class="form-group col-md-12">
									<label for="email"> {{ __('msg.projecttitle')}}</label>
									<input type="text" class="form-control" id="projectTitle" name="projectTitle" placeholder="{{ __('msg.typ')}}"> </div>
								<div class="form-group col-md-12">
									<label for="email">{{ __('msg.releasedate')}} </label>
									<input type="text" id="datepicker" class="form-control" name="releaseDate" placeholder="dd/mm/yyyy" readonly>
									<!-- <input type="text" class="form-control" id="releaseDate" name="releaseDate" placeholder="Month, Day, Year"> -->
								</div>
								<div class="form-group col-md-12">
									<label for="type">{{ __('msg.privacy')}}</label>
									<select name="privacy" id="privacy" class="form-control">
										<option>Select Type</option>
										<option value="private">{{ __('msg.private')}}</option>
										<option value="public">{{ __('msg.public')}}</option>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="bg-white left-sidebar rounded-xl shadow-sm mt-2 pb-3">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="bordr-botm">
									<h5 class="pb-1 mt-4 profile-manager">{{ __('msg.uploadaudio')}} <span class="max-size float-right mt-2">{{ __('msg.maxsize600kb')}}</span></h5> </div>
							</div>
						</div>
					</div>
					<div class="mt-4">
						<div class="col-md-12 card border-0 rounded-md">
							<div class="card-body project-upload-audio">
								<div class="row align-items-center h-100">
									<div class="col-md-12">
										<div class="mx-auto">
											<div class="justify-content-center">
												<div class="d-flex justify-content-start">
													<div class="col-md-12 upload-audio">
														<div class="create-click create-pro"> <span class="click-to-upload-audio font-14-weight500 mt-2 font-11-res">
                                                   <span><img
                                                      src="assets/images/sound-upload.svg"></span>
															<label class="file-upload main-text-color ml-2" for="fileUpload1">{{ __('msg.clickhere')}} </label>  <span class="ml-1 margin-top2">{{ __('msg.uploadaudio')}}</span>
															<input type="file" class="custom-file-input" accept="mp3,mp4,ogg,mpeg,wav" name="projectAudio" id="fileUpload1" > </div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="mt-4 pt-1 pb-2">
						<div class="col-md-12 card border-0 rounded-md">
							<div class="d-flex justify-content-between"> <span class="audio-file" id="filename"> {{ __('msg.audiofilenamehere')}}</span><span><img id="btn-example-file-reset" src="assets/images/close.svg"></span> </div>
						</div>
					</div>
				</div>
			</section>
			<section class="right-side mt-5 mt-r15 col-md-8 pl-2 pd-left">
				<div class="bg-white rounded-xl shadow-sm pt-3 pb-2 form-home form-login form-register">
					<div class="col-md-12">
						<h5 class="profile-manager bordr-botm pb-3">{{ __('msg.contributors')}}</h5> </div>
					<div class="col-md-12 mt-3">
						<div class="row pt-2 mb-2">
							<div class="col-md-12">
								<div class="input-group create-pro-search">
									<input type="text" id="employee_search" name="Search" class="form-control search-content rounded-xl " placeholder="{{ __('msg.search')}}"> <span class="bg-white has-search">
										<span class="search form-control-feedback">
											<img src="assets/images/search.png" alt="Search">
										</span> </span>
								</div>
							</div>
						</div>
					</div>
					<!---contributors here --->
					<div id="contributor-list" class="mt-4"> </div>
				</div>
				<div class="bg-white left-sidebar shadow-sm rounded-xl mt-2 pb-3">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="bordr-botm">
									<h5 class="pb-1 mt-3 profile-manager">{{ __('msg.links')}} <span class="float-right mt-1"><a class="profil-pro-combine" href="#">+ Add Links</a></span></h5> </div>
							</div>
						</div>
						<div id="platforms" class="row">
							<div id="plaform-link-container" class="col-md-12 mt-4 platform-container">
								<div class="row">
									<div class="form-group col-lg-3  col-md-12 pd-right pr-r15 form-home form-login form-register">
										<div class="mm-dropdown home drop-down">
											<select name="projectData[0][platform]" id="Platform" class="form-control">
												<option>Select Platform</option> @foreach($platforms as $platform)
												<option value="{{$platform->id}}">{{$platform->title}}</option> @endforeach </select>
											<input type="hidden" class="option" name="namesubmit" value="" /> </div>
										<!-- End This -->
									</div>
									<div class="form-group col-lg-8  col-md-11 pd-right pr-r15 form-home form-login form-register width-r90">
										<div class="input-group">
											<input type="text" name="projectData[0][url]" id="url" class="form-control rounded-xl" placeholder="Enter URL"> </div>
									</div>
									<div class="form-group col-md-1 d-flex justify-content-center align-items-center width-r10">
										<a href="#"><img src="assets/images/delete.svg"></a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<div class="col-md-12">
				<div class="d-flex">
					<div class="pb-2 mt-4 align-self-center mx-auto col-md-4">
						<button type="submit" class="btn btn-lg btn-primary btn-login w-100 mt-2">{{ __('msg.save')}}</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
<script src="http://code.jquery.com/jquery-3.1.1.js"></script>
<script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/project_mgt_validation.js') }}"></script>
<script src="https://unpkg.com/jquery@2.2.4/dist/jquery.js"></script>
<link href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script type="text/javascript">
function filePreview(input) {
	if(input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			document.getElementById('project-img').src = e.target.result;
		};
		reader.readAsDataURL(input.files[0]);
	}
}
$("#fileUpload").change(function() {
	filePreview(this);
});
</script>
<script type="text/javascript">
var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
$("#employee_search").autocomplete({
	source: function(request, response) {
		// Fetch data
		$.ajax({
			url: "{{route('autocomplete')}}",
			type: 'post',
			dataType: "json",
			data: {
				search: request.term,
				_token: '{{csrf_token()}}'
			},
			success: function(data) {
				var array = $.map(data, function(row) {
					return {
						value: row.id,
						label: row.name,
						name: row.name,
						image: row.img,
						type:row.type,
					}
				});
				response(array);
			}
		});
	},
	select: function(event, ui) {
		// Set selection
		
		if(ui.item.type == "Studio") {
			var image = ui.item.image != undefined ? '/musix-blvd-prod/public/studio/' + ui.item.image.replace(/^.*[\\\/]/, '') : '/assets/images/project-small.png';
		}else{
		var image = ui.item.image != undefined ? '/musix-blvd-prod/public/img/users/' + ui.item.image.replace(/^.*[\\\/]/, '') : '/assets/images/project-small.png';
		}
		$('#contributor-list').append(getContributorContent(ui.item.name, image, ui.item.value));
		return false;
	}
});

function getContributorContent(name, img, id) {
	return $(`<div class="col-md-12 mt-1" id="contributor-container_${id}">
						<div class="row">
							<div class="form-group col-md-12 col-lg-8 pd-right pr-r15">
								<div class="input-group project-input">
									<input type="hidden" value="${id}" name="contributors[]" id="name_${id}" />
									<input type="text" value="${name}" name="contributorsIds[]" class="form-control search-content rounded-xl custom-input" readonly>
									<span class="bg-white has-search">
										<span class="form-control-feedback pro-img">
											<img class="rounded-xl" src="${img}" width="35" height="35" alt="">
										</span>
									</span>
								</div>
							</div>
							<div class="form-group col-md-11 col-lg-3 pd-right home pro-select width-r90">
								<select class="form-control" name="roles[]">
									@foreach($roles as $role)
										<option value="{{$role->name}}">{{$role->name}}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-md-1 d-flex justify-content-center align-items-center width-r10" onclick="removeContainer(${id})">
								<a href="#"><img src="assets/images/delete.svg"></a>
							</div>
						</div>
					</div>`);
}
$(".profil-pro-combine").click(function() {
	var elemCount = document.getElementsByClassName('platform-container').length;
	var cloneElem = getLinkContainer(elemCount);
	$('#platforms').append(cloneElem);
});
$("#datepicker").datepicker({
	dateFormat: 'dd/mm/yy'
});

function removeContainer(id) {
	$('#contributor-container_' + id).remove();
}

function getLinkContainer(id) {
	return $(`<div id="plaform-link-container${id}" class="col-md-12 mt-1 platform-container">
								<div class="row">
									<div class="form-group col-lg-3  col-md-12 pd-right pr-r15 form-home form-login form-register">
										<div class="mm-dropdown home drop-down">
												<select name="projectData[${id}][platform]" id="Platform" class="form-control">
													<option>Select Platform</option>
													@foreach($platforms as $platform)
													<option value="{{$platform->id}}">{{$platform->title}}</option>
													@endforeach
												</select>
											<input type="hidden" class="option" name="namesubmit" value="" />
										</div>
									</div>
									<div class="form-group col-lg-8  col-md-11 pd-right pr-r15 form-home form-login form-register width-r90">
										<div class="input-group">
											<input type="text" name="projectData[${id}][url]" id="url" class="form-control rounded-xl" placeholder="Enter URL">
										</div>
									</div>
									<div class="form-group col-md-1 d-flex justify-content-center align-items-center width-r10" onclick="removeLinkContainer(${id})">
										<a href="#"><img src="assets/images/delete.svg"></a>
									</div>
								</div>
							</div>`);
}

function removeLinkContainer(id) {
	$('#plaform-link-container' + id).remove();
}
</script>
<script>
	var input = document.getElementById('fileUpload1');
	var infoArea = document.getElementById('filename');
	input.addEventListener('change', showFileName);
	function showFileName(event) {
		// the change event gives us the input it occurred in 
		var input = event.srcElement;
		// the input has an array of files in the `files` property, each one has a name that you can use. We're just using the name here.
		var fileName = input.files[0].name;
		// use fileName however fits your app best, i.e. add it into a div
		infoArea.textContent = 'File name: ' + fileName;
	}
</script>
 @endsection