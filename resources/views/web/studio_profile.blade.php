@extends('webuserlayout.profile_design')

@section('content')
<div class="container mt-3 mb-5">
         <div class="row">
            <section class="left-side mt-5 col-md-4 pd-right">
               <div class="bg-white left-sidebar shadow-sm rounded-xl">
                  <div class="col-md-12">
                     <div class="row">
                        <div class="col-md-12">
                           <div class="d-flex justify-content-start">
                              <div class="studio-profile profil-pic d-inline-flex">
                                 <?php if (!empty($profileImg) && !empty($profileImg->image)) {?>
                                    <img src="{{url('public/studio/')}}/{{$profileImg->image}}" alt="Profile Picture">
                                 <?php } else {?>
                                    <img src="{{ url('assets/images/marc.jpg') }}" alt="Profile Picture">
                                 <?php }?>
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
                              <span><img src="{{asset('assets/images/projects.svg')}}"></span>
                              <span class="projects">Projects</span
                                 ><span class="float-right num">{{$numberOfProjects}}</span>
                           </div>
                           <div class="total-connections">
                              <span><img src="{{asset('assets/images/connections.svg')}}"></span>
                              <span class="projects">Connections</span
                                 ><span class="float-right num">{{$numberOfConnections}}</span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
            <section class="right-side mt-5 col-md-8 pl-2 studio-pd-left pd-left">
               <div class="bg-white rounded-xl shadow-sm form-home form-login form-register">
                  <div class="col-md-12">
                     <div class="bordr-botm">
                        <h5 class="pb-2 profile-manager pt-3">
                           Project Information
                           <span class="float-right mg-top-5 ">
                           <a class="btn btn-sm btn-primary btn-edit mt-r0" href="{{ url('/studioEditProfile') }}">Edit Profile</a>
                           </span>
                        </h5>
                     </div>
                  </div>
                  <div class="col-md-12 mt-4">
                     <div class="row">

                        <div class="form-group col-md-6 col-lg-4 home pd-right">
                           <label for="Studio Name">Studio Name</label>
                           <input type="text" class="form-control" id="firstName" value="{{ @$userData->name }}" readonly  placeholder="Studio Name">
                        </div>
                        <div class="form-group col-md-6 col-lg-4 home pd-right pr-r15">
                           <label for="Address">Address</label>
                           <input type="text" class="form-control" id="lastName" placeholder="Address" value="{{ @$userData->address}}" readonly>
                        </div>
                        <div class="form-group col-md-6 col-lg-4 home pr-r0">
                           <label for="Management Company">Booking Email</label>
                           <input type="email" class="form-control" id="company" placeholder="Booking Email" value="{{ @$userData->booking_email}}" readonly>
                        </div>
                        <div class="form-group col-md-6 col-lg-4 home form-register pd-right pr-r15">
                           <label for="Facebook">Hourly Rate</label>
                           <input type="text" class="form-control" id="fb" placeholder="Hourly Rate" value="{{ @$userData->hourly_rate}}" readonly>
                        </div>
                        <div class="form-group col-md-6 col-lg-4 home pd-right ">
                           <label for="Instagram">Performance Rights Organization</label>
                           <input type="text" class="form-control" id="instagram" placeholder="Performance Rights Organization" value="{{$userData->pro}}" readonly>
                        </div>
                        <div class="form-group col-md-6 col-lg-4 home pr-r15">
                           <label for="Twitter">Select One Option</label>
                           <input type="text" class="form-control" id="" placeholder="Select Option" value="<?php if ($userData->type == 1) {echo ("Independent");} else {echo ("Under Contract");}?>" readonly>
                        </div>
                     </div>
                  </div>
               </div>
            </section>
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