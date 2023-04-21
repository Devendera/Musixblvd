@extends('webuserlayout.profile_design')
@section('content')
<div class="content">
      <div class="container">
         <div class="row">
            <div class="col-md-12 mt-5 pt-3 d-flex">
               <div class="float-left"> <span><img src="assets/images/faq.svg"></span> </div>
               <div class="float-left">
                  <h2 class="ml-2 top-project-heading">Frequently Asked Questions</h2>
               </div>
            </div>
            <div class="col-md-12">
               <div class="pro-border-bottom"> </div>
            </div>
         </div>
      </div>
      <div class="container">
         <div class="row">
            <div class="col-md-12">
               <div class="bg-white rounded-xl shadow-sm form-home form-login form-register mt-4">
                  <div class="col-md-12">
                     <div class="row pl-1 pr-1 pb-2">
                        <div id="accordion" class="width-100">
                           <div class="card">
                              <div class="card-header" id="headingOne">
                                 <h5 class="mb-0">
                                    <a class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                       aria-expanded="true" aria-controls="collapseOne">
                                    What is music licensing?
                                    </a>
                                 </h5>
                              </div>
                              <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                 data-parent="#accordion">
                                 <div class="card-body">
                                    Music licensing is the licensed use of copyrighted music. Music licensing is
                                    intended to ensure that the owner of the copyright on musical works are
                                    compensated for certain uses of their works. A purchaser has limited rights
                                    to use the work without a separate agreement.
                                 </div>
                              </div>
                           </div>
                           <div class="card">
                              <div class="card-header" id="headingTwo">
                                 <h5 class="mb-0">
                                    <a class="btn btn-link collapsed" data-toggle="collapse"
                                       data-target="#collapseTwo" aria-expanded="false"
                                       aria-controls="collapseTwo">
                                    What is a music publisher?
                                    </a>
                                 </h5>
                              </div>
                              <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                 data-parent="#accordion">
                                 <div class="card-body">
                                    A music publisher (or publishing company) is responsible for ensuring the
                                    songwriters and composers receive payment when their compositions are used
                                    commercially.Through an agreement called a publishing contract, a songwriter
                                    or composer assigns the copyright of their composition to a publishing
                                    company, In return, the company licenses compositions, helps monitor where
                                    compositions are used, collects royalties and distributes them to the
                                    composers. They also secure commissions for music and promote existing
                                    compositions to recording artists, film and television.
                                 </div>
                              </div>
                           </div>
                           <div class="card">
                              <div class="card-header" id="headingThree">
                                 <h5 class="mb-0">
                                    <a class="btn btn-link collapsed" data-toggle="collapse"
                                       data-target="#collapseThree" aria-expanded="false"
                                       aria-controls="collapseThree">
                                    What is music copyright?
                                    </a>
                                 </h5>
                              </div>
                              <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                 data-parent="#accordion">
                                 <div class="card-body">
                                    Copyright signifies the ownership of an intellectual property of a person or
                                    group. A registration for a musical composition covers the music and lyrics
                                    (if any) embodied in that composition. But it does not cover a recorded
                                    performance of that composition. Likewise a registration for a sound
                                    recording of a performance does not cover the underlying musical
                                    composition.
                                 </div>
                              </div>
                           </div>
                           <div class="card">
                              <div class="card-header" id="headingfour">
                                 <h5 class="mb-0">
                                    <a class="btn btn-link collapsed" data-toggle="collapse"
                                       data-target="#collapsefour" aria-expanded="false"
                                       aria-controls="collapsefour">
                                    What is a performing rights organization?(PRO)
                                    </a>
                                 </h5>
                              </div>
                              <div id="collapsefour" class="collapse" aria-labelledby="headingfour"
                                 data-parent="#accordion">
                                 <div class="card-body">
                                    A performing rights organization(PRO) is an agency that ensures songwriters
                                    and publishers are paid for the use of their music by collecting royalties
                                    on behalf of the rights owner. PROs collect public performance royalties .
                                    when a song is played in public, like on any kind of radio(AM/FM, streaming,
                                    or satellite), in a venue, or TV shows and commercials, it is required that
                                    they pay for the use. The PRO collects those payments and distributes them
                                    to the rights holder.
                                 </div>
                              </div>
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