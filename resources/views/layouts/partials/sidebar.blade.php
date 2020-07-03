<a href="#" class="btn btn-danger btn-sm btn-block" data-toggle="modal" data-target="#compose-modal">
	<i class="fa fa-edit"></i>
	Compose
</a>
<hr>

<ul class="nav nav-pills flex-column nav-stacked">
	<li class="nav-item">
		<a class="nav-link {{ !count(request()->all()) ? 'active' : '' }}"
		   href="{{ route(config('inbox.route.name') . 'inbox.index') }}">
			Inbox
			<span class="badge badge-success float-right">{{ auth()->user()->unread()->count() }}</span>
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link {{ request()->has('sent') ? 'active' : '' }}"
		   href="{{ route(config('inbox.route.name') . 'inbox.index', ['sent']) }}">
			Sent Mail
		</a>
	</li>
	{{--
	<li class="nav-item">
		<a class="nav-link {{ request()->has('starred') ? 'active' : '' }}"
		   href="{{ route(config('inbox.route.name') . 'inbox.index', ['starred']) }}">
			Starred
		</a>
	</li>
	<li class="nav-item">
		<a class="nav-link {{ request()->has('drafts') ? 'active' : '' }}"
		   href="{{ route(config('inbox.route.name') . 'inbox.index', ['drafts']) }}">
			Drafts
			<span class="badge badge-success float-right">3</span>
		</a>
	</li>
	 --}}
</ul>
