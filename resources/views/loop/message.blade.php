<div class="media mb-3" id="message-{{ $message->id }}">
	<img class="align-self-start rounded-circle mr-3" src="{{ optional($message->user)->avatar }}"
	     alt="{{ optional($message->user)->name }}" width="40px">
	<div class="media-body p-3 {{ $message->user_id == auth()->id() ? 'bg-light' : 'bg-primary text-light' }}">
		<h5 class="mt-0">{{ optional($message->user)->name }}</h5>
		<div class="lead">
			{!! nl2br(e($message->body)) !!}
		</div>
	</div>
</div>
