
@extends('webuserlayout.profile_design')

@section('content')
<div class="container">
         <div class="row">
            <div class="col-md-12 mt-5 pt-3">
               <div class="float-left">
                  <span><img class="mt-2" src="assets/images/setting.svg" /></span>
               </div>
               <div class="float-left">
                  <h2 class="ml-2 top-project-heading mt-2">Settings</h2>
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
               <div class="bg-white rounded-xl shadow-sm form-home form-login form-register mt-4">
                  <div class="col-md-12">
                     <div class="row pl-1 pr-1">
                        <div class="col-md-12">
                           <div class="d-flex justify-content-start pt-4">
                              <div class="card-text">
                                 <h4 class="setting-name">Account</h4>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row pl-1 pr-1 pb-2">
                        <div class="col-md-10">
                           <div class="d-flex justify-content-start">
                              <div class="setting width-100 d-inline-flex">
                                 <div class="card-text">
                                    <div class="mt-4">
                                       <h5>Update Language</h5>
                                    </div>
                                    <div class="mt-2">
                                       <p>Click on edit button to Update Language</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2 mt-4 d-flex mt-r0">
                           <div class=" width-100 justify-content-end d-flex align-items-end align-content flex-end">
                              <div class="pb-3">
                                 <button onclick="location.href='{{url('/language')}}'" type="submit" class="btn btn-lg btn-primary btn-login mt-2">
                                 Edit
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
                                       <h5>Update Password</h5>
                                    </div>
                                    <div class="mt-2">
                                       <p>Click on edit button to Update Password</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2 mt-4 d-flex mt-r0">
                           <div class="width-100 justify-content-end d-flex align-items-end align-content flex-end">
                              <div class="pb-3">
                                 <button type="submit" class="btn btn-lg btn-primary btn-login mt-2">
                                 Edit
                                 </button>
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