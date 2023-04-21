@if($connections != "")
<div class="col-md-12">
<div class="d-flex">
<h3 class="noti-Heading">Notification</h3>
<a class="view-activity  text-right w-100 d-block  pt-1 pb-2" href="{{ url('/view-activity')}}">View Activity</a>
</div>
@foreach($connections as $con)
<div class="noti-profile " id="request">
   <div class="pd-left pd-right">
      <span class="msg-profile-pic">
      <img src="{{asset('assets/images/marc.jpg')}}" alt="Profile Picture">
      </span>
      <span class="noti-name-wrap mt-1">
         <p class="noti-profile-name">
            {{$con->name}}
         </p>
         <?php if($con->connection_type=="connection"){ ?>
         <p class="notify">Requested to connect with you</p>
         <?php } elseif($con->connection_type=="management"){ ?>
         <p class="notify">Requested you to add as manager</p>
         <?php } else{ ?>
         <p class="notify">Requested you to add as contributor</p>
         <?php }  ?>
         <p><a class="btn btn-sm btn-primary btn-edit" href="{{ url('/add-connection/'.$con->id)}}">Accept</a> <a class="ml-3 reject-req" href="{{ url('/delete-connection/'.$con->id)}}">Reject</a></p>
      </span>
   </div>
   <span class="noti-time pd-left pd-right">
      <p>10:00 AM</p>
      <ul class="nav edit-notification">
         <li class="nav-item dropdown">
            <a class="user nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
               aria-expanded="false" href="login.html">
               <div class="menu-edit" id="response"></div>
            </a>
            <div class="dropdown-menu edit-notification" aria-labelledby="navbarDropdown">
               <a class="dropdown-item delete-noti ml-1" href="#"><img src="assets/images/delete-edit.svg" alt="delete"><span class="ml-1"> Delete this Notification</span> </a>
               <p class="dropdown-item"><a class="btn btn-sm btn-primary btn-edit pd-mark delete-noti ml-1" href="#"><img src="assets/images/tick.svg" alt="delete"><span class="ml-1"> Mark as Read</span></a></p>
            </div>
         </li>
      </ul>
   </span>
</div>
@endforeach
@endif