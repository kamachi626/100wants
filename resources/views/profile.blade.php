@extends('master')

@section('main-content')
	<header>
		<img class="icon-img" src="https://twitter.com/{{$profile_user->nickname}}/profile_image?size=bigger">
		<h1 class="title">
			<a href="https://twitter.com/{{$profile_user->nickname}}" target="_blank">
				{{$profile_user->name}}
			</a>
		</h1>
	</header>
	<section class="padding-content">
		<h3 class="list-title">{{$profile_user->name}}のリスト({{count($lists)}}/50)</h3>
		@if (count($lists) == 0)
			<div class="list-item">
				リストがありません。
			</div>
		@else
			<section class="lists">
				@foreach ($lists as $list)
					<div class="list-item">
						<a href="/list/{{$list->id}}">{{$list->title}}({{$list->count}})</a>
					</div>
				@endforeach
			</section>
		@endif
	</section>
@endsection