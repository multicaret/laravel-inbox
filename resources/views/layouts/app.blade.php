<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
	<link rel="stylesheet"
	      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

	<title>Inbox</title>

	<style>
		/* CSS used here will be applied after bootstrap.css */

		.tab-pane .list-group-item:first-child {
			border-top-right-radius: 0;
			border-top-left-radius: 0;
		}

		.tab-pane .list-group-item:last-child {
			border-bottom-right-radius: 0;
			border-bottom-left-radius: 0;
		}

		.tab-pane .list-group input[type="checkbox"] {
			margin-top: 2px;
		}

		a.list-group-item.read {
			color: #222;
			background-color: #F3F3F3;
		}
	</style>
</head>
<body>
@include('inbox::layouts.partials.navbar')
<div class="container-fluid">

	{{--
	<div class="row">
		<div class="col-sm-3 col-md-2">
			<div class="btn-group dropdown">
				<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					Mail <span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu">
					<li><a href="#">Mail</a></li>
					<li><a href="#">Contacts</a></li>
					<li><a href="#">Tasks</a></li>
				</ul>
			</div>

		</div>
		<div class="col-sm-9 col-md-10">
			<!-- Split button -->
			<div class="btn-group dropdown">
				<label class="btn btn-outline-secondary dropdown-toggle mb-0" data-toggle="dropdown">
					<input type="checkbox">
				</label>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="#">All</a>
					<a class="dropdown-item" href="#">None</a>
					<a class="dropdown-item" href="#">Read</a>
					<a class="dropdown-item" href="#">Unread</a>
					<a class="dropdown-item" href="#">Starred</a>
					<a class="dropdown-item" href="#">Unstarred</a>
				</div>
			</div>

			<button type="button" class="btn btn-outline-secondary" data-toggle="tooltip" title="Refresh">
				<i class="fa fa-sync-alt"></i>
			</button>

			<!-- Single button -->
			<div class="btn-group dropdown">
				<button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
					More
				</button>
				<div class="dropdown-menu" role="menu">
					<a class="dropdown-item" href="#">Mark all as read</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="#">Mark all as read</a>
				</div>
			</div>
		</div>
	</div>
	<hr>
	--}}
	<div class="row">
		<div class="col-sm-3 col-md-2">
			@include('inbox::layouts.partials.sidebar')
		</div>
		<div class="col-sm-9 col-md-10">
			<!-- Nav tabs -->
			<ul class="nav nav-tabs">
				<li class="nav-item">
					<a class="nav-link active" href="#home" data-toggle="tab">
						<i class="fa fa-inbox"></i> Primary
					</a>
				</li>
				{{--
				<li class="nav-item">
					<a class="nav-link" href="#profile" data-toggle="tab">
						<i class="fa fa-user"></i> Social
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#messages" data-toggle="tab">
						<i class="fa fa-tags"></i>
						Promotions
					</a>
				</li>
				 --}}
			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane fade show active" id="home">
					@yield('content')
				</div>

				{{--
				<div class="tab-pane fade in" id="profile">
					<div class="list-group">
						<div class="list-group-item">
							<span class="text-center">This tab is empty.</span>
						</div>
					</div>
				</div>
				<div class="tab-pane fade in" id="messages">
					...
				</div>
				 --}}
			</div>

			<div class="clearfix text-center mx-auto mt-4 d-table">
				@yield('pagination')
			</div>
		</div>
	</div>
</div>

<!-- Compose Modal -->
<div class="modal fade" id="compose-modal" tabindex="-1">
	<div class="modal-dialog" role="document">
		<form class="modal-content" action="{{ route(config('inbox.route.name') . 'inbox.store') }}" method="POST">
			@csrf
			<div class="modal-header">
				<h5 class="modal-title">Send New Message</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				@include('inbox::form')
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">@lang('inbox::strings.form.send')</button>
			</div>
		</form>
	</div>
</div>

@if(session()->has('message'))
	<div class="alert alert-{{ session('message')['type'] }} alert-dismissible fade show position-fixed"
	     style="bottom:10px; right:15px;">
		{{ session('message')['text'] }}
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>
@endif


<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
</body>
</html>
