@extends('webuserlayout.profile_design')

@section('content')
<div class="home-page mb-5">
<div class="container">
         <div class="row">
            <div class="col-md-12 mt-5 pt-3">
               <div class="float-left">
                  <span><img class="mt-2" src="assets/images/studio.svg"></span>
               </div>
               <div class="float-left ml-2">
                  <h2 class="top-project-heading">
                  {{ __('msg.studio')}}
                     <h6 class="ml-2 total-results">  {{ __('msg.results')}}</h6>
                  </h2>
               </div>
            </div>
            <div class="col-md-12">
               <div class="pro-border-bottom"></div>
            </div>
         </div>
      </div>
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="alert alert-danger" id="ajaxdiv">
                  <strong id="ajaxResponse"></strong>
               </div>
               <div class="bg-white rounded-xl shadow-sm form-home form-login form-register mt-4">
                  <div class="col-md-12 pr-4 pl-4">
                     <ul class="manager-list">
                        @if(isset($studios))
                        @foreach($studios as $k => $data)
                        <li class="bordr-botm">
                           <div class="d-flex justify-content-space-between pt-4 pb-4 connect-user">
                              <div class="manager d-inline-flex">

								  <?php
$img = 'assets/images/marc.jpg';
if ($data->type == "Studio") {
    if (!empty($data->img)) {
        $arr = explode("/", $data->img);
        $img = end($arr);
        ?>
                                    <img class="profile-pic" src="{{ url('public/studio/'.$img) }}" alt="Profile Picture">
                                 <?php } else {?>
                                    <img class="profile-pic" src="{{ $img }}" alt="Profile Picture">
                                 <?php }} else {?>
                                     <img class="profile-pic" src="{{$data->img !='' ? $data->img : $img }}" alt="Profile Picture">
                                 <?php }?>

                                 <div class="online">
                                 <a href="{{ url('/studio-view/'.$data->id)}}"> <h4 class="manager-name">{{$data->name}}</h4></a>
                                    <div class="card-text">
                                       <div class="mt-1">
                                          <span class="profil-name">{{$data->type}}</span>
                                       </div>
                                       <div class="d-flex mt-2">
                                          <span class="dot-connected connectd dot--full"></span> <span
                                             class="connected-status">{{$data->connection}}</span>
                                       </div>
                                       <div class="mt-1">
                                          <div class="manager-client float-left">
                                             <img src="assets/images/clients.svg">
                                             <span class="m-connect"> {{ __('msg.clients')}}: </span>
                                             <span class="m-connect"> {{$data->studio->clients_count}}</span>
                                          </div>
                                          <div class="manager-client ml-2 float-left ml-r0"><img
                                             src="assets/images/connection-list.svg">
                                             <span class="m-connect"> {{ __('msg.connections')}}: </span>
                                             <span class="m-connect"> {{$data->connections_count}}</span>
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
                                                   <span>{{$data->email}}</span></a>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              @if($data->connection == config('constants.NotConnected'))
                              <div class="ml-auto">
                                 <div class="pb-2 mt-4 mt-r0">
                                    <button type="button"
                                          class="btn btn-lg btn-primary btn-login message mt-2 add-connection-btn" data-id="{{$data->id}}" data-url="{{URL::to('/')}}" data-type="{{$data->type}}">Add Connection
                                    </button>
                                 </div>
                              </div>
                              @elseif($data->connection == config('constants.Pending'))
									  <div class="ml-auto">
										<div class="pb-2 mt-4  mt-r0">
											<button type="button"
										   		class="btn btn-lg btn-primary btn-login message mt-2 add-connection-btn">Pending
											</button>
                                 		</div>
                              		</div>
                              @else
                              <div class="ml-auto">
                                 <div class="pb-2 mt-4  mt-r0">
                                    <button type="submit" class="btn btn-lg btn-primary btn-login message mt-2">Message</button>
                                 </div>
                              </div>
                              @endif
                           </div>
                        </li>
                        @endforeach
                         {{ $studios->links() }}
                        @else
                        <li class="bordr-botm">
                           <div class="d-flex justify-content-space-between pt-4 pb-4 connect-user">
                              <span>{{ __('msg.notfoundanystudio')}}</span>
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