<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pipeline</title>
</head>
<body>
	<ul>
	@foreach($posts as $post)
		<li>
			{{$post->title}}
		</li>

	@endforeach
	</ul>
	{{ $posts->appends(request()->all())->links() }}
</body>
</html>