@extends('webuserlayout.user_design')

@section('content')

	<div class="home-page  mb-5">
	<div class="d-flex px-3 py-3 pt-md-5 pb-md-4 text-center">
		<div class="col-md-6 col-sm-9 mx-auto">
			<h1 class="heading mt-2"><span class="connect"><strong>Connect</strong></span> <strong>to the World's Top Music Artist & Managers </strong></h1>
		</div>
	</div>
	<div class="col-sm-9 mx-auto">
		<p class="sub-heading text-center"> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
	</div>
	
	<div class="container">
		<div class="row mt-4 pt-2 mb-2">   
			<div class="col-md-12">
				<div class="input-group">
					<div class="filter shadow-sm">
						<a href="#"><img src="assets/images/filter.png" alt="filter"></a>
					</div>
					<input type="text" class="form-control border-0 rounded-xl shadow-sm search-content" placeholder="Search Creative, Manager, Studio...">
					<span class="input-group-append bg-white has-search">
						<span class="search form-control-feedback explore-search">
							<img src="assets/images/search.png" alt="Search">
						</span>
					</span>
					<button class="btn btn-lg btn-primary search-btn shadow-sm" type="button"> Search</button>
				</div>
			</div>
		</div>
		
		<div class="bg-white rounded-xl pt-4 pb-1 shadow-sm form-home">
			<form>
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="d-flex">
								<div class="ml-auto">
									<a class="filter-clear" href="#">Clear Filter</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-md-3 home pd-right">
							<label for="type">Type</label>
							<select class="form-control">
								<option>Select Type</option>
							</select>
						</div>
						
						
						<div class="form-group col-md-3 home pd-right">
							<label for="Artistry">Artistry</label>
							<select class="form-control">
								<option>Select Artistry</option>
							</select>
						</div>
						
						
						<div class="form-group col-md-3 home pd-right">
							<label for="Genre">Genre</label>
							<select class="form-control">
								<option>Select Genre</option>
								<option>Apple Music</option>
								<option>Spotify</option>
								<option>Google Play</option>
								<option>Tidal</option>
								<option>Amazon</option>
								<option>Pandora</option>
								<option>Deezer</option>
								<option>Youtube</option>					
							</select>
						</div>
						
						
						<div class="form-group col-md-3 home">
							<label for="Gender">Gender</label>
							<select class="form-control">
								<option>Select Gender</option>
							</select>
						</div>	
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="row">
						<div class="form-group col-md-3 home pd-right">
							<label for="connections">Connections</label>
							<select class="form-control">
								<option>Select Connections</option>
							</select>
						</div>
						
						
						<div class="form-group col-md-3 home pd-right">
							<label for="projects">Projects</label>
							<select class="form-control">
								<option>Select Projects</option>
							</select>
						</div>
						
						
						<div class="form-group col-md-3 home pd-right">
							<label for="streams">Streams</label>
							<select class="form-control">
								<option>Select Streams</option>		  
								<option>Alternative</option>
								<option>Blues</option>
								<option>Classical</option>
								<option>Country</option>
								<option>Dance</option>
								<option>Easy Listening</option>
								<option>Electronic</option>
								<option>Hip Hop/Rap</option>
								<option>Indie</option>
								<option>Gospel</option>
								<option>Jazz</option>
								<option>Latin</option>
								<option>New Age</option>
								<option>Opera</option>
								<option>Pop</option>
								<option>R&amp;B/Soul</option>
								<option>Reggae</option>
								<option>Rock</option>
								<option>Beats</option>
							</select>
						</div>
						
						
						<div class="form-group col-md-3 home">
							<label for="country">Country</label>
							<select class="form-control">
								<option>Select Country</option>
							</select>
						</div>	
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="row">
						<div class="form-group col-md-3 home pd-right">
							<label for="state">State</label>
							<select class="form-control">
								<option>Select State</option>
							</select>
						</div>
						<div class="form-group col-md-3 pd-right">
							<label for="city">City</label>
							<input type="text" class="form-control" id="city" placeholder="City">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	</div>
    @endsection


