@foreach ($taskComments as $taskComment)
    <div class="d-flex align-items-start mt-3">
        <img class="me-2 rounded-circle" src="/assets/images/users/avatar-5.jpg" alt="Generic placeholder image"
            height="32">
        <div class="flex-1">
            <h5 class="mt-0">{{ $taskComment->user->name }}

                <small class="text-muted fw-normal float-end">{{ $taskComment->created_at->diffForHumans() }}</small>
            </h5>
            {{ $taskComment->content }}

            <br />
            <div id="child_{{ $taskComment->id }}"></div>
        </div>
    </div>
@endforeach
