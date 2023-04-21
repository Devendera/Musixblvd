@extends('webuserlayout.profile_design')
@section('content')
<div class="container mt-3 mb-5">
      <div class="row">
        <section class="left-side mt-5 col-md-12">
          <div class="bg-white left-sidebar shadow-sm rounded-xl pb-4">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-8">
                  <div class="d-flex justify-content-start">
                    <div class="manager-profile d-inline-flex">
					<?php if ($userData->img != '') {?>
            <img src="{{$userData->img}}">
					<?php	} else {?>
					<img src="assets/images/marc.jpg">
					<?php }?>
                      <div class="online">
                        <div class="col-md-9 pd-left">
                          <h4 class="profile-name mt-4">
                           {{$userData->name}}
                          </h4>
                        </div>
                        <div class="d-flex mt-2 pd-left">
                          <span class="dot green dot--full"></span>
                          <span class="status">{{ __('msg.online')}}</span>
                        </div>
                        <div class="col-md-12 pd-left stream-view">
                        
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
                      <span class="projects">{{ __('msg.projects')}}</span><span class="float-right num">{{$numberOfProjects}}</span>
                    </div>
                    <div class="total-connections">
                      <span><img src="{{asset('assets/images/connections.svg')}}"></span>
                      <span class="projects">{{ __('msg.connections')}}</span><span class="float-right num">{{$numberOfConnections}}</span>
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
              <h5 class="profile-manager bordr-botm pb-3">{{ __('msg.basicdetails')}}</h5>
            </div>
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4 manager-view-pro mt-4">
                  <div class="profil-left-widths">
                    <img src="{{asset('assets/images/mail.svg')}}">
                  </div>
                  <div class="profil-right-sides">
                    <h6 class="project-info-name mt-0">{{ __('msg.email')}}</h6>
                    <h6 class="profile-mangrer-mail">
                    {{$userData->email}}
                    </h6>
                  </div>
                </div>
                <div class="col-md-3 manager-view-pro mt-4">
                  <div class="profil-left-widths">
                    <img src="{{asset('assets/images/website.svg')}}">
                  </div>
                  <div class="profil-right-sides">
                    <h6 class="project-info-name mt-0">{{ __('msg.website')}}</h6>
                    <h6 class="profile-mangrer-mail">
                    {{$userData->website}}
                    </h6>
                  </div>
                </div>
                 <div class="col-md-3 manager-view-pro mt-4">
                  <div class="profil-left-widths">
                    <img src="{{asset('assets/images/location.svg')}}">
                  </div>
                  <div class="profil-right-sides">
                    <h6 class="project-info-name mt-0">{{ __('msg.state')}}</h6>
                    <h6 class="profile-mangrer-mail">
                    {{$userData->state}}
                    </h6>
                  </div>
                </div>
                <div class="col-md-2 manager-view-pro mt-4">
                  <div class="profil-left-widths">
                    <img src="{{asset('assets/images/location.svg')}}">
                  </div>
                  <div class="profil-right-sides">
                    <h6 class="project-info-name mt-0">{{ __('msg.city')}}</h6>
                    <h6 class="profile-mangrer-mail">
                    {{$userData->city}}
                    </h6>
                  </div>
                </div>
                <div class="col-md-4 manager-view-pro mt-4">
                  <div class="profil-left-widths">
                    <img src="{{asset('assets/images/gender.svg')}}">
                  </div>
                  <div class="profil-right-sides">
                    <h6 class="project-info-name mt-0">{{ __('msg.gender')}}</h6>
                    <h6 class="profile-mangrer-mail">
                    {{$genders->title}}
                    </h6>
                  </div>
                </div>
                <div class="col-md-3 manager-view-pro mt-4">
                  <div class="profil-left-widths">
                    <img src="{{asset('assets/images/type.svg')}}">
                  </div>
                  <div class="profil-right-sides">
                    <h6 class="project-info-name mt-0">{{ __('msg.typ')}}</h6>
                    <h6 class="profile-mangrer-mail">
				            	{{$types->title}}
                    </h6>
                  </div>
                </div>
                <div class="col-md-3 manager-view-pro mt-4">
                  <div class="profil-left-widths">
                    <img src="{{asset('assets/images/stage.svg')}}">
                  </div>
                  <div class="profil-right-sides">
                    <h6 class="project-info-name mt-0">{{ __('msg.stage')}}</h6>
                    <h6 class="profile-mangrer-mail"> {{$userData->stage}}</h6>
                  </div>
                </div>
                <div class="col-md-2 manager-view-pro mt-4">
                  <div class="profil-left-widths">
                    <img src="{{asset('assets/images/suitcase.svg')}}">
                  </div>
                  <div class="profil-right-sides">
                    <h6 class="project-info-name mt-0">{{ __('msg.user')}}</h6>
                    <h6 class="profile-mangrer-mail">{{$crafts->title}}</h6>
                  </div>
                </div>
                 <div class="col-md-4 manager-view-pro mt-4">
                  <div class="profil-left-widths rights-organization">
                    <img src="{{asset('assets/images/performance.svg')}}">
                  </div>
                  <div class="profil-right-sides rights-organization">
                    <h6 class="project-info-name mt-0">{{ __('msg.performancerightorganisation')}}</h6>
                    <h6 class="profile-mangrer-mail">{{$userData->pro}}</h6>
                  </div>
                </div>
                <div class="col-md-3 manager-view-pro mt-4">
                  <div class="profil-left-widths">
                    <img src="{{asset('assets/images/genre.svg')}}">
                  </div>
                  <div class="profil-right-sides">
                    <h6 class="project-info-name mt-0">{{ __('msg.genre')}}</h6>
                    <h6 class="profile-mangrer-mail">{{$genres->title}}</h6>
                  </div>
                </div>
                <div class="col-md-2 manager-view-pro mt-4">
                  <div class="profil-left-widths">
                    <img src="{{asset('assets/images/status.svg')}}">
                  </div>
                  <div class="profil-right-sides">
                    <h6 class="project-info-name mt-0">{{ __('msg.status')}}</h6>
                    <h6 class="profile-mangrer-mail"> <?php if ($userData->status === 1) {echo 'Independent';} else {echo 'Under Contract';}?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
		<?php if (!empty($userData['platforms']) && count($userData['platforms']) > 0) { ?>
			<div class="col-md-12">
			  <div class="bg-white rounded-xl pt-4 pb-4 mt-2 shadow-sm">
				<div class="col-md-12">
				  <h5 class="profile-manager bordr-botm pb-3">
				  {{ __('msg.streamingplatforms')}}
				  </h5>
				</div>
				<div class="col-md-12">
				  <div class="streaming-platforms">
					<ul class="d-flex mt-3 user-stre">
					<?php
						foreach ($userData['platforms'] as $key => $value) {
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
			  </div>
			</div>
		<?php } ?>
        <?php  if(!empty($userData->creativesInfluencers)){ ?>
        <div class="col-md-12">
          <div class="bg-white rounded-xl pt-4 pb-2 mt-2 shadow-sm">
            <div class="col-md-12">
              <h5 class="profile-manager bordr-botm pb-3">{{ __('msg.influencers')}}</h5>
            </div>
            <div class="col-md-12">
              <p class="influ-content">{{ $userData->creativesInfluencers }}</p>
            </div>
          </div>
        </div>
        <?php } ?>
        <div class="col-md-12">
          <div class="bg-white rounded-xl pt-4 pb-4 mt-2 shadow-sm">
            <div class="col-md-12">
              <h5 class="profile-manager bordr-botm pb-3">
              {{ __('msg.socialmedialinks')}}
              </h5>
            </div>
            <div class="col-md-12">
               <div class="row">
                
				  <div class="col-md-12">
                      <div class="project-link d-flex justify-content-start">
                              <span class="copy-link ml-2">
                              <a href="#">{{$userData->links}}</a></span>
							  </div>
                          <span class="project-link-bg">
                           <a href="{{$userData->links}}" class="copy-link-btn">Copy link</a>
                           </span>
						  </div>
				       </div>
              
                     <div class="row mt-2">
					 </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="bg-white rounded-xl pt-4 pb-4 mt-2 shadow-sm">
            <div class="col-md-12">
              <div class="bordr-botm">
                <h5 class="pb-2 profile-manager">{{ __('msg.managers')}}</h5>
              </div>
            </div>
                        <div class="col-md-12">
                        <div class="row">
                        @foreach($reciever as $recieve)
                <div class="col-md-3 profile-man-img mt-3">
				  <div class="proj-left-width">
          <img src="{{ url('public/img/users/'.$recieve->img) }}">
				  </div>
                   <div class="proj-right-side">
                   <a href="{{ url('/manager-view/'.$recieve->id)}}"> <h4 class="profil-project-name">{{$recieve->name !='' ? $recieve->name : 'N\A'}}</h4></a>
                       <h6 class="profile-project-public">Artist</h6>
                   </div>
                   </div>
                   @endforeach
                   @foreach($sender as $sender)
                <div class="col-md-3 profile-man-img mt-3">
				  <div class="proj-left-width">
          <img src="{{ url('public/img/users/'.$sender->img) }}">
				  </div>
                   <div class="proj-right-side">
                   <a href="{{ url('/manager-view/'.$sender->id)}}"> <h4 class="profil-project-name">{{$sender->name !='' ? $sender->name : 'N\A'}}</h4></a>
                       <h6 class="profile-project-public">Artist</h6>
                   </div>
                   </div>
                   @endforeach
              </div>
              </div>
            </div>
          </div>
        <div class="col-md-12">
          <div class="bg-white rounded-xl pt-4 pb-4 mt-2 shadow-sm">
            <div class="col-md-12">
              <div class="bordr-botm">
                <h5 class="pb-2 profile-manager">{{ __('msg.projects')}}</h5>
              </div>
            </div>
            <div class="col-md-12">
              <div class="row">
              @foreach($projectData as $project)
                        <div class="col-md-3 profile-man-img mt-3">
						<div class="proj-left-width">
                           <img src="{{ url('public/img/users/'.$project->img) }}">
						</div>
                           <div class="proj-right-side">
                              <a href="{{ url('/projectInfo/'.$project->id)}}"><h6 class="profil-project-name">{{$project->title}}</h6></a>
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
    </div>
@endsection