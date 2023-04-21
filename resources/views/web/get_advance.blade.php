   @extends('webuserlayout.profile_design')
      @section('content')
      <form action="{{url('/advance')}}" id="advance_form"  method="post">{{ csrf_field() }}
      <div class="container">
         <div class="row">
            <div class="col-md-12 mt-5 pt-3">
               <div class="float-left"> <span><img class="mt-2" src="assets/images/get-in-advance.svg"></span> </div>
               <div class="float-left">
                  <h2 class="ml-2 top-project-heading mt-2 fs-28">Get an Advance</h2>
               </div>
            </div>
            <div class="col-md-12">
               <div class="pro-border-bottom"> </div>
            </div>
         </div>
      </div>
      <div class="container mb-5">
         <div class="row">
            <div class="col-md-12">
               <div class="bg-white rounded-xl shadow-sm  mt-4 get-in-advance">
                  <div class="col-md-12 pt-4 pb-4">
                     <div class="form-group radios">
                        <label for="Language">Select One Option</label>
                        <div class="languages pt-2">
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="Legala" id="Legala"
                                 value="option1">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input  name="legal" value="Legal" id="Legal" type="radio">
                                    <label class="form-check-label" for="Legal">Legal</label>
                                 </div>
                              </div>
                           </div>
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="Legala" id="Legala"
                                 value="option2" checked="checked">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input name="legal"  value="distribution" id="Legal1" type="radio">
                                    <label class="form-check-label" for="Legal1">Distribution</label>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div id="legal_error"></div>
                     </div>
                     <hr>
                     <div class="form-group radios">
                        <label for="Language">The sole decision maker in the performing act.</label>
                        <div class="languages pt-2">
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="gridRadios7" id="gridRadios7"
                                 value="option1">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input  name="is_decisionmaker" value="1" id="radio7" type="radio">
                                    <label class="form-check-label" for="radio7">I Am</label>
                                 </div>
                              </div>
                           </div>
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="gridRadios7" id="gridRadios7"
                                 value="option2" checked="checked">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input  name="is_decisionmaker" value="2" id="radio8" type="radio">
                                    <label class="form-check-label" for="radio8">I Am Not</label>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <hr>
                     <div class="form-group radios">
                        <label for="Language">I have a primary source of income that is not from the music industry.
                        </label>
                        <div class="languages pt-2">
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="gridRadios9" id="gridRadios9"
                                 value="option1">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input  name="is_musicprimary" value="1" id="radio9" type="radio">
                                    <label class="form-check-label" for="radio9">Yes</label>
                                 </div>
                              </div>
                           </div>
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="gridRadios9" id="gridRadios9"
                                 value="option2" checked="checked">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input  name="is_musicprimary" value="2" id="radio10" type="radio">
                                    <label class="form-check-label" for="radio10">No</label>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <hr>
                     <div class="form-group radios">
                        <label for="Language">My primary source of income comes from the entertainment industry, but
                        not from creating or performing music. </label>
                        <div class="languages pt-2">
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="gridRadios6" id="gridRadios6"
                                 value="option1">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input  name="is_entertainmentprimary" value="1" id="radio1" type="radio">
                                    <label class="form-check-label" for="radio1">Yes</label>
                                 </div>
                              </div>
                           </div>
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="gridRadios6" id="gridRadios6"
                                 value="option2" checked="checked">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input  name="is_entertainmentprimary" value="2" id="radio2" type="radio">
                                    <label class="form-check-label" for="radio2">No</label>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <hr>
                     <div class="form-group radios">
                        <label for="Language">Do you receive at minimum $5000 in royalties? For how long? </label>
                        <div class="languages pt-2">
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="gridRadios7" id="gridRadios7"
                                 value="option1">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input  name="amount_period" value="0-1 year" id="gridRadios1" type="radio">
                                    <label class="form-check-label" for="gridRadios1">0-1 year</label>
                                 </div>
                              </div>
                           </div>
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="gridRadios7" id="gridRadios7"
                                 value="option2" checked="checked">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input  name="amount_period" value="1-2 years" id="gridRadios2" type="radio">
                                    <label class="form-check-label" for="gridRadios2">1-2 years</label>
                                 </div>
                              </div>
                           </div>
                           <div class="form-check d-inline-flex rdio pr-5">
                              <input class="form-check-input" type="radio" name="gridRadios7" id="gridRadios7"
                                 value="option2" checked="checked">
                              <div class="radiobuttons">
                                 <div class="rdio rdio-primary radio-inline">
                                    <input  name="amount_period" value="3+ years" id="gridRadios3" type="radio">
                                    <label class="form-check-label" for="gridRadios3">3+ years</label>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <hr>
                     <div class="form-group ">
                        <div class="year"><label>I have no prior or current source of income, other than music
                           consecutively for the past ____ years. Users can insert an amount. </label>
                        </div>
                        <div class="form-group col-md-4 home form-login pd-right pl-0">
                           <label for="txtYears">Years</label>
                           <input  type="text" name="period" id="txtYears" class="form-control" placeholder="Years">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="d-flex justify-content-center">
                     <div class=" mt-4 pt-4  col-md-12  text-center">
                        <button type="submit" class="btn  btn-primary btn-login  mt-2 pl-5 pr-5">SUBMIT</button>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      </form>
      <script src="http://code.jquery.com/jquery-3.1.1.js"></script>
      <script type="text/javascript" src="{{ asset('assets/js/jquery.validate.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/additional-methods.min.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/getadvance_validation.js') }}"></script>
      @endsection
