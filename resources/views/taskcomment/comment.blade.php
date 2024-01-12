 <div class="d-flex align-items-start mt-3">
     <img class="me-2 rounded-circle" src="/assets/images/users/avatar-5.jpg" alt="Generic placeholder image"
         height="32">
     <div class="flex-1">
         <h5 class="mt-0">{{ $taskComment->user->name }}

             <small class="text-muted fw-normal float-end">{{ $taskComment->created_at->diffForHumans() }}</small>
         </h5>
         {{ $taskComment->content }}

         <br />

         @if ($taskComment->can_reply == false)
             <a href="javascript:void(0);" class="text-muted font-13 d-inline-block mt-2"
                 onclick="setReplyId({{ $taskComment->id }}, '{{ $taskComment->user->name }}');">
                 <i class="mdi mdi-reply"></i> Reply
             </a>
         @endif
         <div id="child_{{ $taskComment->id }}"></div>
     </div>
 </div>
