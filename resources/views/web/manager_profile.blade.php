@extends('webuserlayout.profile_design')
@section('content')
<div class="container mt-3 mb-5">
         <div class="row">
            <section class="left-side mt-5 col-md-4 pd-right">
               <div class="bg-white left-sidebar shadow-sm rounded-xl pb-4">
			     <div class="col-md-12">
                  @if(Session::has('flash_message_error'))
                   <div class="alert alert-danger alert-desktop">
                   <strong>{{ Session::get('flash_message_error') }}</strong>
                   </div>
               @endif
               @if(Session::has('flash_message_success'))
                  <div class="alert alert-success alert-desktop">
                      <button type="button" class="close" data-dismiss="alert">×</button>
                      <strong>{{ Session::get('flash_message_success') }}</strong>
                  </div>
               @endif
                     <div class="row">
                        <div class="col-md-12">
                           <div class="d-flex justify-content-start">
                              <div class="manager-profile d-inline-flex">
                                 <img src="{{url('public/img/users/')}}/{{$userData->img}}" alt="Profile Picture" />
                                 <div class="online">
                                    <h4 class="profile-name mt-4">
                                       {{$userData->name}}
                                    </h4>
                                    <div class="d-flex mt-2">
                                       <span class="dot green dot--full"></span>
                                       <span class="status">Online</span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <div class="manager-projects">
                              <span class="clients-img"><img src="assets/images/clients.svg" /></span>
                              <span class="projects">Clients</span>
                               <span class="float-right num">{{$numberOfClients}}</span>
                           </div>
                           <div class="total-connections">
                              <span><img src="assets/images/connections.svg" /></span>
                              <span class="projects">Connections</span
                                 ><span class="float-right num">{{$numberOfConnections}}</span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <section class="right-side mt-5 col-md-8 pl-2 pd-left">
               <div class=" bg-white rounded-xl pt-4 pb-2 shadow-sm form-home form-login form-register">
                  <div class="col-md-12">
                     <div class="bordr-botm">
                        <h5 class="pb-2 profile-manager">
                           Basic Details
                           <span class="float-right mg-top-5"><a class="btn btn-sm btn-primary btn-edit mt-r0" href="{{ url('/editProfile') }}">Edit Profile</a></span>
                        </h5>
                     </div>
                  </div>
                  <form>
                     <div class="col-md-12 mt-4">
                        <div class="row">
                           <div class="form-group col-md-6 col-lg-4 home pd-right">
                              <label for="First Name">First Name</label>
                              <input type="text"class="form-control" id="firstName" value=<?php print_r($nameArr[0]);?> readonly>
                           </div>
                           <div class="form-group col-md-6 col-lg-4 home pd-right pr-r15">
                              <label for="Last Name">Last Name</label>
                              <input type="text" class="form-control" id="lastName" value=<?php print_r($nameArr[1]);?> readonly>
                           </div>
                           <div class="form-group col-md-6 col-lg-4 home pr-r0">
                              <label for="Management Company">Management Company</label>
                              <input type="text" class="form-control" id="company" value= {{$userData->management_company}} readonly>
                           </div>
                           <div class="form-group col-md-6 col-lg-4 home pd-right pr-r15">
                              <label for="Facebook">Facebook</label>
                              <input type="text" class="form-control" id="fb" readonly value= {{$userData->facebook}} >
                           </div>
                           <div class="form-group col-md-6 col-lg-4 home pd-right">
                              <label for="Instagram">Instagram</label>
                              <input type="text" class="form-control" id="instagram" readonly value= {{$userData->instagram}} >
                           </div>
                           <div class="form-group col-md-6 col-lg-4 home pr-r0">
                              <label for="Twitter">Twitter</label>
                              <input type="text" class="form-control" id="Twitter" readonly value= {{$userData->twitter}} >
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </section>
            <div class="col-md-12">
               <div class="bg-white rounded-xl pt-4 pb-4 mt-2 shadow-sm">
                  <div class="col-md-12">
                     <div class="bordr-botm">
                        <h5 class="pb-2 profile-manager">
                           Clients
                           <span class="float-right mg-top-5"><a class="btn btn-sm btn-primary btn-add-client" href="{{url('/creatives')}}">Add Client</a></span>
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
                           <a href="{{ url('/creative-view/'.$clients->id)}}"> <h4 class="project-info-name mt-0">{{$clients->name !='' ? $clients->name : 'N\A'}}</h4></a>
									<?php }?>
                           <?php if ($clients->type == 2) {?>
                           <a href="{{ url('/manager-view/'.$clients->id)}}"> <h4 class="project-info-name mt-0">{{$clients->name !='' ? $clients->name : 'N\A'}}</h4></a>
									<?php }?>
                           <?php if ($clients->type == 3) {?>
                           <a href="{{ url('/studio-view/'.$clients->id)}}"> <h4 class="project-info-name mt-0">{{$clients->name !='' ? $clients->name : 'N\A'}}</h4></a>
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
                           <a href="{{ url('/creative-view/'.$recieve->id)}}"> <h4 class="project-info-name mt-0">{{$recieve->name !='' ? $recieve->name : 'N\A'}}</h4></a>
									<?php }?>
                           <?php if ($recieve->type == 2) {?>
                           <a href="{{ url('/manager-view/'.$recieve->id)}}"> <h4 class="project-info-name mt-0">{{$recieve->name !='' ? $recieve->name : 'N\A'}}</h4></a>
									<?php }?>
                           <?php if ($recieve->type == 3) {?>
                           <a href="{{ url('/studio-view/'.$recieve->id)}}"> <h4 class="project-info-name mt-0">{{$recieve->name !='' ? $recieve->name : 'N\A'}}</h4></a>
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
