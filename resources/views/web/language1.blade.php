@extends('webuserlayout.profile_design')
@section('content')

@endsection<div class="container">
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
                                    <form method="post" action="{{ url('/update-language') }}" id="language-form" enctype="multipart/form-data">{{ csrf_field() }}
                                       <fieldset class="form-group radios">
                                          <label for="Language">Language </label>
                                          <div class="languages pt-1">
                                             <div class="form-check d-inline-flex rdio pr-5">
                                                <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios5" value="option1">
                                                <div class="radiobuttons">
                                                   <div class="rdio rdio-primary radio-inline">
                                                      <input name="radio" value="1" id="radio1" type="radio">
                                                      <label class="form-check-label" for="radio1">Auto</label>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="form-check d-inline-flex rdio pr-5">
                                                <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios6" value="option2" checked="checked">
                                                <div class="radiobuttons">
                                                   <div class="rdio rdio-primary radio-inline">
                                                      <input name="radio" value="en" id="radio2" type="radio">
                                                      <label class="form-check-label" for="radio2">English</label>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="form-check d-inline-flex rdio pr-5">
                                                <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios6" value="option3" checked="checked">
                                                <div class="radiobuttons">
                                                   <div class="rdio rdio-primary radio-inline">
                                                      <input name="radio" value="es" id="radio3" type="radio">
                                                      <label class="form-check-label" for="radio3">Spanish</label>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="form-check d-inline-flex rdio pr-5">
                                                <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios6" value="option4" checked="checked">
                                                <div class="radiobuttons">
                                                   <div class="rdio rdio-primary radio-inline">
                                                      <input name="radio" value="fr" id="radio4" type="radio">
                                                      <label class="form-check-label" for="radio4">French</label>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="form-check d-inline-flex rdio pr-5">
                                                <input class="form-check-input" type="radio" name="gridRadios" id="gridRadios6" value="option5" checked="checked">
                                                <div class="radiobuttons">
                                                   <div class="rdio rdio-primary radio-inline">
                                                      <input name="radio" value="zh" id="radio5" type="radio">
                                                      <label class="form-check-label" for="radio5">Chinese</label>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>

                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2 mt-4 d-flex mt-r0">
                           <div class="width-100 justify-content-end d-flex align-items-end align-content flex-end">
                              <div class="pb-3">
                                 <button type="submit" class="btn btn-lg btn-primary btn-login mt-2"> Apply </button>
                              </div>
                           </div>
                        </div>
                     </div>
                     </fieldset>
</form>
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
                                       <div class="row">
                                          <div class="form-group col-md-4 home pd-right">
                                             <label for="First Name">Old Password</label>
                                             <input type="text" class="form-control" placeholder="Old Password">
                                          </div>
                                          <div class="form-group col-md-4 home pd-right">
                                             <label for="Type">New Password</label>
                                             <input type="text" class="form-control" placeholder="New Password"/>
                                          </div>
                                          <div class="form-group col-md-4 home pd-right">
                                             <label for="Type">Confirm New Password</label>
                                             <input type="text" class="form-control" placeholder="Confirm New Password">
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="col-md-2 mt-4 d-flex mt-r0">
                           <div class=" width-100 justify-content-end d-flex align-items-end align-content flex-end">
                              <div class="pb-3">
                                 <button type="submit" class="btn btn-lg btn-primary btn-login mt-2">
                                 Submit
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
