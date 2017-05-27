@extends('master')

@section('main-content')
<header>
	<img class="icon-img" src="https://twitter.com/{{$list_user->nickname}}/profile_image?size=bigger">
	<h1 class="title">{{$list->title}}</h1>
	<h2 class="name">
		<a href="/{{$list_user->nickname}}">
			{{$list_user->name}}
		</a>
	</h2>
	<div class="summary">
		登録: {{$list->count}} / 100　達成: {{$list->done}} / {{$list->count}}
	</div>
</header>

<section class="padding-content">
	<lu class="list">
		@if (count($items) == 0)
			<li>目標がありません！</li>
		@endif
		@foreach ($items as $item)
			<li><div class="index">#{{sprintf('%d', $loop->index + 1)}}</div>{{$item->title}}</li>
		@endforeach
	</lu>

	@if ($is_editable)
		<footer class="pure-g menu">
			<button class="pure-u-1 pure-button menu-button" onclick="location.href='/list/{{$list->id}}/edit'">
				リスト編集
			</button>
		</footer>
	@endif

</section>
@endsection