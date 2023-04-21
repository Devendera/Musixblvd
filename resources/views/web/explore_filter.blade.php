
@extends('webuserlayout.profile_design')
@section('content')
<div class="home-page mb-5">
<div class="container">
	<div class="row">
		<div class="col-md-12 mt-5 pt-3">
			<div class="float-left">
				<span><img class="mt-2" src="assets/images/search2x.png"></span>
			</div>
			<div class="float-left ml-2">
				<h2 class="top-project-heading">  {{ __('msg.explores')}} <h6 class="ml-2 total-results">{{ __('msg.about50results')}}</h6></h2>
			</div>
		</div>
		<div class="col-md-12">
			<div class="">
				<div class="alert alert-danger alert-desktop mt-3 mb-0" id="ajaxdiv">
                  <strong style = "color:red" id="ajaxResponse"></strong>
               </div>
				@if($errors->any())
				<div class="alert alert-danger alert-desktop mt-3 mb-0">
                	<strong style = "color:red">{{ implode('', $errors->all(':message')) }}</strong>
                </div>
				@endif
				@if(Session::has('flash_message_success'))
	            <div class="alert alert-success alert-block alert-desktop mt-3 mb-0">
	                <button type="button" class="close" data-dismiss="alert">×</button>
	                <strong style = "color:green">{{ Session::get('flash_message_success') }}</strong>
	            </div>
	         	@endif
			</div>
		</div>
	</div>
</div>

<div class="container">
	<form method="GET" id="filterFrm" action="{{ url('/explore-filter') }}">
		<div class="row mt-4 pt-2 mb-2">
			<div class="col-md-12">
				<div class="input-group">
					<div class="filter shadow-sm">
						<a href="{{ url('/explore-filter') }}"><div class="filter-img"></div></a>
					</div>
					<input type="text" class="form-control border-0 rounded-xl shadow-sm search-content" placeholder="{{ __('msg.searchtext')}}" name="search" value="{{ $searchQuery }}">
					<span class="input-group-append bg-white has-search">
						<span class="search form-control-feedback explore-search">
							<img src="assets/images/search.png" alt="Search">
						</span>
					</span>
					<button class="btn btn-lg btn-primary search-btn shadow-sm" type="submit"> {{ __('msg.explore')}}</button>
				</div>
			</div>
		</div>
		<div class="bg-white rounded-xl pt-4 pb-1 shadow-sm form-home">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-12">
						<div class="d-flex">
							<div class="ml-auto">
								<a class="filter-clear" href="#">{{ __('msg.filter')}}</a>
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
							<option value="">None</option>
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
							<option value="">Select State</option>
						</select>
					</div>
					<div class="form-group col-md-3 pd-right">
						<label for="city">{{ __('msg.city')}}</label>
						<input type="text" class="form-control" id="city" placeholder="City" name="city" value="{{ Input::old('city') }}">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="container">
 	<div class="row">
    	<div class="col-md-12">
       		<div class="bg-white rounded-xl shadow-sm form-home form-login form-register mt-4  mb-5">
          		<div class="col-md-12 pr-4 pl-4">
             		<ul class="manager-list">
	                @if(isset($users))
	                @foreach($users as $index => $user)
	                	<li class="bordr-botm">
	                   		<div class="d-flex justify-content-space-between pt-4 pb-4 connect-user">
                      			<div class="manager d-inline-flex">
								<?php
$img = 'assets/images/marc.jpg';
if ($user->type == "Studio") {
    if (!empty($user->img)) {
        $arr = explode("/", $user->img);
        $img = end($arr);
        ?>
                                    <img class="profile-pic" src="{{ url('public/studio/'.$img) }}" alt="Profile Picture">
                                 <?php } else {?>
                                    <img class="profile-pic" src="{{ url($img) }}" alt="Profile Picture">
                                 <?php }} else {?>
                                     <img class="profile-pic" src="{{ $user->img !='' ? $user->img : $img }}" alt="Profile Picture">
                                 <?php }?>
                         			<div class="online">
									 @if($user->type == "Manager")
                                   <a href="{{ url('/manager-view/'.$user->id)}}"> <h4 class="manager-name">{{$user->name}}</h4></a>
                                   @endif
                                   @if($user->type == "Creative")
                                   <a href="{{ url('/creative-view/'.$user->id)}}"> <h4 class="manager-name">{{$user->name}}</h4></a>
                                   @endif
                                   @if($user->type == "Studio")
                                   <a href="{{ url('/studio-view/'.$user->id)}}"> <h4 class="manager-name">{{$user->name}}</h4></a>
                                   @endif
                            			<div class="card-text">
			                               <div class="mt-1">
			                                  <span class="profil-name">{{$user->type}}</span>
			                               </div>
                               				<div class="d-flex mt-2">
												<span class="dot-connected connectd dot--full"></span>
												@if($user->connection == config('constants.NotConnected')) 
												<span class="not-connected-status">{{$user->connection}}</span>
												@else
												<span class="connected-status">{{$user->connection}}</span>
                               				     @endif
											</div>
			                                <div class="mt-1">
			                                  	<div class="manager-client float-left">
			                                    	<img src="assets/images/clients.svg">
			                                     	<span class="m-connect">{{ __('msg.clients')}}: </span>
			                                     	<span class="m-connect"> @if(isset($user->manager) && !empty($user->manager))
		                                             {{$user->manager->clients_count}}
		                                             @elseif(isset($user->creative) && !empty($user->creative))
		                                             {{$user->creative->clients_count}}
		                                             @elseif(isset($user->studio) && !empty($user->studio))
		                                             {{$user->studio->clients_count}}
		                                             @endif</span>
			                                  	</div>
                                  				<div class="manager-client ml-2 float-left ml-r0">
                                  					<img src="assets/images/connection-list.svg">
                                     				<span class="m-connect">{{ __('msg.connection')}}: </span>
                                     				<span class="m-connect"> {{$user->connections_count}}</span>
                                  				</div>
                                  				<div class="float-left more-link">
                                     				<a class="nav-link dropdown-toggle manager-more-link active" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     				{{ __('msg.more')}}
                                     				</a>
                                     				<div class="more">
                                        				<div class="dropdown-menu explore-icons shadow-sm" aria-labelledby="navbarDropdown">
                                           					<a class="dropdown-item" href="#">
                                           					<img src="assets/images/mail.svg">
                                           					<span>{{$user->email}}</span></a>
                                           					@if(isset($user->manager) && !empty($user->manager))
                                           					<a class="dropdown-item" href="#" >
                                           					<img src="assets/images/company.svg"> <span>{{ $user->manager->company }}</span></a>
                                           					<a class="dropdown-item" href="{{ $user->manager->facebook }}" target="#">
                                           					<img src="assets/images/fb.svg"> <span>{{ $user->manager->facebook }}</span></a>
				                                           <a class="dropdown-item" href="{{ $user->manager->instagram }}" target="#">
				                                           <img src="assets/images/instagram.svg">
				                                           <span>{{ $user->manager->instagram }}</span></a>
				                                           <a class="dropdown-item" href="{{ $user->manager->twitter }}" target="#">
				                                           <img src="assets/images/twitter.svg">
				                                           <span>{{ $user->manager->twitter }}</span></a>
				                                           @endif
				                                           @if(isset($user->creative) && !empty($user->creative))
				                                           <a class="dropdown-item" href="#">
				                                           <img src="assets/images/company.svg"> <span>{{ $user->creative->website }}</span></a>
				                                           @endif
				                                           @if(isset($user->studio) && !empty($user->studio))
				                                           <a class="dropdown-item" href="#">
				                                           <img src="assets/images/company.svg"> <span>{{ $user->studio->address }}</span></a>
				                                           @endif
                                        				</div>
                                     				</div>
                                  				</div>
                               				</div>
                            			</div>
                         			</div>
                      			</div>
                      			@if($user->connection == config('constants.NotConnected'))
			                    <div class="ml-auto">
									<div class="pb-2 mt-4 mt-r0">
										<button type="button"
									   class="btn btn-lg btn-primary btn-login message mt-2 add-connection-btn" data-id="{{$user->id}}" data-url="{{URL::to('/')}}" data-type="{{$user->type}}">Add Connection</button>
									</div>
								</div>
								@else
								<div class="ml-auto">
									<div class="pb-2 mt-4">
									<button type="button"
									   class="btn btn-lg btn-primary btn-login message mt-2" data-id="{{$user->id}}" data-url="{{URL::to('/')}}">Message</button>
									</div>
								</div>
                      			@endif
                   			</div>
                		</li>
		                @endforeach
		                {{ $users->links() }}
		                @else
		                <li class="bordr-botm">
		                   <div class="d-flex justify-content-space-between pt-4 pb-4 connect-user">
		                      <div class="manager d-inline-flex">
		                         Not found any users
		                      </div>
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