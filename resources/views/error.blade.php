@extends('master')

@section('main-content')
<header>
	<h1 class="title">{{$error_code}}</h1>
</header>
<section class="padding-content">
	<p class="pure-u-1">
		{{$error_message}}
	</p>
</section>
@endsection