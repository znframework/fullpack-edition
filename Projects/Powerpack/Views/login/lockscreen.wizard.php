<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
<title>ZN Powerpack &raquo; {{$dict->lockScreen}}</title>
	@Import::view('sections/head'):
</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		<div class="vertical-align-wrap">
			<div class="vertical-align-middle">
				<div class="auth-box lockscreen clearfix">
					<div class="content">
						<h1 class="sr-only">ZN Framework - {{$dict->lockScreen}}</h1>
						<div class="logo text-center"><img src="{{THEMES_URL}}img/logo-dark.png" alt="ZN Framework"></div>
						<div class="user text-center">
							<img src="{{photo(Cookie::photo())}}" class="img-circle" alt="Avatar">
							<h2 class="name">{{Cookie::name() ?: $dict->user}}</h2>
						</div>
						<form method="post">
							<div class="input-group">
								<input type="email" name="email" class="form-control" placeholder="{{$dict->yourEmail}} ...">
								<span class="input-group-btn"><button name="lockscreen" type="submit" value="1" class="btn btn-primary"><i class="fa fa-arrow-right"></i></button></span>
							</div>
						</form>
						<br>
						@status($status ?? NULL,  $info ?? 'danger'):
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- END WRAPPER -->
</body>

</html>
