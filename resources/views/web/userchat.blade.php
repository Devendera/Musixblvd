@extends('webuserlayout.profile_design') @section('content')
<div class="home-page mb-5">
	<div class="container mt-4 mt-r-4">
		<div class="row">
			<section class="left-side mt-5 col-md-4 pd-right d-lg-block d-md-block">
				<div class="bg-white-custom left-sidebar rounded-xl shadow-sm pb-4">
					<div class="col-md-12">
						<div class="col-md-12">
							<h5 class="pb-1 mt-3 profile-manager">
                           Messages <span class="float-right mt-1"><a class="msg-chat" href="#"><img src="assets/images/msg.svg"></a></span>
                        </h5> </div>
						<div class="col-md-12">
							<div class="row pt-2 mb-2">
								<div class="col-md-12">
									<div class="input-group create-pro-search">
										<input type="text" id="searchTheKey" class="form-control search-content rounded-xl" placeholder="Search Messages"> <span class="bg-white has-search">
                                 <span class="search form-control-feedback"><img src="assets/images/search.png" alt="Search"></span> </span>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="pro-border-bottom"></div>
						</div>
						<div class="user-wrap">
							<ul class="msg-user-list mt-3" id="matchKey">
                        @if(isset($connectedUsers)) @foreach($connectedUsers as $k => $data)
								<?php 
                            $userMessage = \App\Message::latest('id')->where(['from_user' => $data->connectedUser->id])->orwhere(['to_user' => $data->connectedUser->id])->first();
                        ?>
                        <?php 
                           $unreadMessage = \App\Message::where(['from_user' => $data->connectedUser->id])->where(['read_flag' => 0])->get();
                           $countMessage = count($unreadMessage);
                           if ($countMessage == 0) {
                             $countMessage = "";
                           }
                        ?>
										<li class="row bckgrund-current-user border-left-blue" id="subjectName">
											<a href="#" class="active">
												<div class="border-list"></div>
												<div class="noti-profile message-profile pading-user-msg">
                                    <span class="msg-profile-pic">
                                    <?php
                                       $img = 'assets/images/marc.jpg';
                                       $userImage = 'assets/images/marc.jpg';
                                       if ($data->connectedUser->type == "Studio") {
                                          if (!empty($data->connectedUser->img)) {
                                             $arr = explode("/", $data->connectedUser->img);
                                             $img = end($arr);
                                    ?>
                                      <img class="profile-pic" id="pic_{{$data->connectedUser->id}}" src="{{ url('public/studio/'.$img) }}" alt="Profile Picture">
                                     <?php $userImage = url('public/studio/'.$img) ?>
                                    <?php } else {?>
                                      <img class="profile-pic" id="pic_{{$data->connectedUser->id}}" src="{{ url($img) }}" alt="Profile Picture">
                                      <?php $userImage = $img; ?>
                                    <?php }} else {?>
                                      <img class="profile-pic" id="pic_{{$data->connectedUser->id}}" src="{{ $data->connectedUser->img !='' ? $data->connectedUser->img : $img }}" alt="Profile Picture">
                                      <?php $userImage = $data->connectedUser->img !='' ? $data->connectedUser->img : $img ?>
                                    <?php } ?>
                                    </span>
                                    <span class="msg-name-wrap mt-1 ml-2">
                                       <p class="noti-profile-name">
                                         <button type="button" class="text-capitalize view btn btn-blue py-0 px-0" name="showuserdata" id="{{ $userImage }}" value=" {{$data->connectedUser->id}}">{{ $data->connectedUser->name }} </button>
                                         <input type="hidden" id="custId" name="custId" value="">
									              <input type="hidden" id="profileuser" name="profile" value="">
                                       </p>
									           @if(isset($userMessage))
                                       <p class="notify">{{$userMessage->content}}</p>
                                    </span>
                                    <span class="msg-time pd-left pd-right">
                                       <p>{{$userMessage->created_at}}</p>
                                    	<ul class="nav edit-notification">
                                          <li class="nav-item dropdown">
                                            <span class="badge badge-primary badge-custom-message">{{$countMessage}}</span>
                                           </li>
							                  </ul>
							              </span>
                                   @endif
                           </div>
							  </a>
							</li> @endforeach @endif </ul>
						</div>
					</div>
				</div>
			</section>
			<section class="right-side mt-5 col-md-8 pl-2 pd-left">
				<div class="bg-white rounded-xl shadow-sm pt-3 form-home form-login form-register">
					<div class="col-md-12">
						<div class="d-flex justify-content-start mt-1">
							<div class="profile-man-img d-inline-flex chat-user-img"> <img id="image_id" src="">
								<div class="ml-1">
									<h6 class="user-chat-current text-capitalize" id="username"></h6>
									<div class="d-flex mt-2"> <span class="user-chat-online"></span> </div>
								</div>
							</div>
						</div>
					</div>
					<div class="pro-border-bottom"></div>
					<div class="conversation" id="conversation"> @include('web/chat') </div>
					<div class="pro-border-bottom"></div>
					<form class="conversation-compose mt-2 pb-3">
						<div class="emoji">
							<a href="#"><img src="assets/images/emoji.svg"></a>
						</div>
						<div class="photo">
							<div class="fil">
								<label class="file-upload" for="fileUpload"><img src="assets/images/file.svg"></label>
								<input class="custom-file-input" type="file" id="fileUpload"> </div>
						</div>
						<input class="input-msg" name="input" id="message" placeholder="Type a message" autocomplete="off">
						<button class="send sendbtn" type="button">
							<div class="circle">
								<a href="#"><img src="assets/images/send.svg"></a>
							</div>
						</button>
					</form>
				</div>
			</section>
		</div>
	</div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script>
   $(document).ready(function() {
      $('#image_id').hide();
   });
  $(document).ready(function() {
      $(document).on('click', '.view', function() {
         var id = $(this).val();
         var custId = $('#custId').val(id);
         var name = $(this).text();
         const profile = $(this).attr('id');
         let that = $(this);
         var picurl = document.getElementById("profileuser").value = profile;
         document.getElementById("image_id").src = picurl;
         $('#image_id').show();
         $('#username').html(name);
         $.ajax({
            url: "get-chat",
            data: {
               'id': id
            },
            success: function(datas) {
               $('#conversation').show;
               $('#conversation').html(datas);
               setInterval(that, 5000);
            }
         });
      });
      $(document).on('click', '.sendbtn', function() {
         var id = $('#custId').val();
         var message = $('#message').val();
         $.ajax({
            url: "save-chat",
            data: {
               'id': id,
               'message': message
            },
            success: function(datas) {
               $('#message').val("");
            }
         });
      });
   });
   /*function fetchCount() {
      var custId = $('#custId').val();
   $.ajax({
      url: "userMessageCount",
      success: function (datas) {
         $('.user-wrap').show;
         $('.user-wrap').html(datas);
      }
   });
   }
   $(document).ready(function () {
   setInterval(fetchCount, 5000);
   });
   */
   function fetchdata() {
      var id = $('#custId').val();
      $.ajax({
         url: "get-chat",
         data: {
            'id': id
         },
         success: function(datas) {
            $('#conversation').show;
            $('#conversation').html(datas);
			var image =  $('#image_id').attr('src');
	        $(".sender_img").attr("src",image);
         }
      });
   }
   $(document).ready(function() {
      setInterval(fetchdata, 1000);
   });
   $("#searchTheKey").on('keyup', function() {
      var value = $(this).val().toLowerCase();
      $("#matchKey li").each(function() {
         if($(this).text().toLowerCase().search(value) > -1) {
            $(this).show();
            $(this).prev('.subjectName').last().show();
         } else {
            $(this).hide();
         }
      });
   })
</script>
@endsection