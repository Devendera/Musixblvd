@extends('webuserlayout.profile_design')
@section('content')
<div class="container mt-3 mb-5">
		<div class="row">
			<div class="col-md-12">
				<div class="bg-white rounded-xl pt-4 mt-5 pb-4 shadow-sm form-home form-login form-register">
				@if($message)
		            <div class="alert alert-success alert-block alert-desktop">
		                <button type="button" class="close" data-dismiss="alert">×</button>
		                <strong style = "color:green">{{$message}}</strong>
		            </div>
		         @endif
					<div class="col-md-12">
						<div class="bordr-botm">
							<h5 class="pb-1 profile-manager">{{ __('msg.projectinformation')}} <span class="float-right mt-1"><a class="profil-pro-combine" href="mailto:support@musixblvd.com?subject=Project Issue">{{ __('msg.help')}}</a></span></h5>
						</div>
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="mt-4">
									<div class="project-img pro-wrap">
									<div class="project-info">
										<img src="{{ url('public/img/users/'.$detail->img) }}" alt="Profile Picture">
                                     </div>
										<div class="online">
											<h4 class="profile-name">{{$detail->title}}</h4>
											<div class="card-text m-1">
												<div class="mt-2">
													<span class="project-release-date">
													<img src="{{ url('assets/images/calander.svg') }}">
													<span class="project-r-date ml-1">{{ __('msg.releasedate')}}: </span></span>  <span class="project-status"> {{$detail->release_date}}</span>
												</div>
												<div class="mt-1">
													<span class="project-release-date">
													<img src="{{ url('assets/images/privacy.svg') }}">
													<span class="project-r-date ml-1">{{ __('msg.privacy')}}: </span>
												    </span> 
													<span class="project-status"> {{$detail->Privacy}}</span>
												</div>
												<div class="mt-2">
													<span class="audio-play" onclick="togglePlay()">
													<img src="{{ url('assets/images/play-icon.svg') }}"  id="audio-play-icon">
													<audio controls id="audio_{{$detail->id}}" style="display:none;">
                                                   <source src="{{ url('public/audio/projects/'.$detail->audio) }}" type="audio/ogg">
                                                   </audio>
													<span class="project-r-date ml-1">{{$detail->audio}} </span></span>
												</div>
											</div>
											<div>
												<a class="btn btn-sm btn-primary btn-edit mt-3" href="#" data-toggle="modal" data-target="#myModalAgent1">{{ __('msg.claimcredit')}}</a>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="bg-white rounded-xl pt-4 pb-4 mt-2 shadow-sm form-home form-login form-register">
					<div class="col-md-12">
						<h5 class="profile-manager bordr-botm pb-3">{{ __('msg.contributors')}}</h5>
					</div>
					<div class="col-md-12">
						<div class="row">
						@foreach($contributors as $contributor)

							<div class="col-md-3 profile-man-img mt-3">
							<?php
							  $img = 'assets/images/marc.jpg';
							  if ($contributor->type == "3") {
								if (!empty($contributor->img)) {
									$arr = explode("/",$contributor->img);
									$img = end($arr);
								?>
                                <img class="profile-pic" src="{{ url('public/studio/'.$img) }}" alt="Profile Picture">
                                 <?php } else {?>
                                    <img class="profile-pic" src="{{ url($img) }}" alt="Profile Picture">
                                 <?php }} else {?>
                                     <img class="profile-pic" src="{{ url('public/img/users/'.$contributor->img) }}" alt="Profile Picture">
                                 <?php }?>
								<div class="ml-3">
								<?php if($contributor->type == "1"){ ?>
									<a href="{{ url('/creative-view/'.$contributor->id)}}"><h6 class="profil-project-name">{{ $contributor->name }}</h6></a>
								<?php } else if($contributor->type == "2"){ ?>
									<a href="{{ url('/manager-view/'.$contributor->id)}}"><h6 class="profil-project-name">{{ $contributor->name }}</h6></a>
								<?php }else{ ?>
									<a href="{{ url('/studio-view/'.$contributor->id)}}"><h6 class="profil-project-name">{{ $contributor->name }}</h6></a>
								<?php } ?>
								<h6 class="profile-project-public">Artist</h6>
								</div>
							</div>
						@endforeach
						</div>
					</div>
				</div>
				<div class="bg-white rounded-xl pb-4 shadow-sm form-home form-login form-register">
					<div class="col-md-12">
						<h5 class="profile-manager bordr-botm pb-3 pt-3 mt-2">{{ __('msg.links')}}</h5>
					</div>
					<div class="col-md-12 mt-4">
					@if($detail->apple_music !="")
						<div class="row  mt-2">
							<div class="col-md-12">
								<div class="project-link d-flex justify-content-start">
									<div class="social">
										<img src="{{ url('assets/images/music.png') }}">
									</div>
									<span class="copy-link ml-2"><a data-url="{{$detail->apple_music}}" id="" href="{{$detail->apple_music}}" target="#">{{$detail->apple_music}}</a></span>
									</div>
									<span class="project-link-bg">
										<a href="javascript:{}" onclick="copyLink({{$detail->apple_music}})" class="copy-link-btn">Copy link</a>
									</span>
								</div>
						</div>
					@endif
					@if($detail->spotify !="")
						<div class="row  mt-2">
							<div class="col-md-12">
								<div class="project-link d-flex justify-content-start">
									<div class="social">
										<img src="{{ url('assets/images/lines.png') }}">
									</div>
									<span class="copy-link ml-2"><a data-url="{{$detail->spotify}}" id="" href="{{$detail->spotify}}" target="#">{{$detail->spotify}}</a></span>
									</div>
									<span class="project-link-bg">
										<a href="javascript:{}" onclick="copyLink({{$detail->spotify}})" class="copy-link-btn">Copy link</a>
									</span>
								</div>
						</div>
					@endif
					@if($detail->pandora !="")
						<div class="row  mt-2">
							<div class="col-md-12">
								<div class="project-link d-flex justify-content-start">
									<div class="social">
										<img src="{{ url('assets/images/p.png') }}">
									</div>
									<span class="copy-link ml-2"><a data-url="{{$detail->pandora}}" id="" href="{{$detail->pandora}}" target="#">{{$detail->pandora}}</a></span>
									</div>
									<span class="project-link-bg">
										<a href="javascript:{}" onclick="copyLink({{$detail->pandora}})" class="copy-link-btn">Copy link</a>
									</span>
								</div>
						</div>
					@endif
					@if($detail->google !="")
						<div class="row  mt-2">
							<div class="col-md-12">
								<div class="project-link d-flex justify-content-start">
									<div class="social">
										<img src="{{ url('assets/images/play-store.png') }}">
									</div>
									<span class="copy-link ml-2"><a data-url="{{$detail->google}}" id="" href="{{$detail->google}}" target="#">{{$detail->google}}</a></span>
									</div>
									<span class="project-link-bg">
										<a href="javascript:{}" onclick="copyLink({{$detail->google}})" class="copy-link-btn">Copy link</a>
									</span>
								</div>
						</div>
					@endif
					@if($detail->amazon !="")
						<div class="row  mt-2">
							<div class="col-md-12">
								<div class="project-link d-flex justify-content-start">
									<div class="social">
										<img src="{{ url('assets/images/amazon.png') }}">
									</div>
									<span class="copy-link ml-2"><a data-url="{{$detail->amazon}}" id="" href="{{$detail->amazon}}" target="#">{{$detail->amazon}}</a></span>
									</div>
									<span class="project-link-bg">
										<a href="javascript:{}" onclick="copyLink({{$detail->amazon}})" class="copy-link-btn">Copy link</a>
									</span>
								</div>
						</div>
					@endif
					@if($detail->deezer !="")
						<div class="row  mt-2">
							<div class="col-md-12">
								<div class="project-link d-flex justify-content-start">
									<div class="social">
										<img src="{{ url('assets/images/build.png') }}">
									</div>
									<span class="copy-link ml-2"><a data-url="{{$detail->deezer}}" id="" href="{{$detail->deezer}}" target="#">{{$detail->deezer}}</a></span>
									</div>
									<span class="project-link-bg">
										<a href="javascript:{}" onclick="copyLink({{$detail->deezer}})" class="copy-link-btn">Copy link</a>
									</span>
								</div>
						</div>
					@endif
					@if($detail->tidal !="")
						<div class="row  mt-2">
							<div class="col-md-12">
								<div class="project-link d-flex justify-content-start">
									<div class="social">
										<img src="{{ url('assets/images/black.png') }}">
									</div>
									<span class="copy-link ml-2"><a data-url="{{$detail->tidal}}" id="" href="{{$detail->tidal}}" target="#">{{$detail->tidal}}</a></span>
									</div>
									<span class="project-link-bg">
										<a href="javascript:{}" onclick="copyLink({{$detail->tidal}})" class="copy-link-btn">Copy link</a>
									</span>
								</div>
						</div>
					@endif
					@if($detail->youtube !="")
						<div class="row  mt-2">
							<div class="col-md-12">
								<div class="project-link d-flex justify-content-start">
									<div class="social">
										<img src="{{ url('assets/images/youtube.png') }}">
									</div>
									<span class="copy-link ml-2"><a data-url="{{$detail->youtube}}" id="" href="{{$detail->youtube}}" target="#">{{$detail->youtube}}</a></span>
									</div>
									<span class="project-link-bg">
										<a href="javascript:{}" onclick="copyLink({{$detail->youtube}})" class="copy-link-btn">Copy link</a>
									</span>
								</div>
						</div>
					@endif
					@if($detail->sound_cloud !="")
						<div class="row  mt-2">
							<div class="col-md-12">
								<div class="project-link d-flex justify-content-start">
									<div class="social">
										<img src="{{ url('assets/images/cloud.png') }}">
									</div>
									<span class="copy-link ml-2"><a data-url="{{$detail->sound_cloud}}" id="" href="{{$detail->sound_cloud}}" target="#">{{$detail->sound_cloud}}</a></span>
									</div>
									<span class="project-link-bg">
										<a href="javascript:{}" onclick="copyLink({{$detail->sound_cloud}})" class="copy-link-btn">Copy link</a>
									</span>
								</div>
						</div>
					@endif
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="myModalAgent1" class="modal fade" role="dialog">
  <div class="modal-dialog agent-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Contributors Role</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body agent-body">
	  <form  method="post" action="{{url('/claim-credit/')}}/{{$detail->id}}" enctype="multipart/form-data">{{ csrf_field() }}
  <div class="form-group home">
					<label for="Studio Name">Select Contributors Role</label>
						<select class="form-control" name="contributors">
						@foreach($roles as $role)
							<option value="{{$role->id}}" >{{$role->name}}</option>
						@endforeach
						</select>
					</div>
      </div>
      <div class="modal-footer">

      <button class="btn btn-sm btn-primary btn-edit mt-3" type="submit">Submit</button>
      </div>
</form>
    </div>

  </div>
</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script>
		var resultArray = <?php echo json_encode($requestConnections); ?>
         console.log(resultArray);
		$('#badge-count').html(resultArray.length);
			// Use resultArray values in javascript

			$.each(resultArray, function(index, value) {
				var message=resultArray[index].is_approved==0?'You got invitation request':'You Accepted Connection Request';
				var showBtn=resultArray[index].is_approved==1?'hidden':'';

				$('#notification-container').append($(`
				<div class="noti-profile">
												<div class="pd-left pd-right">
													<span class="msg-profile-pic">
													<img src="${resultArray[index].img}" onerror="this.src='assets/images/marc.jpg';" alt="User Picture">
													</span>
													<span class="noti-name-wrap mt-1">
														<p class="noti-profile-name">
															${resultArray[index].name}
														</p>
														<p class="notify">${message}</p>
														<p ${showBtn} ><a class="btn btn-sm btn-primary btn-edit" href="javascript:{}" onclick="document.getElementById('form_${resultArray[index].id}').submit();">Accept</a> <a class="ml-3 reject-req" href="javascript:{}" onclick="document.getElementById('reject_${resultArray[index].id}').submit();">Reject</a></p>
														<form method="post" id="form_${resultArray[index].id}" action="{{ route('acceptConnection') }}"> {{ csrf_field() }}
															<input type="hidden" name="id" value="${resultArray[index].id}" />
															<input type="hidden" name="project_id" value="{{$detail->id}}" />
														</form>
														<form method="post" id="reject_${resultArray[index].id}" action="{{ route('rejectConnection') }}"> {{ csrf_field() }}
															<input type="hidden" name="id" value="${resultArray[index].id}" />
															<input type="hidden" name="project_id" value="{{$detail->id}}" />
														</form>
												    </span>
												</div>
												<span class="noti-time pd-left pd-right">
													<p>10:00 AM</p>
													<ul class="nav edit-notification">
														<li class="nav-item dropdown">
															<a class="user nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
															aria-expanded="false" href="login.html">
																<div class="menu-edit"></div>
															</a>
															<div class="dropdown-menu" aria-labelledby="navbarDropdown">
																<a class="dropdown-item delete-noti ml-1" href="#"><img src="assets/images/delete-edit.svg" alt="delete"><span class="ml-1"> Delete this Notification</span> </a>
																<p class="dropdown-item"><a class="btn btn-sm btn-primary btn-edit pd-mark delete-noti ml-1" href="#"><img src="assets/images/tick.svg" alt="delete"><span class="ml-1"> Mark as Read</span></a> </p>
															</div>
														</li>
													</ul>
												</span>
											</div>`));

			})
		function copyLink(id){
			var value=$('#span_'+id).text();
			console.log(value);
			navigator.clipboard.writeText(value);
		}

	</script>
	<script>
	var myAudio = document.getElementById('audio_{{$detail->id}}');
	var isPlaying = false;
	function togglePlay() {
		isPlaying ? myAudio.pause() : myAudio.play();
		if($('.audio-play').hasClass( "is-play" )){
			$(".audio-play").removeClass("is-play");
			$("#audio-play-icon").attr("src", "<?php echo url('assets/images/play-icon.svg');  ?>");
		}else{
			$(".audio-play").addClass("is-play");
			$("#audio-play-icon").attr("src", "<?php echo url('assets/images/play-icon.png');?>");	
		}
	};

	myAudio.onplaying = function() {
		isPlaying = true;
	};

	myAudio.onpause = function() {
		isPlaying = false;
	};
	</script>
	@endsection
