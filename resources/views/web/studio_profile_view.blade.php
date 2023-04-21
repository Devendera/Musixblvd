@extends('webuserlayout.profile_design')

@section('content')
<div class="container mt-3 mb-5">
         <div class="row">
            <section class="left-side mt-5 col-md-12">
               <div class="bg-white left-sidebar shadow-sm rounded-xl pb-2">
                  <div class="col-md-12">
                     <div class="row">
                        <div class="col-md-8">
                           <div class="d-flex justify-content-start">
                              <div class="profile-img d-inline-flex studio-profile">
                                 <img src="{{url('public/studio/')}}/{{$profileImg->image}}" alt="Profile Picture">
                                 <div class="online">
                                    <div class="col-md-12">
                                       <h4 class="profile-name mt-4">{{$userData->name}}</h4>
                                    </div>
                                    <div class="col-md-12">
                                    <?php if(Auth::guard('web')->check()){ ?>
													<button type="button"  class="btn btn-lg btn-primary btn-login message mt-2 add-connection-btn" data-id="{{$userData->id}}" data-url="{{URL::to('/')}}" data-type="{{$userData->type}}">{{$status}}</button>
													
												<?php } else { ?>
													<button type="button" class="btn btn-sm btn-primary btn-edit mt-3 ml-r0 " >Message</button>
													
												<?php } ?>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-4">
                           <div class="float-right right-pro">
                              <div class="manager-projects">
                                 <span><img src="{{asset('assets/images/projects.svg')}}"></span>
                                 <span class="projects">Projects</span
                                    ><span class="float-right num">{{$numberOfProjects}}</span>
                              </div>
                              <div class="total-connections">
                                 <span><img src="{{asset('assets/images/connections.svg')}}"></span>
                                 <span class="projects">Connections</span>
                                 <span class="float-right num">{{$numberOfConnections}}</span>
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
                        <div class="col-md-3 manager-view-pro mt-3">
                           <div class="profil-left-width">
                              <img src="{{asset('assets/images/mail.svg')}}">
                           </div>
                           <div class="profil-right-side">
                              <h6 class="profil-project-name mt-0">Email</h6>
                              <h6 class="profile-mangrer-mail">
                               {{$userData->email}}
                              </h6>
                           </div>
                        </div>
                        <div class="col-md-3 manager-view-pro mt-3">
                           <div class="profil-left-width">
                              <img src="{{asset('assets/images/location.svg')}}">
                           </div>
                           <div class="profil-right-side">
                              <h6 class="profil-project-name mt-0">Address</h6>
                              <h6 class="profile-mangrer-mail">
                              {{$userData->address}}
                              </h6>
                           </div>
                        </div>
                        <div class="col-md-3 manager-view-pro mt-3">
                           <div class="profil-left-width">
                              <img src="{{asset('assets/images/residental.svg')}}">
                           </div>
                           <div class="profil-right-side">
                              <h6 class="profil-project-name mt-0">Type</h6>
                              <h6 class="profile-mangrer-mail">  {{$userData->pro}}</h6>
                           </div>
                        </div>
                        <div class="col-md-3 manager-view-pro mt-3">
                           <div class="profil-left-width">
                              <img src="{{asset('assets/images/mail.svg')}}">
                           </div>
                           <div class="profil-right-side">
                              <h6 class="profil-project-name mt-0">Booking Email</h6>
                              <h6 class="profile-mangrer-mail">
                              {{$userData->booking_email}}
                              </h6>
                           </div>
                        </div>
                        <div class="col-md-3 manager-view-pro mt-3">
                           <div class="profil-left-width">
                              <img src="{{asset('assets/images/hr.svg')}}">
                           </div>
                           <div class="profil-right-side">
                              <h6 class="profil-project-name mt-0">Hourly Rate</h6>
                              <h6 class="profile-mangrer-mail">  {{$userData->hourly_rate}}</h6>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="bg-white rounded-xl pt-4 pb-4 mt-2 shadow-sm">
                  <div class="col-md-12">
                     <h5 class="profile-manager bordr-botm pb-3">Studio Images</h5>
                  </div>
                  <div class="col-md-12 mt-4">
                     <div class="row">
                     @foreach($userImg as $image)
                        <div class="col-md-4 studio mb-3">
                           <img src="{{url('public/studio/')}}/{{$image->image}}">
                        </div>
                        @endforeach
                       
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-12">
               <div class="bg-white rounded-xl pt-4 pb-4 mt-2 shadow-sm">
                  <div class="col-md-12">
                     <h5 class="profile-manager bordr-botm pb-3">Projects</h5>
                  </div>
                  <div class="col-md-12">
                     <div class="row">
                        @foreach($projectData as $project)
                        <div class="col-md-3 profile-man-img mt-3">
						<div class="proj-left-width">
                           <img src="{{ url('public/img/users/'.$project->img) }}">
						</div> 
                           <div class="proj-right-side">
                              <h6 class="profil-project-name">{{$project->title}}</h6>
                              <h6 class="profile-project-public">Public</h6>
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