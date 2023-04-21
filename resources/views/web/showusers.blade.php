@if(isset($connectedUsers))
							@foreach($connectedUsers as $k => $data)
                     <?php $userMessage = \App\Message::latest('id')->where(['from_user' => $data->connectedUser->id])->orwhere(['to_user' => $data->connectedUser->id])->first();?>
                     <?php $unreadMessage = \App\Message::where(['from_user' => $data->connectedUser->id])->where(['read_flag' => 0])->get();
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
if ($data->connectedUser->type == "Studio") {
    if (!empty($data->connectedUser->img)) {
        $arr = explode("/", $data->connectedUser->img);
        $img = end($arr);
        ?>
                                    <img class="profile-pic" id="pic" src="{{ url('public/studio/'.$img) }}" alt="Profile Picture">
                                 <?php } else {?>
                                    <img class="profile-pic" id="pic" src="{{ url($img) }}" alt="Profile Picture">
                                 <?php }} else {?>
                                     <img class="profile-pic"  id="pic" src="{{ $data->connectedUser->img !='' ? $data->connectedUser->img : $img }}" alt="Profile Picture">
                                 <?php }?>

                                    </span>
                                    <span class="msg-name-wrap mt-1 ml-2">
                                       <p class="noti-profile-name">
                                       <button type="button" class="text-capitalize view btn btn-blue py-0 px-0" name="showuserdata" id="showuserdata" value=" {{$data->connectedUser->id}}"> {{$data->connectedUser->name}}</button>                                       
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
                           </li>
                           @endforeach
                           @endif