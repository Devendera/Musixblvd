@extends('webuserlayout.profile_design')
@section('content')

<div class="container mt-3 mb-5">
			<div class="row">
				<section class="left-side mt-5 col-md-12">
					<div class="bg-white left-sidebar shadow-sm rounded-xl pb-4">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-8">
									<div class="d-flex justify-content-start pl-r0">
										<div class="manager-profile d-inline-flex">
											<?php if ($userData->img != '') {?>
										<img src="{{url('public/img/users/')}}/{{$userData->img}}">
									<?php	} else {?>
										<img src="assets/images/marc.jpg">
									<?php }?>
											<div class="online">
												<div class="col-md-9 pd-left">
													<h4 class="profile-name mt-4">
														{{$userData->name}}
													</h4>
												</div>
												<div class="d-flex mt-2 pl-r15">
													<span class="dot green dot--full"></span>
													<span class="status">Online</span>
												</div>
												<div class="col-md-12 pd-left req-management">
												<button type="button"
                                       class="btn btn-sm btn-primary btn-edit mt-3 ml-r0 add-connection-btn" data-id="{{$userData->user_id}}" data-url="{{URL::to('/')}}" data-type="{{$userData->type}}">{{$status}}
                                </button>
								<button type="button"
                                       class="add-request-btn req-mgmt ml-r0 req-mgmt-pending"  data-id="{{$userData->user_id}}" data-url="{{URL::to('/')}}" data-type="{{$userData->type}}">{{$request}}
                                </button>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="float-right right-pro">
										<div class="total-connections">
											<span><img src="{{asset('assets/images/connections.svg')}}"></span>
											<span class="projects">Connections</span
											><span class="float-right num">{{$numberOfConnections}}</span>
										</div>
										<div class="total-connections">
                      <span><img src="{{asset('assets/images/connections.svg')}}"></span>
                      <span class="projects">{{ __('msg.Clients')}}</span><span class="float-right num">{{$numberOfClients}}</span>
                    </div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</section>
				<div class="col-md-12">
					<div class="bg-white rounded-xl pt-4 pb-4 mt-2 shadow-sm">
						<div class="col-md-12">
							<h5 class="profile-manager bordr-botm pb-3">Basic Details</h5>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4 col-lg-3 manager-view-pro mt-3">
									<div class="profil-left-width">
										<img src="{{asset('assets/images/mail.svg')}}">
									</div>
									<div class="profil-right-side">
										<h6 class="project-info-name mt-0">Email</h6>
										<h6 class="profile-mangrer-mail">
										{{$userData->email}}
										</h6>
									</div>
								</div>
								<div class="col-md-4 col-lg-3 manager-view-pro mt-3">
									<div class="profil-left-width">
										<img src="{{asset('assets/images/company.svg')}}">
									</div>
									<div class="ml-3 basic-info">
										<h6 class="project-info-name mt-0">Management Company</h6>
										<h6 class="profile-mangrer-mail">
										{{$userData->management_company}}
										</h6>
									</div>
								</div>
								<div class="col-md-4 col-lg-3 manager-view-pro mt-3">
									<div class="float-left basic-wrap">
										<img src="{{asset('assets/images/fb.svg')}}">
									</div>
									<div class="ml-3 basic-info">
										<h6 class="project-info-name mt-0">Facebook</h6>
										<h6 class="profile-mangrer-mail">
										{{$userData->facebook}}
										</h6>
									</div>
								</div>
								<div class="col-md-4 col-lg-3 manager-view-pro mt-3">
									<div class="float-left basic-wrap">
										<img src="{{asset('assets/images/instagram.svg')}}">
									</div>
									<div class="ml-3 basic-info">
										<h6 class="project-info-name mt-0">Instagram</h6>
										<h6 class="profile-mangrer-mail">
										{{$userData->instagram}}
										</h6>
									</div>
								</div>
								<div class="col-md-4 col-lg-3 manager-view-pro mt-3">
									<div class="float-left basic-wrap">
										<img src="{{asset('assets/images/twitter.svg')}}">
									</div>
									<div class="ml-3 basic-info">
										<h6 class="project-info-name mt-0">Twitter</h6>
										<h6 class="profile-mangrer-mail">{{$userData->twitter}}</h6>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="bg-white rounded-xl pt-4 pb-4 mt-2 shadow-sm">
						<div class="col-md-12">
							<div class="bordr-botm">
								<h5 class="pb-2 profile-manager">
									Clients

								</h5>
							</div>
						</div>
						<div class="col-md-12">
						<div class="row">
						@foreach($sender as $clients)
								<div class="col-md-3 profile-man-img mt-3">
									<div class="proj-left-width">
										<img src="{{url('public/img/users/')}}/{{$clients->img}}">
									</div>
									<div class="proj-right-side">
                           <?php if ($clients->type == 1) {?>
                           <a href="{{ url('/creative-view/'.$clients->id)}}"> <h4 class="manager-name">{{$clients->name !='' ? $clients->name : 'N\A'}}</h4></a>
									<?php }?>
                           <?php if ($clients->type == 2) {?>
                           <a href="{{ url('/manager-view/'.$clients->id)}}"> <h4 class="manager-name">{{$clients->name !='' ? $clients->name : 'N\A'}}</h4></a>
									<?php }?>
                           <?php if ($clients->type == 3) {?>
                           <a href="{{ url('/studio-view/'.$clients->id)}}"> <h4 class="manager-name">{{$clients->name !='' ? $clients->name : 'N\A'}}</h4></a>
									<?php }?>
                           <h6 class="profile-project-public">Artist</h6>
									</div>
								</div>
							@endforeach
							@foreach($reciever as $recieve)
								<div class="col-md-3 profile-man-img mt-3">
									<div class="proj-left-width">
										<img src="{{url('public/img/users/')}}/{{$recieve->img}}">
									</div>
									<div class="proj-right-side">
                           <?php if ($recieve->type == 1) {?>
                           <a href="{{ url('/creative-view/'.$recieve->id)}}"> <h4 class="manager-name">{{$recieve->name !='' ? $recieve->name : 'N\A'}}</h4></a>
									<?php }?>
                           <?php if ($recieve->type == 2) {?>
                           <a href="{{ url('/manager-view/'.$recieve->id)}}"> <h4 class="manager-name">{{$recieve->name !='' ? $recieve->name : 'N\A'}}</h4></a>
									<?php }?>
                           <?php if ($recieve->type == 3) {?>
                           <a href="{{ url('/studio-view/'.$recieve->id)}}"> <h4 class="manager-name">{{$recieve->name !='' ? $recieve->name : 'N\A'}}</h4></a>
									<?php }?>
										<h6 class="profile-project-public">Artist</h6>
									</div>
								</div>
							@endforeach
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
        @endsection