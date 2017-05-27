@extends('master')

@section('main-content')
{{ Form::open(["class" => "pure-form ", 'method' => "post"]) }}

	<header>
		<img class="icon-img" src="https://twitter.com/{{$user->nickname}}/profile_image?size=bigger">
		{{Form::text("title", $list->title, [
			"maxlength" => "64",
			"class" => "pure-u-1 title edit-title",
			"placeholder" => "タイトル",
			"required" => "required",
		])}}
		<h2 class="name">
			<a href="https://twitter.com/{{$user->nickname}}" target="_blank">
				{{$user->name}}
			</a>
		</h2>
	</header>

	<section class="padding-content">
		<section class="lists">
			<label class="pure-u-1">やりたいこと</label>
			@foreach ($items as $item)
				<section class="pure-g list-item">
					{{ Form::text(
						"item_titles[".$loop->index."]",
						$item->title,
						[
							"max-length" => "120",
						 	"class" => "pure-u-1",
						 	"placeholder" => "やりたいこと その".($loop->index + 1)
						])
					}}
				</section>
			@endforeach
		</section>

		<section>
			<label class="pure-u-1">リストの色</label>
			<section class="list-color">
				@foreach (["#FF691F", "#FAB81E", "#7FDDB6", "#19CF86", "#91D2FA", "#1B95E0", "#ABB8C2", "#E81C4F", "#F58EA8", "#981CEB"] as $item)
					<button type="button" class="pure-button color-button" style="background-color: {{$item}};" onclick="$('#color-box').val('{{$item}}');$('#color-box').focus();"></button>
				@endforeach
				{{ Form::text(
					"list_color",
					"#".$list->color,
					[
						"id" => "color-box",
						"max-length" => "7",
					 	"placeholder" => "#FFFFFF",
					 	"required" => "required",
					 	"pattern" => "#[a-fA-F0-9]{6}",
					])
				}}
			</section>
		</section>

		<footer class="pure-g menu">
			{{ Form::submit("保存", ["name" => "save", "class" => "pure-u-1 pure-button menu-button"]) }}
			{{ Form::submit("削除", [
				"name" => "delete",
				"class" => "pure-u-1 pure-button menu-button",
				"onclick" => "return confirm('リストを削除してよろしいですか？');"
			]) }}
			<button type="button" class="pure-u-1 pure-button menu-button" onclick="location.href='/list/{{$list->id}}';">キャンセル</button>
		</footer>

		{{ Form::hidden("list_id", $list->id) }}

	</section>
{{Form::close()}}

@endsection