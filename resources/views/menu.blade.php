<header class="web-title">
	<h3><a href="/">やりたいことリスト</a></h3>
</header>
<div class="padding-content">
	@if (is_null($user))
		<section class="pure-g menu">
			<button class="pure-u-1 pure-button menu-button" onclick="location.href='/login'">
				<i class="fa fa-fw fa-twitter"></i>
				ログイン
			</button>
		</section>
	@else
		<section class="profile">
			<a href="/profile/{{$user->id}}">
				<img class="icon-img" src="https://twitter.com/{{$user->nickname}}/profile_image?size=normal">
				<div class="name">
					{{'@'.$user->nickname}}
				</div>
			</a>
		</section>
		<section class="pure-g menu">
			<button class="pure-u-1 pure-button menu-button" onclick="location.href='/list/create'">
				<i class="fa fa-fw fa-plus-square-o"></i>
				リストを作る
			</button>
			<button class="pure-u-1 pure-button menu-button" onclick="location.href='/{{$user->nickname}}'">
				<i class="fa fa-fw fa-list"></i>
				リストを見る
			</button>
			<button class="pure-u-1 pure-button menu-button" onclick="location.href='/logout'">
				<i class="fa fa-fw fa-sign-out"></i>
				ログアウト
			</button>
		</section>
	@endif
</div>