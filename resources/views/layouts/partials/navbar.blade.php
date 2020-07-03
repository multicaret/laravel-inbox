<nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
	<div class="container-fluid">
		<a class="navbar-brand" href="{{ route(config('inbox.route.name') . 'inbox.index') }}">Multicaret Inbox</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar"
		        aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbar">
			<form class="form-inline">
				<input class="form-control mr-sm-2" type="search" placeholder="Search..">
				<button class="btn btn-outline-info my-2 my-sm-0" type="submit">Search</button>
			</form>

			<ul class="navbar-nav ml-auto">
				@if(in_array('database', config('inbox.notifications.via', [])))
					<li class="nav-item dropdown">
						<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-bell"></i>
							<span class="badge badge-danger">
								{{ auth()->user()->unreadNotifications()->count() }}
							</span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							@foreach(auth()->user()->notifications()->latest()->get() as $notification)
								@php
									$message = \Multicaret\Inbox\Models\Message::find($notification->data['message_id']);
									$thread = \Multicaret\Inbox\Models\Thread::find($notification->data['thread_id']);
								@endphp
								@if($thread && $message)
									<a href="{{ route(config('inbox.route.name') . 'inbox.show', $thread) }}"
									   class="dropdown-item {{ $notification->unread() ? 'bg-secondary' : '' }}"
									   id="notification-{{ $notification->id }}">
										<strong>{{ $message->user->name }}</strong>
										@if($notification->data['isReply'])
											Re:
										@else
											Send you new message:
										@endif
										<i>{{ $thread->subject }}</i>
									</a>
								@endif
								@php($notification->markAsRead())
							@endforeach
						</div>
					</li>
				@endif
				<li class="nav-item dropdown">
					<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
						{{ auth()->user()->name }}
					</a>
					<div class="dropdown-menu dropdown-menu-right">
						<a class="dropdown-item" href="#">Logout</a>
					</div>
				</li>
			</ul>
		</div>
	</div>
</nav>
