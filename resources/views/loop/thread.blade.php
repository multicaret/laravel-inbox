<a href="{{ route(config('inbox.route.name') . 'inbox.show', $thread) }}"
   class="list-group-item {{ !$thread->isUnread() ? 'read' : '' }}">
	<div class="checkbox d-inline-block">
		<label>
			<input type="checkbox">
		</label>
	</div>

	@if($thread->isUnread())
		<span class="badge badge-success">New</span>
	@endif

	<span class="d-inline-block mr-5">{{ $thread->user->username }}</span>
	<span class="badge badge-danger">{{ $thread->messages->count() }}</span>
	<span>{{ $thread->subject }}</span>
	<small class="text-muted">- {{ str_limit($thread->lastMessage()->body, 50) }}</small>
	<span class="float-right badge badge-secondary ml-2">{{ $thread->updated_at->diffForHumans() }}</span>
	{{--<a href="{{ route('inbox.destroy', $thread->id) }}"><span class="fa fa-trash"></span></a>--}}
	{{--<span class="float-right fa fa-paperclip"></span>--}}

	<form action="{{ route(config('inbox.route.name') . 'inbox.destroy', $thread) }}" method="post"
	      class="d-inline-block float-right">
		@csrf
		@method('delete')
		<button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
	</form>
</a>
