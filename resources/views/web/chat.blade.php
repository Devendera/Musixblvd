
                     <div class="conversation-container">
                     @if($flag != 0)
                                  @foreach($chat as $chats)
                     <div class="chat-date mt-3">
                           <span class="time-chat">{{$chats->created_at}}</span>
                        </div>
                        <div class="mt-4">
                             @if($chats->from_user == $userId)
                           <div class="message-right">
                              <div class="float-right w-100">
                                 <div class="float-left msgbox-left-91">
                                    <span class="messages sent">
                                    {{$chats->content}}
                                    <span class="metadata">
                                    <span class="time">{{$chats->created_at}}</span>
                                    </span>
                                    </span>
                                 </div>
                                 <div class="float-right">
                                    <span>
                                       <div class="d-flex justify-content-start">
                                          <div class="chat-user d-inline-flex">
                                             <img src="{{ url( Auth::guard('web')->user()->img) }}" />
                                          </div>
                                       </div>
                                    </span>
                                 </div>
                              </div>
                           </div>
                           @else
						        <div class="message-left">
                              <div class="float-left w-100">
                                 <div class="float-left">
                                    <div class="d-flex justify-content-start">
                                       <div class="chat-user d-inline-flex">
                                          <img class="sender_img" id="sender_img" src="">
                                       </div>
                                    </div>
                                 </div>
                                 <div class="float-right msgbox-left-91">
                                    <div class="messages received">
                                    {{$chats->content}}
                                       <span class="metadata"><span class="time">{{$chats->created_at}}</span></span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           @endif
						   @endforeach
                        @endif
                        </div>
                     