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
                  <h2 class="top-project-heading">
                  {{ __('msg.explores')}}
                     <h6 class="ml-2 total-results">{{ __('msg.about50results')}}</h6>
                  </h2>
               </div>
            </div>
            <div class="col-md-12">
               <div class="pro-border-bottom">
               </div>
            </div>
         </div>
      </div>
      <div class="container">
         <div class="row mt-4 pt-2 mb-2">
            <div class="col-md-12">
               <div class="alert alert-danger" id="ajaxdiv">
                  <strong id="ajaxResponse"></strong>
               </div>
               @if($errors->any())
               <div class="alert alert-danger">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ implode('', $errors->all(':message')) }}</strong>
               </div>
               @endif
               @if(Session::has('flash_message_error'))
               <div class="alert alert-success alert-block alert-desktop">
                   <button type="button" class="close" data-dismiss="alert">×</button>
                   <strong style = "color:red">{{ Session::get('flash_message_error') }}</strong>
               </div>
               @endif
               <form method="get" id="searchFrm" action= "{{ url('/explore') }}" enctype="multipart/form-data">
                  <div class="input-group">
                     <div class="filter shadow-sm">
                        <a href="{{url('/explore-filter')}}"><img src="assets/images/filter.svg" alt="filter"></a>
                     </div>

                     <input type="text" class="form-control border-0 rounded-xl shadow-sm search-content"
                        placeholder="{{ __('msg.searchtext')}}" name="search" value="{{ $searchQuery }}">
                     <span class="input-group-append bg-white has-search">
                     <span class="search form-control-feedback explore-search">
                     <img src="assets/images/search.png" alt="Search">
                     </span>
                     </span>
                     <button class="btn btn-lg btn-primary search-btn shadow-sm" type="submit">  {{ __('msg.search')}}</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="bg-white rounded-xl shadow-sm form-home form-login form-register mt-4">
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
                                    <img class="profile-pic" src="{{ url('$img') }}" alt="Profile Picture">
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
                                             <span class="m-connect">
                                             @if(isset($user->manager) && !empty($user->manager))
                                             {{$user->manager->clients_count}}
                                             @elseif(isset($user->creative) && !empty($user->creative))
                                             {{$user->creative->clients_count}}
                                             @elseif(isset($user->studio) && !empty($user->studio))
                                             {{$user->studio->clients_count}}
                                             @endif
                                             </span>
                                          </div>
                                          <div class="manager-client ml-2 float-left ml-r0"><img
                                             src="assets/images/connection-list.svg">
                                             <span class="m-connect">{{ __('msg.connection')}}: </span>
                                             <span class="m-connect"> {{$user->connections_count}}</span>
                                          </div>
                                          <div class="float-left more-link">
                                             <a class="nav-link dropdown-toggle manager-more-link active" href="#" id="navbarDropdown" role="button"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                {{ __('msg.more')}}
                                             </a>
                                             <div class="more">
                                                <div class="dropdown-menu explore-icons shadow-sm"
                                                   aria-labelledby="navbarDropdown">
                                                   <a class="dropdown-item" href="#">
                                                   <img src="assets/images/mail.svg">
                                                   <span>{{$user->email}}</span></a>
                                                   @if(isset($user->manager) && !empty($user->manager))
                                                   <a class="dropdown-item" href="#">
                                                   <img src="assets/images/company.svg"> <span>Sample
                                                   {{ $user->manager->company }}</span></a>
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
                                                   <img src="assets/images/company.svg"> <span>
                                                   {{ $user->creative->website }}</span></a>
                                                   @endif
                                                   @if(isset($user->studio) && !empty($user->studio))
                                                   <a class="dropdown-item" href="#">
                                                   <img src="assets/images/company.svg"> <span>
                                                   {{ $user->studio->address }}</span></a>
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
                                       class="btn btn-lg btn-primary btn-login message mt-2 add-connection-btn" data-id="{{$user->id}}" data-url="{{URL::to('/')}}" data-type="{{$user->type}}">Add
                                    Connection</button>
                                 </div>
                              </div>
                              @elseif($user->connection == config('constants.Pending'))
									  <div class="ml-auto">
										<div class="pb-2 mt-4 mt-r0">
											<button type="button"
										   		class="btn btn-lg btn-primary btn-login message mt-2 add-connection-btn">Pending
											</button>
                                 		</div>
                              		</div>
                              @else
                              <div class="ml-auto">
                                 <div class="pb-2 mt-4  mt-r0">
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