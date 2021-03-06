@extends('master')

@section('main-content')
{{ Form::open([
	"class" => "pure-form ",
	"method" => "post"
]) }}

	<header>
		<img class="icon-img" src="{{$user->image}}">
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
				<fieldset  class="pure-group list-item">
					{{ Form::text(
						"item_titles[".$loop->index."]",
						$item->title,
						[
							"max-length" => "120",
						 	"class" => "pure-input-1",
						 	"placeholder" => "やりたいこと その".($loop->index + 1)
						])
					}}
					{{ Form::textarea(
						"item_comments[".$loop->index."]",
						$item->comment,
						[
							"max-length" => "500",
						 	"class" => "pure-input-1 comment",
						 	"placeholder" => "コメント",
						])
					}}
					<section class="pure-input-1 done">
						{{Form::checkbox("item_dones[".$loop->index."]", null, $item->is_done, [
							"id" => "item_done_".$loop->index,
							"class" => "tgl tgl-flip hidden",
						])}}
						<label
							class="tgl-btn done-flg"
							data-tg-off="未達成"
							data-tg-on="達成"
							for="{{"item_done_".$loop->index}}">
						</label>
						達成日:
						{{Form::date("item_done_dates[".$loop->index."]", $item->done, [
							"class" => "done-date",
						])}}
					</section>
				</fieldset>
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
			@if ($list->id == -1)
				{{ Form::submit("登録", ["name" => "create", "class" => "pure-u-1 pure-button menu-button"]) }}
			@else
				{{ Form::submit("保存", ["name" => "update", "class" => "pure-u-1 pure-button menu-button"]) }}
				{{ Form::submit("削除", [
					"name" => "delete",
					"class" => "pure-u-1 pure-button menu-button",
					"onclick" => "return confirm('リストを削除してよろしいですか？');"
				]) }}
			@endif
			@php
				$cancel_url = $list->id == -1 ? "/".$user->nickname : "/list/".$list->id;
			@endphp
			<button type="button" class="pure-u-1 pure-button menu-button" onclick="location.href='{{$cancel_url}}';">キャンセル</button>
		</footer>

		{{ Form::hidden("list_id", $list->id) }}

	</section>
{{Form::close()}}

@endsection