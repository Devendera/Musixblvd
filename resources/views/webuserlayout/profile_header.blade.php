<header class="bg-white border-bottom shadow-sm fixed-top">
<?php $connections = ""; ?>	
			<div class="container">
				<div class="row">
					<div class="col-12">
						<nav class="navbar navbar-expand-lg navbar-light my-2 my-md-0 d-flex">
							<a class="navbar-brand my-0 font-weight-normal" href="{{ url('/user-index') }}">
								<img src="{{asset('assets/images/logo.png')}}" width="80" height="80" alt="logo">
							</a>
							<ul class="ml-md-auto ml-sm-auto nav app-header-right-menu order-lg-2">
								<li class="nav-item dropdown" id="messageCount">
									@include('webuserlayout/messageCount')
								</li>
							</ul>
							<ul class="nav app-header-right-menu notifications order-lg-2">
								<li class="nav-item dropdown">
									<a class="login-link order-lg-2 user nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false" href="#">
									       @include('webuserlayout/notification')
									</a>
									<div class="dropdown-menu keep-open" aria-labelledby="navbarDropdown" id="notification"> 
										<div class="col-md-12" >
											<h3 class="noti-Heading">Notification</h3>
											@include('webuserlayout/connection_request')
											<div class="noti-date">
												<span class="date-time"> Yesterday</span>
											</div>
										</div>
									</div>
								</li>
							</ul>
							<ul class="nav app-header-right-menu my-profile order-lg-2">
								<li class="nav-item dropdown user-profile nav-link">
								<?php 
                                 $img = 'assets/images/marc.jpg';
                                 if(Auth::guard('web')->user()->type == "Studio"){
                                    if(!empty(Auth::guard('web')->user()->img)){
                                    $arr = explode("/",Auth::guard('web')->user()->img);
                                    $img = end($arr);
                                ?>
                                    <img class="profile-pic" src="{{ url('public/studio/'.$img) }}" width="40" height="40" alt="Profile Picture">
                                 <?php }else{ ?>
                                    <img class="profile-pic" src="{{ url($img) }}" alt="Profile Picture">
                                 <?php  } }else{ ?>
                                     <img class="profile-pic" src="{{Auth::guard('web')->user()->img!='' ? Auth::guard('web')->user()->img: $img }}" width="40" height="40 alt="Profile Picture">
                                 <?php } ?>
									
									<span class="user-name">{{ Auth::guard('web')->user()->name}}</span>
									<a class="dropdown-toggle"  href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false">
										<div class="fa fa-chevron-down rotate"></div>
									</a>
									<div class="dropdown-menu keep-open" aria-labelledby="navbarDropdown">
										<div class="col-md-12">
										<h3 class="noti-Heading"></h3>
											<div class="noti-profile col-md-12">
												<div class="pd-left pd-right">
													<span class="profile-pic">
													<?php 
													$img = 'assets/images/marc.jpg';
													if(Auth::guard('web')->user()->type == "Studio"){
														if(!empty(Auth::guard('web')->user()->img)){
														$arr = explode("/",Auth::guard('web')->user()->img);
														$img = end($arr);
													?>
														<img class="profile-pic" src="{{ url('public/studio/'.$img) }}" width="40" height="40  alt="Profile Picture">
													<?php }else{ ?>
														<img class="profile-pic" src="{{ url($img) }}" alt="Profile Picture">
													<?php  } }else{ ?>
														<img class="profile-pic" src="{{Auth::guard('web')->user()->img!='' ? Auth::guard('web')->user()->img: $img }}" alt="Profile Picture">
													<?php } ?>
																			
													</span>
													<span class="noti-name-wrap mt-1">
														<p class="noti-profile-name">
															{{ Auth::guard('web')->user()->name}}
														</p>
														<p class="notify">{{ Auth::guard('web')->user()->email}}</p>
													</span>
												</div>
											</div>
											@if(Auth::guard('web')->user()->type == "Manager")
											<a class="dropdown-item delete-noti mt-3" href="{{url('/managerProfile')}}"><span class="profile-icon pl-4"> <span class="ml-1">{{ __('msg.profile')}}</span> <span class="arrow-right"></span></span> </a>
											@endif
											@if(Auth::guard('web')->user()->type == "Creative")
											<a class="dropdown-item delete-noti mt-3" href="{{url('/my-profile')}}"><span class="profile-icon pl-4"> <span class="ml-1"> {{ __('msg.profile')}}</span> <span class="arrow-right"></span></span> </a>
											@endif
											@if(Auth::guard('web')->user()->type == "Studio")
											<a class="dropdown-item delete-noti mt-3" href="{{url('/studio/profile')}}"><span class="profile-icon pl-4"> <span class="ml-1">{{ __('msg.profile')}}</span> <span class="arrow-right"></span></span> </a>
											@endif
											<a class="dropdown-item delete-noti arrowbt" href="#"><span class="streaming-icon pl-4"> <span class="ml-1"> {{ __('msg.streaming')}}</span> <span class="arrow-right arrowbt"></span></span> </a>
											<div id="stream_dropdown">
											<a class="dropdown-item delete-noti" href="#" data-toggle="modal" data-target="#youtubeModal"><span class="ml-1">Connect Youtube</span> </a>
											<a class="dropdown-item delete-noti" href="#"data-toggle="modal" data-target="#"> <span class="ml-1">Connect Sound Cloud</span> </a>
											<a class="dropdown-item delete-noti" href="#"data-toggle="modal" data-target="#spotifyModal"> <span class="ml-1">Connect Spotify</span> </a>
											</div>
											<a class="dropdown-item delete-noti" href="{{url('/settings')}}"><span class="setting-icon pl-4"> <span class="ml-1"> {{ __('msg.settings')}}</span> <span class="arrow-right settingbtn"></span></span> </a>
											<div id="setting_dropdown">
                                            <a class="dropdown-item delete-noti" href="{{url('/terms')}}"><span class="ml-1"> {{ __('msg.terms')}}</span> <span class="arrow-right"></span></a>
											</div>
											<a class="dropdown-item delete-noti" href="{{url('/advance')}}"><span class="get-advance-icon pl-4"> <span class="ml-1"> {{ __('msg.advance')}}</span> <span class="arrow-right"></span></span> </a>
											<a class="dropdown-item delete-noti sign-out" href="{{url('/logout')}}"><span class="signout-icon pl-4"> <span class="ml-1"> {{ __('msg.sign-out')}}</span> <span class="arrow-right"></span></span> </a>										
										</div>
									</div>
								</li>
							</ul>
								<button class="navbar-toggler order-lg-3 ml-sm-3" type="button" data-toggle="collapse" data-target="#navbar-list-2"
								aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
									<span class="navbar-toggler-icon"></span>
								</button>
								<div class="collapse navbar-collapse order-lg-1" id="navbar-list-2">
								<ul class="navbar-nav mr-auto">
								<li class="nav-item active"> 
									<a class="nav-link" href="{{url('/user-index')}}">{{ __('msg.home')}} <span class="sr-only">(current)</span></a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/addproject')}}">{{ __('msg.projects')}}</a>
								</li>
								<li class="nav-item">

									<a class="nav-link" href="{{url('/managers')}}">{{ __('msg.managers')}}</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/studios')}}">{{ __('msg.studio')}}</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/studioMapView')}}">{{ __('msg.map')}}</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/connected-users')}}">{{ __('msg.connection')}}</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/explore')}}">{{ __('msg.explores')}}</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/faq')}}">{{ __('msg.faq')}}</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{url('/contact')}}">{{ __('msg.contact')}}</a>
								</li>
							</ul>
							<input type="hidden" value="{{ Auth::guard('web')->user()->id}}" id="userid">
							</div>
						</nav>
					</div>
				</div>
			</div>
		</header>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $('#stream_dropdown').hide();
  $('.arrowbt').click(function(){
          $('#stream_dropdown').toggle()
                      return false;
  });
  $('#setting_dropdown').hide();
  $('.settingbtn').click(function(){
          $('#setting_dropdown').toggle()
                      return false;
  
  });
});
</script>
		<script>$(document).ready(function(){
var userId = document.getElementById('userid').value;
              		$.ajax({
			url:"get-connection-request",
			data:{userId: userId},
			success:function(datas)
			{
				$("#notification").html(datas);
			console.log(datas);
				}
		});
});</script>
		<script>    
				</script>
				