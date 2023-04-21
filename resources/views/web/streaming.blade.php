@extends('webuserlayout.profile_design')

@section('content')
<div class="home-page mb-5">
<div class="container">
         <div class="row">
            <div class="col-md-12 mt-5 pt-3">
               <div class="float-left">
                  <span><img class="mt-2" src="assets/images/streaming.svg" /></span>
               </div>
               <div class="float-left">
                  <h2 class="ml-2 top-project-heading">Streaming Services</h2>
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
               <div class="bg-white rounded-xl shadow-sm page-button mt-4">
                  <div class="col-md-12">
                     <div class="row pl-1 pr-1 pb-2">
                        <div class="col-md-10">
                           <div class="d-flex justify-content-start">
                              <div class="setting width-100 d-inline-flex">
                                 <div class="card-text">
                                    <div class="mt-4">
                                       <h5>Connect Youtube</h5>
                                    </div>
                                    <div class="mt-2">
                                       <p>Click on the Button to Connect the Service</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2 mt-4 d-flex mt-r0">
                           <div class="width-100 justify-content-end d-flex align-items-end align-content flex-end">
                              <div class="pb-3">
                                 <button type="submit" class="btn btn-lg btn-primary btn-login mt-2"  data-toggle="modal" data-target="#youtubeModal">
                                 Connect
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="bordr-botm"></div>
                     <div class="row pl-1 pr-1 pb-2">
                        <div class="col-md-10">
                           <div class="d-flex justify-content-start">
                              <div class="setting width-100">
                                 <div class="card-text">
                                    <div class="mt-4">
                                       <h5>Connect SoundCloud</h5>
                                    </div>
                                    <div class="mt-2">
                                       <p>Click on the Button to Connect the Service</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2 mt-4 d-flex mt-r0">
                           <div class="width-100 justify-content-end d-flex align-items-end align-content flex-end">
                              <div class="pb-3">
                                 <button onclick="location.href='#'" type="submit" class="btn btn-lg btn-primary btn-login mt-2">
                                 Connect
                                 </button>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="bordr-botm"></div>
                     <div class="row pl-1 pr-1 pb-2">
                        <div class="col-md-10">
                           <div class="d-flex justify-content-start">
                              <div class="setting width-100 d-inline-flex">
                                 <div class="card-text">
                                    <div class="mt-4">
                                       <h5>Connect Spotify</h5>
                                    </div>
                                    <div class="mt-2">
                                       <p>Click on the Button to Connect the Service</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2 mt-4 d-flex mt-r0">
                           <div class="width-100 justify-content-end d-flex align-items-end align-content flex-end">
                           <button type="submit" class="btn btn-lg btn-primary btn-login mt-2"  data-toggle="modal" data-target="#spotifyModal">Connect</button>
                              <div class="pb-3"></div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
</div>     
@endsection