<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
	<title>ZN Power Pack &raquo; {{$dict->installation}}</title>
	@Import::view('sections/head'):
</head>

<body>
	<!-- WRAPPER -->
	<div id="wrapper">
		<div class="vertical-align-wrap">
			<div class="vertical-align-middle">
				<div class="auth-box ">
					<div class="left">
						<div class="content">
							<div class="header">
								<div class="logo text-center"><img src="{{THEMES_URL}}img/logo-dark.png" alt="ZN Logo"></div>
								<p class="lead">{{$dict->welcomeInstallationPage}}</p>
							</div>
							<form method="post" class="form-auth-small">

								<div class="form-group">
									@@Form::class('form-control')->select('driver', 
									[
										'mysqli' => 'MySQL'
									], '0'):
								</div>

								<div class="form-group">
									<label for="host" class="control-label sr-only">Host</label>
									<input type="text" name="host" class="form-control" id="host" placeholder="Host(*): localhost" required>
								</div>

								<div class="form-group">
									<label for="database" class="control-label sr-only">Database</label>
									<input type="text" name="database" class="form-control" id="database" placeholder="Database Name(*): powerpack" required>
								</div>

								<div class="form-group">
									<label for="user" class="control-label sr-only">User</label>
									<input type="text" name="user" class="form-control" id="user" placeholder="User Name(*): root" required>
								</div>

								<div class="form-group">
									<label for="password" class="control-label sr-only">Password</label>
									<input type="text" name="password" class="form-control" id="password" placeholder="Password: 1234">
								</div>

								<input type="submit" name="install" value="{{$dict->installButton}}" class="btn btn-primary btn-lg btn-block">
								
							</form>
						</div>
					</div>
					<div class="right">
						<div class="overlay"></div>
						<div class="content text">
							<h1 class="heading">ZN Framework Powerpack</h1>
							<p>{{$dict->forDeveloper}} ~ {{$dict->version}} {{$version}}</p>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- END WRAPPER -->
</body>

</html>
