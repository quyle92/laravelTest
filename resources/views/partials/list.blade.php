<ul class="{{$field ?? 'channel_name'}}">
	@foreach($channels as $channel)
		<li>{{$channel->name}}</li>
	@endforeach
</ul>