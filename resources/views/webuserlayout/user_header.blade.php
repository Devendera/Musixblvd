<header class="bg-white border-bottom shadow-sm fixed-top">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<nav class="navbar navbar-expand-lg navbar-light my-2 my-md-0 d-flex">
						<a class="navbar-brand my-0 font-weight-normal" href="{{ url('/user-index') }}">
							<img src="{{asset('assets/images/logo.png')}}" width="80" height="80" alt="logo">
						</a>
						<a class="ml-md-auto ml-sm-auto mr-sm-2 mr-md-2 login-link order-lg-2" href="{{ url('/login') }}">log in</a>
								<!--<a class="ml-md-3 ml-sm-3 btn btn-lg btn-primary signup-btn order-lg-2" href="register.html">Sign up</a> -->
                            <a href="{{ url('/register') }}" class="ml-md-3 ml-sm-3 btn btn-lg btn-primary signup-btn order-lg-2">Sign up</a>
						<button class="navbar-toggler order-lg-3 ml-sm-3" type="button" data-toggle="collapse" data-target="#navbar-list-2" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						<div class="collapse navbar-collapse order-lg-1" id="navbar-list-2">
							<ul class="navbar-nav mr-auto">
								<li class="nav-item {{ request()->is('user-index') ? 'active' : '' }}">
									<a class="nav-link" href="{{url('/user-index')}}">Home <span class="sr-only">(current)</span></a>
								</li>
								<li class="nav-item {{ request()->is('addproject') ? 'active' : '' }}">
									<a class="nav-link" href="{{url('/addproject')}}">projects</a>
								</li>
								<li class="nav-item {{ request()->is('managers') ? 'active' : '' }}">
									<a class="nav-link" href="{{url('/managers')}}">managers</a>
								</li>
								<li class="nav-item {{ request()->is('studios') ? 'active' : '' }}">
									<a class="nav-link" href="{{url('/studios')}}">studios</a>
								</li>
								<li class="nav-item {{ request()->is('studioMapView') ? 'active' : '' }}">
									<a class="nav-link" href="{{url('/studioMapView')}}">studios map</a>
								</li>
								<li class="nav-item {{ request()->is('connected-users') ? 'active' : '' }}">
									<a class="nav-link" href="{{url('/connected-users')}}">connections</a>
								</li>
								<li class="nav-item {{ request()->is('explore') ? 'active' : '' }}">
									<a class="nav-link" href="{{url('/explore')}}">explore</a>
								</li>
								<li class="nav-item {{ request()->is('faq') ? 'active' : '' }}">
										<a class="nav-link" href="{{url('/faq')}}">FAQ</a>
								</li>
								<li class="nav-item {{ request()->is('contact') ? 'active' : '' }}">
									<a class="nav-link" href="{{url('/contact')}}">contact us</a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</div>
		</div>
	</header>