@extends('layouts.agentLayout.agent_design',['x' => $agentDetails,'message' => $agentMessage])
@section('content') 
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row ">
          <div class="col-sm-12">
            <h4 class="m-0">Underwriting</h4>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="card saearch-wrap">
          <div class="row mx-0">
          <div class="col-sm-6 pl-0">
            <span class="ic-search"><i class="fas fa-search"></i></span>
            <input class="form-control col-sm-6" type="search" placeholder="Search" style="padding-left: 32px;" aria-label="Search">
          </div>
       <div class="col-sm-6 float-right px-0">
            <a  class="btn btn-block btn-blue float-right w-auto btn-lg" href ="{{ url('agent/save-Underwriting')}}">Start New Underwriting</a>
          </div>
          </div>
        </div>

        <div class="row mx-0 undertablewrap">
            <div class="col-md-4 dt-table pl-0">
              <div class="card p-0">
                  <div class="card-header border-0 bg-light-gray">
                    <h4 class="m-0">Name</h4>
                  </div>
                  <div class="card-body">
                    <div class="row m-0">
					@foreach($data as $data1)
                      <div class="col-sm-12 p-0 name-txt pb-3 border-bottom">
                        <div class="left-info">
                      <span class="name-tag">PPS</span>
                      <p><button type="button" class="view btn btn-blue" name="showuserdata" id="{{$data1->id}}" value="{{$data1->request_id}}" >{{$data1->first_name}}{{$data1->last_name}}</button> <span>{{$data1->request_id}}</span></p>
                      </div>
                      <a href=""><img src="dist/img/Waste.png"></a>
                      </div>
                    @endforeach
                      
                      
                    
                    
                    
                    </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 pr-0 personalouter">
                <div class="accordion" id="accordionExample">
                  <div class="card border p-0">
                    <div class="card-header border-0 bg-light-gray" id="headingOne">
                      <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left p-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                          <p class="mb-0">{{$data1->first_name}}{{$data1->last_name}}  <span class="ml-5">Request Id: {{$data1->request_id}}</span></p>
						  <i class="fa fa-minus" aria-hidden="true"></i>
						  <i class="fa fa-plus"></i>
                        </button>
                      </h2>
                    </div>
                @include('agent/underwriting/showsearchunderwritingdata')
                 
                  </div>
                </div>
				
							<div class="col-smwrap">
									<div class="matchdetail table-responsive">
									<h4>Matched Results</h4>
									<div class="table-responsive">
									<div class="card">
									  <table class="table mb-0 bg-white">
										<thead class="thead-light">
										  <tr>
											<th>Company</th>
											<th>Product</th>
											<th>Product Details</th>
											<th>Quote</th>
											<th>E-App</th>
											<th>Contact</th>
										  </tr>
										</thead>
										<tbody>
											<tr>
											  <td>Aegon Life Insurance Com...</td>
											  <td>Retirement Plan</td>
											  <td>The product is very useful for the old ...</td>
											  <td><a href="#">Copy URL</a></td>
											  <td><a href="#">Copy URL</a></td>
											  <td>Parkash Rawat +91 9876543210 p.rawat@aegonlic...</td>
											</tr>	
											<tr>
											  <td>Aegon Life Insurance Com...</td>
											  <td>Retirement Plan</td>
											  <td>The product is very useful for the old ...</td>
											  <td><a href="#">Copy URL</a></td>
											  <td><a href="#">Copy URL</a></td>
											  <td>Parkash Rawat +91 9876543210 p.rawat@aegonlic...</td>
											</tr>	
											<tr>
											  <td>Aegon Life Insurance Com...</td>
											  <td>Retirement Plan</td>
											  <td>The product is very useful for the old ...</td>
											  <td><a href="#">Copy URL</a></td>
											  <td><a href="#">Copy URL</a></td>
											  <td>Parkash Rawat +91 9876543210 p.rawat@aegonlic...</td>
											</tr>	
											<tr>
											  <td>Aegon Life Insurance Com...</td>
											  <td>Retirement Plan</td>
											  <td>The product is very useful for the old ...</td>
											  <td><a href="#">Copy URL</a></td>
											  <td><a href="#">Copy URL</a></td>
											  <td>Parkash Rawat +91 9876543210 p.rawat@aegonlic...</td>
											</tr>												
										  </tbody>
									  </table>								
								</div>
								</div>
								</div>
							
							
							</div>				
				
				
				
            </div>  
          </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>

  <script>
	$(document).ready(function(){
	$(document).on('click', '.view', function(){
		var id=$(this).val();
		alert(id);
		
		$.ajax({    
		          url: '{!!url("agent/showTable")!!}',
		            data: {'id': id},
			success:function(datas)
			{
			
	$('#collapseOne').show;
			$('#collapseOne').html(datas);

		   }
		        });
 
		
	});
});
	</script>
	

  @endsection