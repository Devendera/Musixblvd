
@extends('webuserlayout.profile_design')

@section('content')
<div class="home-page mb-5">
<div class="container">

		<div class="row">
			<div class="col-md-12 mt-5 pt-3">
				<div class="float-left">
					<span><img class="mt-2" src="assets/images/connections.svg" width="64" height="45"></span>
				</div>
				<div class="float-left ml-2">
					<h2 class="top-project-heading">{{ __('msg.connectedusers')}}<h6 class="ml-2 total-results">{{ __('msg.about50results')}}</h6></h2>
				</div>
			</div>
			<div class="col-md-12">
				<div class="pro-border-bottom">
				</div>
			</div>
		</div>
	</div>	
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="bg-white rounded-xl shadow-sm form-home form-login form-register mt-4">
					
					<div class="col-md-12 pr-4 pl-4">
						
						<ul class="manager-list">
							@if(isset($connectedUsers))
							@foreach($connectedUsers as $k => $data)
						
							<li class="bordr-botm">
								<div class="d-flex justify-content-space-between pt-4 pb-4 connect-user">
									<div class="manager d-inline-flex">
									<?php
$img = 'assets/images/marc.jpg';
if ($data->connectedUser->type == "Studio") {
    if (!empty($data->connectedUser->img)) {
        $arr = explode("/",$data->connectedUser->img);
        $img = end($arr);
        ?>
                                    <img class="profile-pic" src="{{ url('public/studio/'.$img) }}" alt="Profile Picture">
                                 <?php } else {?>
                                    <img class="profile-pic" src="{{ url($img) }}" alt="Profile Picture">
                                 <?php }} else {?>
                                     <img class="profile-pic" src="{{ $data->connectedUser->img !='' ? $data->connectedUser->img : $img }}" alt="Profile Picture">
                                 <?php }?>
										
										<div class="online">
										@if($data->connectedUser->type == "Manager")
                                   <a href="{{ url('/manager-view/'.$data->connectedUser->id)}}"> <h4 class="manager-name">{{$data->connectedUser->name !='' ? $data->connectedUser->name : 'N\A'}}</h4></a>
                                   @endif
                                   @if($data->connectedUser->type == "Creative")
                                   <a href="{{ url('/creative-view/'.$data->connectedUser->id)}}"> <h4 class="manager-name">{{$data->connectedUser->name !='' ? $data->connectedUser->name : 'N\A'}}</h4></a>
                                   @endif
                                   @if($data->connectedUser->type == "Studio")
                                   <a href="{{ url('/studio-view/'.$data->connectedUser->id)}}"> <h4 class="manager-name">{{$data->connectedUser->name !='' ? $data->connectedUser->name : 'N\A'}}</h4></a>
                                   @endif
											
											<div class="card-text">
												<div class="mt-1">
													<span class="profil-name">{{$data->connectedUser->type}}</span>
												</div>
												<div class="d-flex mt-2">
													<span class="dot-connected connectd dot--full"></span> <span class="connected-status">{{ __('msg.connected')}}</span>
												</div>
												<div class="mt-1">
													<div class="manager-client float-left"><img src="assets/images/clients.svg">
													<span class="m-connect">Clients: </span> <span class="m-connect">
													@if(isset($data->connectedUser->manager) && !empty($data->connectedUser->manager))
		                                             {{$numberOfClients}}
		                                             @elseif(isset($data->connectedUser->creative) && !empty($data->connectedUser->creative))
		                                             {{$numberOfClients}}
		                                             @elseif(isset($data->connectedUser->studio) && !empty($data->connectedUser->studio))
		                                             {{$numberOfClients}}
		                                             @endif
													</span> </div> 
													<div class="manager-client ml-2 float-left"><img src="assets/images/connection-list.svg">
													<span class="m-connect">{{ __('msg.connections')}}: </span> <span class="m-connect"> {{$numberOfConnections}}</span> </div> 
													<div class="float-left more-link">
														<a class="nav-link dropdown-toggle manager-more-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														{{ __('msg.more')}}
														</a>
														<div class="more">
															<div class="dropdown-menu shadow-sm" aria-labelledby="navbarDropdown">
																<a class="dropdown-item" href="#"><img src="assets/images/mail.svg"> <span>{{$data->connectedUser->email }}</span></a>
																
															</div>
														</div>
													</div> 
												</div>
											</div>
										</div>
									</div>
									<div class="ml-auto">
										<div class="pb-2 mt-4  mt-r0">
											<a href="{{ url('user-chat') }} " class="btn btn-lg btn-primary btn-login message mt-2">{{ __('msg.message')}}</a>
										</div>
									</div>
								</div>
							</li>
							@endforeach
							 {{ $connectedUsers->links() }}
							@else
							<li class="bordr-botm"> 
								<div class="d-flex justify-content-space-between pt-4 pb-4 connect-user">
									<span>{{ __('msg.notfoundanyconnection')}}</span>
								</div>
							</li>
							@endif
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>

	@endsection
