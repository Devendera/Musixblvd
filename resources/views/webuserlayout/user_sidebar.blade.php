<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-light-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.html" class="brand-link d-inline-block">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image" style="opacity: 1">
      <img src="dist/img/smalllogo.png" alt="AdminLTE Logo" class="brand-image samll" style="opacity: 1">
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ url('/agent/view')}}" class="nav-link  {{ (request()->is('agent/view')) ? 'nav-link active' : '' }}">
              <i class="nav-icon far fa-address-card"></i>
              <p>
                Digital Business Card
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ url('agent/show-calender')}}" class="nav-link {{ (request()->is('agent/show-calender')) ? 'nav-link active' : '' }}">
              <i class="nav-icon far fa-calendar"></i>
              <p>
                Calendar
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{url('agent/show-calender')}}" class="nav-link">
              <i class="nav-icon far fa-user"></i>
              <p>
                Underwriting
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="groupchat.html" class="nav-link">
              <i class="nav-icon far fa-comment-alt"></i>
              <p>
                Group Chat
              </p>
            </a>
          </li>
          
          
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-building"></i>
              <p>
                Agency
                <i class="fas fa-angle-right right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="blog.html" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Blog</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/icons.html" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Carrier</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/buttons.html" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Videos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/sliders.html" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>Scripts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/modals.html" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>FFL Playbook</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/UI/navbar.html" class="nav-link">
                  <i class="fas fa-angle-right nav-icon"></i>
                  <p>About Us</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>