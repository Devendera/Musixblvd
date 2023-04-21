@extends('webuserlayout.profile_design')

@section('content')

<div class="home-page mb-5">
<div class="d-flex px-3 py-3 pt-md-5 pb-md-4 text-center">
         <div class="col-md-9 col-lg-6 col-sm-9 mx-auto">
            <h1 class="heading mt-2">
               <span class="connect"><strong>{{ __('msg.connect')}}</strong></span>
               <strong>{{ __('msg.dashboardtext')}}</strong>
            </h1>
         </div>
      </div>

     <div class="container">
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
	<form method="GET" id="filterFrm" action="{{ url('/explore-filter') }}">
		<div class="row mt-4 pt-2 mb-2">
			<div class="col-md-12">
				<div class="input-group">
					<div class="filter shadow-sm">
						<a href="#"><div class="filter-img" id="filter"></div></a>
					</div>
					<input type="text" class="form-control border-0 rounded-xl shadow-sm search-content" placeholder="{{ __('msg.searchtext')}}" name="search" value="{{ $searchQuery }}">
					<span class="input-group-append bg-white has-search">
						<span class="search form-control-feedback explore-search">
							<img src="assets/images/search.png" alt="Search">
						</span>
					</span>
					<button class="btn btn-lg btn-primary search-btn shadow-sm" type="submit"> {{ __('msg.search')}}</button>
				</div>
			</div>
		</div>
		<div id ="togglefrm" class="bg-white rounded-xl pt-4 pb-1 shadow-sm form-home">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div class="d-flex">
							<div class="ml-auto">
                     <a class="" onclick="location.reload()" href="#">{{ __('msg.filter')}}</a>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-3 home pd-right">
						<label for="type">{{ __('msg.typ')}}</label>
						<select class="form-control" name="type">
							<option value="">{{ __('msg.type')}}</option>
							@foreach($types as $type)
							<option value="{{$type->id}}" {{ $searchType == $type->id ? "selected":"" }}>{{$type->title}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-3 home pd-right">
						<label for="Artistry">{{ __('msg.artistry')}}</label>
						<select class="form-control" name="artistry">
							<option value="">{{ __('msg.artistry')}}</option>
							@foreach($crafts as $craft)
							<option value="{{$craft->id}}"  {{ $searchArtistry == $craft->id ? "selected":"" }}>{{$craft->title}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-3 home pd-right">
						<label for="Genre">{{ __('msg.genre')}}</label>
						<select class="form-control" name="genre">
							<option value="">{{ __('msg.genre')}}</option>
							@foreach($genres as $genre)
							<option value="{{$genre->id}}" {{ $searchGenre == $genre->id ? "selected":"" }}>{{$genre->title}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-3 home">
						<label for="Gender">{{ __('msg.gender')}}</label>
						<select class="form-control" name="gender">
							<option value="">{{ __('msg.selectgender')}}</option>
							@foreach($genders as $gender)
							<option value="{{$gender->id}}" {{ $searchGender == $gender->id ? "selected":"" }}>{{$gender->title}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<div class="form-group col-md-3 home pd-right">
						<label for="connections">{{ __('msg.connections')}}</label>
						<select class="form-control" name="connection">
							<option value="">{{ __('msg.selectconnections')}}</option>
							<option value="0-30" {{ $searchConnection == "0-30" ? "selected":"" }}>0-30</option>
							<option value="31-60" {{ $searchConnection == "31-60" ? "selected":"" }}>31-60</option>
							<option value="61-100" {{ $searchConnection == "61-100" ? "selected":"" }}>61-100</option>
							<option value="100+" {{ $searchConnection == "100+" ? "selected":"" }}>100+</option>
						</select>
					</div>
					<div class="form-group col-md-3 home pd-right">
						<label for="projects">{{ __('msg.projects')}}</label>
						<select class="form-control" name="project">
							<option value="">{{ __('msg.selectprojects')}}</option>
							<option value="0-10"  {{ $searchProject == "0-10" ? "selected":"" }}>0-10</option>
							<option value="11-20" {{ $searchProject == "11-20" ? "selected":"" }}>11-20</option>
							<option value="21-30" {{ $searchProject == "21-30" ? "selected":"" }}>21-30</option>
							<option value="31-40" {{ $searchProject == "31-40" ? "selected":"" }}>31-40</option>
							<option value="50-60" {{ $searchProject == "50-60" ? "selected":"" }}>50-60</option>
							<option value="60+" {{ $searchProject == "60+" ? "selected":"" }}>60+</option>
						</select>
					</div>
					<div class="form-group col-md-3 home pd-right">
						<label for="streams">{{ __('msg.streams')}}</label>
						<select class="form-control" name="stream">
							<option value="">{{ __('msg.none')}} </option>
							<option value="0-10K">0-10K</option>
							<option value="10K-50K">10K-50K</option>
							<option value="50K-100K">50K-100K</option>
							<option value="100K-250K">100K-250K</option>
							<option value="250K+">250K+</option>
						</select>
					</div>
					<div class="form-group col-md-3 home">
						<label for="country">{{ __('msg.country')}}</label>
						<select class="form-control" name="country" id="search-country" data-url="{{url('/')}}">
							<option value="">{{ __('msg.country')}}</option>
							@foreach($countries as $country)
							<option value="{{$country->id}}" {{ $searchCountry == $country->id ? "selected":"" }}>{{$country->name}}</option>
							@endforeach
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-12">
				<div class="row">
					<div class="form-group col-md-3 home pd-right">
						<label for="state">{{ __('msg.state')}}</label>
						<select class="form-control" name="state">
							<option value="">{{ __('msg.SelectState')}} </option>
						</select>
					</div>
					<div class="form-group col-md-3 pd-right">
						<label for="city">{{ __('msg.city')}}</label>
						<input type="text" class="form-control" id="city" placeholder="{{ __('msg.city')}} " name="city" value="{{ Input::old('city') }}">
					</div>
				</div>
			</div>
		</form>
	   </div>
	      <?php if (Auth::guard('web')->check()) {?>
	   <div class="bg-white rounded-xl pt-4 pb-1 shadow-sm form-home">
      <div class="col-md-12 pr-4 pl-4">
                     <h5 class="profile-manager bordr-botm pb-2">{{ __('msg.streaming')}}</h5>
      <ul class="manager-list streaming-list">
		                <?php if ($streamingData != '0') {?>
						<?php if (!$streamingData->isEmpty()) {?>
						@foreach($streamingData as $stream)
						<?php if ($stream->provider_type == "youtube") {?>
						   <li class="bordr-botm">
                           <div class="d-flex justify-content-space-between align-items-center pt-3 pb-3">
                              <div class="d-inline-flex">
                                <div class="stream-icon"><img class="icon" src="assets/images/youtube.png" alt="You Tube"></div>
                                 <div class="online">
                                    <h4 class="strem-name">{{$stream->username}}</h4>
                                    <div class="card-text">
                                       <div class="mt-1">
                                          <div class="strem-client float-left">
                                             <span class="m-connect">{{ __('msg.subscribers')}}: </span> <span class="m-connect"> {{$stream->followers}}</span>
                                          </div>
                                          <div class="strem-client  ml-2 float-left ml-r0">
                                             <span class="m-connect">{{ __('msg.vedio')}}:</span> <span class="m-connect"> {{$stream->songs}} </span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="ml-auto">
                                 <div class="pb-2 ">
                                    <a href="{{ url('/deleteStreaming/'.$stream->id)}}"><img src="assets/images/delete.svg"></a>
                                 </div>
                              </div>
                           </div>
                        </li>
						<?php } elseif ($stream->provider_type == "spotify") {?>
						   <li class="bordr-botm">
                           <div class="d-flex justify-content-space-between pt-3 pb-3 align-items-center">
                              <div class="d-inline-flex">
                                <div class="stream-icon"><img class="icon" src="assets/images/lines.png" alt="You Tube"></div>
                                 <div class="online">
                                    <h4 class="strem-name">{{$stream->username}}</h4>
									<div class="card-text">
                                       <div class="mt-1">
                                          <div class="strem-client float-left">
                                             <span class="m-connect">{{ __('msg.followers')}}: </span> <span class="m-connect"> {{$stream->followers}}</span>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="ml-auto">
                                 <div class="pb-2 ">
								 <a href="{{ url('/deleteStreaming/'.$stream->id)}}"><img src="assets/images/delete.svg"></a>
                                 </div>
                              </div>
                           </div>
                        </li>
						<?php }?>
						@endforeach
						<?php } else {?>
							<li class="bordr-botm">
								<div class="not-connected d-flex justify-content-center pt-4 pb-4">
									<span>{{ __('msg.noservice')}}</span>
								</div>
							</li>
						<?php }} else {?>
							<li class="bordr-botm">
								<div class="not-connected d-flex justify-content-center pt-4 pb-4">
									<span>{{ __('msg.noservice')}}</span>
								</div>
							</li>
						<?php }?>
                     </ul>
                  </div>
         </div>
		  <?php }?>
      </div>
     </div>
@endsection
