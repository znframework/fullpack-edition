<!doctype html>
<html lang="en" class="fullscreen-bg">

<head>
	<title>ZN Power Pack &raquo; {{$dict->login}}</title>
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
								<p class="lead">{{$dict->loginAccount}}</p>
							</div>
							<form method="post" class="form-auth-small">
								<div class="form-group">
									<label for="signin-email" class="control-label sr-only">{{$dict->email}}</label>
									<input type="email" name="email" class="form-control" id="signin-email" value="{{$config['default']['email']}}" placeholder="Email">
								</div>
								<div class="form-group">
									<label for="signin-password" class="control-label sr-only">{{$dict->password}}</label>
									<input type="password" name="password" class="form-control" id="signin-password" value="{{$config['default']['password']}}" placeholder="Password">
								</div>
								<div class="form-group clearfix">
									<label class="fancy-checkbox element-left">
										<input name="remember" type="checkbox">
										<span>{{$dict->rememberMe}}</span>
									</label>
								</div>
								<input type="submit" name="login" class="btn btn-primary btn-lg btn-block" value="{{$dict->loginButton}}">
								<div class="bottom">
									<span class="helper-text"><i class="fa fa-lock"></i> <a href="{{SITE_URL . 'lockscreen'}}">{{$dict->forgotPassword}}?</a></span>
								</div>
							</form>
						</div>
					</div>
					<div class="right">
						<div class="overlay"></div>
						<div class="content text">
							<h1 class="heading">ZN Framework Powerpack</h1>
							<p>{{$dict->forDeveloper}}</p>
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
