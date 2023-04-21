@extends('webuserlayout.profile_design')

@section('content')
@php
	$nameArr = explode(" ", $user['name']);
	$fName = $nameArr[0];
	$lName = $nameArr[1];
	$statusArr = array('1' => 'Independent', '2' => 'Under Contact')
@endphp
	<div class="container mt-3 mb-5">
		<div class="row">
			<section class="left-side mt-5 col-md-4 pd-right">
				<div class="bg-white left-sidebar shadow-sm rounded-xl">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="d-flex justify-content-start">
									<div class="profile-img d-inline-flex">
										<?php if (!empty($user->img)) {?>
											<img src="{{$user->img }}" alt="Profile Picture">
										<?php } else {?>
											<img src="{{url('assets/images/marc.jpg') }}" alt="Profile Picture">
										<?php }?>
										<div class="online">
											<h4 class="profile-name mt-4">{{$user->name !='' ? $user->name : 'N/A'}}</h4>
											<div class="d-flex mt-1">
												<span class="dot green dot--full"></span> <span class="status">Online</span>
											</div>
											<div>
												<a class="btn btn-sm btn-primary btn-edit mt-3" href="{{URL::to('/edit-creative')}}">Edit Profile</a>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="total-projects">
									<span><img src="assets/images/projects.svg"></span> <span class="projects">Projects</span><span class="float-right num">{{$numberOfProjects}}</span>
								</div>

								<div class="total-connections">
									<span><img src="assets/images/connections.svg"></span> <span class="projects">Connections</span><span class="float-right num">{{$numberOfConnections}}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="bg-white left-sidebar shadow-sm rounded-xl mt-2 pb-3">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="bordr-botm">
									<h5 class="pb-1 mt-3 profile-manager">Managers</h5>
								</div>
							</div>
						</div>
						@foreach($reciever as $recieve)
						<div class="row">
							<div class="col-md-12">
								<div class="d-flex justify-content-start mt-3">
									<div class="profile-man-img d-inline-flex">
										<img src="{{url('public/img/users/')}}/{{$recieve->img }}" alt="Profile Picture">
										<div class="ml-3 profile-man-left">
										<a href="{{ url('/manager-view/'.$recieve->id)}}"> <h4 class="profil-manager-connect">{{$recieve->name !='' ? $recieve->name : 'N\A'}}</h4></a>
											<h6 class="profile-email">{{$recieve->email}}</h6>
											<div class="d-flex mt-2">
												<span class="dot-manager green dot--full"></span> <span class="status-manager">Online</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						@endforeach
						@foreach($sender as $send)
						<div class="row">
							<div class="col-md-12">
								<div class="d-flex justify-content-start mt-3">
									<div class="profile-man-img d-inline-flex">
											<img src="{{url('public/img/users/')}}/{{ $send->img }}" alt="Profile Picture">
										<div class="ml-3 profile-man-left">
										<a href="{{ url('/manager-view/'.$send->id)}}"> <h4 class="profil-manager-connect">{{$send->name !='' ? $send->name : 'N\A'}}</h4></a>
											<h6 class="profile-email">{{$send->email}}</h6>
											<div class="d-flex mt-2">
												<span class="dot-manager green dot--full"></span> <span class="status-manager">Online</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
				<div class="bg-white left-sidebar shadow-sm rounded-xl mt-2 pb-3">
					<form method="post" action= "{{ url('/combineProject') }}"  enctype="multipart/form-data" id="combinefrm">{{ csrf_field() }}
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="bordr-botm">
									<h5 class="pb-1 mt-3 profile-manager">Projects <span class="float-right mt-1"><button class="profil-pro-combine" type="submit" href="#">Combine Projects</button></span></h5>
								</div>
							</div>
						</div>
						@foreach($projectData as $project)
						<div class="row">
							<div class="col-md-12">
								<div class="d-flex justify-content-between mt-3">
									<div class="profile-man-img d-inline-flex">
                           <a class="" href="{{ url('projectInfo/'.$project->project_id)}}">
										<img src="{{ url('public/img/users/'.$project->img) }}" alt="Profile Picture">
										<div class="ml-3">
											<h6 class="profil-project-name">{{ $project->title}}</h6>
											<h6 class="profile-project-public">Public</h6>
										</div>
                           </a>
									</div>
									<div class="float-right radios">
											<div class="form-check rdio rdio-primary mt-3">
												<input class="form-check-input" type="checkbox" name="combine[]" id="{{ $project->project_id}}" value="{{ $project->project_id}}">
												<label for="{{ $project->project_id}}"></label>
											</div>
									</div>
								</div>
							</div>
						</div>
						@endforeach
					</div>
					</form>
				</div>
			</section>
			<section class="right-side mt-5 col-md-8 pl-2 pd-left mt-r15">
            <div class="bg-white rounded-xl pt-4 pb-4 shadow-sm form-login form-register ">
					<div class="col-md-12">
						@if($errors->any())
					<div class="alert alert-danger alert-desktop">

		              <strong>{{ implode('', $errors->all(':message')) }}</strong>
		            </div>
					@endif
					 <div class="msg">
	  @if(Session::has('flash_message_error'))
                   <div class="alert alert-danger alert-desktop">
				   <button type="button" class="close" data-dismiss="alert">×</button>
                   <strong>{{ Session::get('flash_message_error') }}</strong>
                   </div>
               @endif
               @if(Session::has('flash_message_success'))
                  <div class="alert alert-success alert-desktop">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                      <strong>{{ Session::get('flash_message_success') }}</strong>
                  </div>
               @endif
      </div>
						<h5 class="profile-manager bordr-botm pb-3">Basic Details</h5>
					</div>
					<div class="col-md-12 mt-4">
						<div class="row">
							<div class="form-group col-md-4 pd-right">
								<label for="firstName">First Name </label>
								<input type="text" class="form-control" id="fName" name="firstName" placeholder="Sample" disabled value="{{$fName}}">
							</div>
							<div class="form-group col-md-4 pd-right">
								<label for="lastName">Last name </label>
								<input type="text" class="form-control" id="lName" name="lastName" placeholder="Name" disabled value="{{$lName}}">
							</div>
							<div class="form-group col-md-4">
								<label for="email">Email </label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Sample@gmail.com" disabled value="{{$user->email}}">
							</div>
						</div>
					</div>
					<div class="col-md-12">
                  <div class="row">
                     <div class="form-group col-md-4 pd-right">
                        <label for="website">Website </label>
                        <input type="text" class="form-control" id="website" name="website" placeholder="www.sample.com" disabled value="{{$user->creative->website}}">
                     </div>
                     <div class="form-group col-md-4 pd-right">
                        <label for="state">Country </label>
                        <input type="text" class="form-control" id="country" name="country" placeholder="Sample text" disabled value="{{$country->name}}">
                     </div>
                     <div class="form-group col-md-4">
                        <label for="state">State </label>
                        <input type="text" class="form-control" id="state" name="state" placeholder="Sample text" disabled value="{{$user->state}}">
                     </div>
                  </div>
					</div>
					<div class="col-md-12">
                  <div class="row">
                  <div class="form-group col-md-4 pd-right">
                        <label for="state">City </label>
                        <input type="text" class="form-control" id="city" name="city" placeholder="Sample text" disabled value="{{$user->city}}">
                     </div>
                     <div class="form-group col-md-4 pd-right">
                        <label for="stage">Stage </label>
                        <input type="text" class="form-control" id="stage" name="stage" placeholder="stage" disabled value="{{$user->creative->stage}}">
                     </div>
                     <div class="form-group col-md-4">
                        <label for="gender">Gender </label>
                        <input type="text" class="form-control" id="gender" name="gender" placeholder="Male" disabled value="{{$genders->title}}">
                     </div>
                  </div>
					</div>
               <div class="col-md-12">
                  <div class="row">
                     <div class="form-group col-md-4  pd-right">
                        <label for="type">Type </label>
                        <input type="text" class="form-control" id="type" name="type" placeholder="Solo" disabled value="{{$types->title}}">
                     </div>
                     <div class="form-group col-md-4 pd-right">
                        <label for="craft">Craft </label>
                        <input type="text" class="form-control" id="craft" name="craft" placeholder="Engineer" disabled value="{{$crafts->title}}">
                     </div>
                     <div class="form-group col-md-4">
                        <label for="genre">Genre </label>
                        <input type="text" class="form-control" id="genre" name="genre" placeholder="R&B/Soul" disabled value="{{$genres->title}}">
                     </div>
                  </div>
               </div>
					<div class="col-md-12">
						<div class="row">
							<div class="form-group col-md-4 pd-right">
								<label for="status">Status </label>
								<input type="text" class="form-control" id="status" name="status" placeholder="Independent" disabled value="{{$statusArr[$user->creative->status]}}">
							</div>
							<div class="form-group col-lg-4 col-md-8 pd-right pr-r15">
							<label for="pro">Performance Rights Organization</label>
								<select class="form-control" id="pro" name="pro">
									<option value="">Select PRO</option>
									<option value="N/A" {{$user->creative->pro == 'N/A' ? 'selected' : '' }}>N/A</option>
									<option value="BMI" {{$user->creative->pro == 'BMI' ? 'selected' : '' }}>BMI</option>
									<option value="SESAC" {{$user->creative->pro == 'SESAC' ? 'selected' : '' }}>SESAC</option>
									<option value="ASCAP" {{$user->creative->pro == 'ASCAP' ? 'selected' : '' }}>ASCAP</option>
									<option value="SAG-AFTRA" {{$user->creative->pro == 'SAG-AFTRA' ? 'selected' : '' }}>SAG-AFTRA</option>
								</select>
						    </div>
						</div>
					</div>
					            <?php
if (!empty($user['platforms']) && count($user['platforms']) > 0) {
    ?>
													<div class="col-md-12">
														<div class="streaming-platforms">
															<h6 class="profile-stream">Streaming</h6>
															<ul class="d-flex mt-3 user-stre">
															<?php
foreach ($user['platforms'] as $key => $value) {
        if ($value->title === 'Spotify') {
            ?>
								<li><a href="{{ @$value->url  }}" target="_blank" title="Spotify"><img src="{{ url('assets/images/lines.png') }}"></a></li>
								<?php }if ($value->title === 'Google Play') {?>
									<li><a href="{{ @$value->url }}" target="_blank" title="Google Play"><img src="{{ url('assets/images/play-store.png') }}"></a></li>
								<?php }if ($value->title === 'Youtube') {?>
									<li><a href="{{ @$value->url  }}" target="_blank" title="Youtube"><img src="{{ url('assets/images/youtube.png') }}"></a></li>
								<?php }if ($value->title === 'Tidal') {?>
									<li><a href="{{ @$value->url  }}" target="_blank" title="Tidal"><img src="{{ url('assets/images/black.png') }}"></a></li>
								<?php }if ($value->title === 'Amazon') {?>
									<li><a href="{{ @$value->url  }}" target="_blank" title="Amazon"><img src="{{ url('assets/images/amazon.png') }}"></a></li>
								<?php }if ($value->title === 'Pandora') {?>
									<li><a href="{{ @$value->url  }}" target="_blank" title="Pandora"><img src="{{ url('assets/images/p.png') }}"></a></li>
								<?php }if ($value->title === 'Deezer') {?>
									<li><a href="{{ @$value->url  }}" target="_blank" title="Deezer"><img src="{{ url('assets/images/build.png') }}"></a></li>
								<?php }if ($value->title === 'Apple Music') {?>
									<li><a href="{{ @$value->url  }}" target="_blank" title="Apple Music"><img src="{{ url('assets/images/music.png') }}"></a></li>
								<?php }if ($value->title === 'SoundCloud') {?>
									<li><a href="{{ @$value->url  }}" target="_blank" title="Sound Cloud"><img src="{{ url('assets/images/cloud.png') }}"></a></li>
								<?php }}?>
							</ul>
						</div>
					</div>
					<?php }?>
					<div class="col-md-12">
						<div class="row">
							<div class="form-group col-md-12">
								<label for="influencers">Influencers </label>
								<textarea class="form-control" name="textarea" rows="5" cols="40" placeholder="Sample Message" disabled>{{$user->creative->influencers}}</textarea>
							</div>
						</div>
					</div>
					<?php if (!empty($user->creative->social_media_links)) {?>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<h6 class="profile-stream">Social Media Links</h6>
							</div>
						</div>
						<?php
if (!empty($user->creative->social_media_links)) {
    $links = explode(",", $user->creative->social_media_links);
    $index = 1;
    foreach ($links as $key => $value) {
        if (!empty($value)) {
            ?>
							<div class="row <?php echo ($index !== 1) ? 'mt-2' : ''; ?>">
								<div class="col-md-10 col-sm-9 pd-right">
									<div class="s-link d-flex justify-content-start">
										<span class="class copy-link ml-2">{{ @$value }}</span>
									</div>
								</div>
								<div class="col-md-2 col-sm-3 mt-2 pd-left">
									<span class="link-bg">
										<a href="#" id="{{ @$value }}"class="copy-link-btn copy" >Copy link</a>
									</span>
								</div>
							</div>
						<?php $index++;}}}?>
					</div>
				</div>
				<?php }?>
			</section>
		</div>
	</div>
	@endsection
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
	 $(document).on('click', '.copy', function () {
		 var id = this.id;
		copyToClipboard(id);
   });
   function copyToClipboard(text) {
    var selected = false;
    var el = document.createElement('textarea');
    el.value = text;
    el.setAttribute('readonly', '');
    el.style.position = 'absolute';
    el.style.left = '-9999px';
    document.body.appendChild(el);
    if (document.getSelection().rangeCount > 0) {
        selected = document.getSelection().getRangeAt(0)
    }
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    if (selected) {
        document.getSelection().removeAllRanges();
        document.getSelection().addRange(selected);
    }
};
	</script>