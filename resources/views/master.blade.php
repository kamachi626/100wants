@php
	if(!isset($user)) {
		$user = null;
	}
@endphp
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
	<head>

		@php
			// タイトル構築
			$page_title = (isset($subtitle) ? $subtitle." - " : "")."やりたいことリスト";
		@endphp

		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- OGP -->
		<meta name="og:site_name" content="やりたいことリスト">
		<meta name="og:title" content="{{$page_title}}">
		<meta name="og:url" content="{{Request::url()}}">
		<meta name="og:type" content="{{isset($is_top) ? "website" : "article"}}">
		<meta name="og:description" content="死ぬまでにやりたい100のこととかを管理するやつ">
		<meta name="og:image" content="{{url("/img/ogimage.jpg")}}">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="@shogo_kamachi">
		<meta name="twitter:title" content="{{isset($subtitle) ? $subtitle : "やりたいことリスト"}}">

		<title>{{$page_title}}</title>

		<!-- Script -->
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g=" crossorigin="anonymous"></script>
		<script src="//twemoji.maxcdn.com/2/twemoji.min.js?2.3.0"></script>


		<!-- Style -->
		<link rel="stylesheet" href="https://unpkg.com/purecss@0.6.2/build/pure-min.css" integrity="sha384-UQiGfs9ICog+LwheBSRCt1o5cbyKIHbwjWscjemyBMT9YCUMZffs6UqUTd0hObXD" crossorigin="anonymous">
		<link rel="stylesheet" href="https://unpkg.com/purecss@0.6.2/build/grids-responsive-min.css">
		<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<link href="/css/style.css" rel="stylesheet" type="text/css">

		<style type="text/css">
			@php
				$code = "0084B4";
				if(!is_null($user)) {
					$code = $user->color;
				}
				if(isset($color)) {
					$code = $color;
				}
				$code = preg_replace("/#/", "", $code);
				$color_r = hexdec(substr($code, 0, 2));
				$color_g = hexdec(substr($code, 2, 2));
				$color_b = hexdec(substr($code, 4, 2));
				$color_main = "rgb(".$color_r.",".$color_g.",".$color_b.")";
				$color_sub = "rgba(".$color_r.",".$color_g.",".$color_b.",0.6)";
				$color_header = "rgba(".$color_r.",".$color_g.",".$color_b.",0.8)";
				$color_menu = "rgba(".$color_r.",".$color_g.",".$color_b.",0.2)";
			@endphp
			a, a:hover, a:visited {
				color: {{$color_main}};
			}
			#content {
				border: solid 1px {{$color_sub}} !important;
			}
			#content #main-content header {
				border-color: {{$color_sub}};
				background-color: {{$color_header}} !important;
			}
			#content #menu-content {
				background-color: {{$color_menu}} !important;
			}
			#content #menu-content .content {
				border-color: {{$color_sub}};
			}
			.menu .menu-button {
				background-color: {{$color_main}};
			}
		</style>

	</head>
	<body>
		<div id="website">
			<div id="content" class="pure-g">
				<div id="main-content" class="pure-u-1 pure-u-sm-2-3">
					<div class="content">
						@yield('main-content')
					</div>
				</div>

				<div id="menu-content" class="pure-u-1 pure-u-sm-1-3">
					<div class="content">
						@include('menu')
					</div>
				</div>

			</div>

			<footer class="page-footer">
				<p>
					<a href="https://twitter.com/shogo_kamachi" target="_blank">(C)@shogo_kamachi</a>
				</p>
			</footer>

		</div>
		<script>
			// twemoji.parse(document.body);
	    </script>
	</body>
</html>
