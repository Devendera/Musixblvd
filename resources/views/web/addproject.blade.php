@extends('webuserlayout.profile_design')
@section('content')
<div class="home-page mb-5">
<div class="container">
		<div class="row">
			<div class="col-md-12 mt-5 pt-3">
				<div class="float-left">
					<span><img class="mt-2" src="assets/images/add-projct.png"></span>
				</div>
				<div class="float-left">
					<h2 class="ml-2 top-project-heading">{{ __('msg.projects')}}<h6 class="ml-2 total-results">  {{ __('msg.about50results')}}</h6></h2>
				</div>
			</div>
			<div class="col-md-12">
				@if(Session::has('flash_message_success'))
	            <div class="alert alert-success alert-block alert-desktop mt-2 mb-0">
	                <button type="button" class="close" data-dismiss="alert">×</button>
	                <strong>{{ Session::get('flash_message_success') }}</strong>
	            </div>
	         @endif
			   @if(Session::has('flash_message_error'))
	            <div class="alert alert-success alert-block alert-desktop mt-2 mb-0">
	                <button type="button" class="close" data-dismiss="alert">×</button>
	                <strong>{{ Session::get('flash_message_error') }}</strong>
	            </div>
	         @endif
			</div>
		</div>
	</div>
	<div class="container mb-5">
		<div class="row">
			<div class="col-md-4 mt-4 pt-1">
				<a class="project-new-add" href="#">
					<div class="card border-light rounded-xl shadow-sm">
						<div class="p-2">
							<a id="addproject" href="{{url('/createproject')}}">
							<div class="card-body add-project">
								<div class="row align-items-center h-100 height-auto">
									<div class="col-md-12">

										<div class="mx-auto">
											<div class="justify-content-center">
												<div class="d-flex justify-content-center ">
													<div >
														<div class="text-center"><img src="assets/images/add-project.svg"></div>
														<div class="project-create mt-2">{{ __('msg.addproject')}}</div>

													</div>
												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
							</a>
						</div>
					</div>
				</a>
			</div>
			@foreach($projects as $project)
			<a class="" href="{{ url('projectInfo/'.$project->id)}}">	
				<div class="col-md-4 mt-4 pt-1">
					<div class="card border-light rounded-xl shadow-sm">
						<div class="project-view-img p-2">
							<img src="{{ url('public/img/users/'.$project->img) }}" class="card-img-top" alt="...">
						</div>
						<div class="card-body project-title p-3">
							<div class="bordr-botm">
								<h5 class="card-title"><span class="project-name">{{$project->title}}</span>  <span class="float-right">
									<a href="{{ url('delete-project/'.$project->id) }}"  onclick="return confirm('Are you sure to delete?')"><img src="assets/images/delete.svg"></a>
								</span></h5>
							</div>
							<div class="card-text m-1">
								<div class="mt-2">
									<span><img src="assets/images/calander.svg"></span>
									<span class="r-date ml-1">{{ __('msg.releasedate')}} </span> <span class="p-status float-right"> {{$project->release_date}}</span>
								</div>
								<div class="mt-2">
									<span><img src="assets/images/privacy.svg"></span>
									<span class="r-date ml-1">{{ __('msg.privacy')}} </span> <span class="p-status float-right"> {{$project->Privacy}}</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</a>
			@endforeach
		</div>
	</div>
</div>
	@endsection
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
