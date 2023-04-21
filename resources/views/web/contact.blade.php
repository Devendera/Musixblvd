@extends('webuserlayout.profile_design')

@section('content')
<div class="container">
         <div class="row">
            <div class="col-md-12 mt-5 pt-3">
               <div class="float-left">
                  <span><img class="mt-1" src="assets/images/contactus.svg"></span>
               </div>
               <div class="float-left ml-2">
                  <h2 class="top-project-heading">
                  {{ __('msg.contact')}}
                  </h2>
               </div>
            </div>
            <div class="col-md-12">
               <div class="pro-border-bottom"></div>
            </div>
         </div>
      </div>
      <div class="container mt-3 mb-5">
         <div class="bg-white rounded-xl shadow-sm form-home form-login form-register mt-4 mb-5">
            <div class="col-sm-12 pt-4">
               <div class="row">
                  <div class="col-lg-6">
                     <div class="contact_us_txt">
                        <h2>{{ __('msg.getintouch')}}</h2>
                        <h3>{{ __('msg.contactpagemessage')}}</h3>
                        <a href="mailto:mohit@ficode.in">
                        <span>
                        <img src="assets/images/f-mail.png" alt="mail icon">
                        </span>
                        demo@xyz.com                        </a>
                        <p>
                           <span>
                           <img src="assets/images/location-con.png" alt="location icon">
                           </span>
                           {{ __('msg.address')}}                       
                        </p>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <form class="contact_us_form" method="post" action= "{{ url('/saveContact') }}" enctype="multipart/form-data" id="contactForm">@csrf
                                                            
                        <h2>{{ __('msg.question')}}  </h2>
                        <h3>{{ __('msg.leavemessage')}}  </h3>
                        <div class="row">
                           <div class="col-6 mb-4 relative">
                              <input type="hidden" value="" name="mailTo">
                              <input type="text" name="name" id="contactName" class="form-control" placeholder="{{ __('msg.name')}}">
                           </div>
                           <div class="col-6 mb-4 relative">
                              <input type="email" id="contactEmail" name="email" class="form-control" placeholder="{{ __('msg.email')}}">
                           </div>
                           <div class="col-12 mb-4">
                              <input type="text" name="phone" id="contactPhone" class="form-control" placeholder="{{ __('msg.phonenumber')}}">
                           </div>
                           <div class="col-12 mb-4">
                              <textarea class="form-control" id="contactMessage" name="message" placeholder="{{ __('msg.writeyourmessage')}}" rows="4"></textarea>
                           </div>
                           <div class="col-12">
                              <button class="btn btn-lg btn-primary  shadow-sm fs-16" type="submit">{{ __('msg.sendmessage')}}</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
@endsection