<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<!-- VENDOR CSS -->
<link rel="stylesheet" href="{{STYLES_URL}}style.css">
<link rel="stylesheet" href="{{THEMES_URL}}vendor/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="{{THEMES_URL}}vendor/font-awesome/css/font-awesome.min.css">
<link rel="stylesheet" href="{{THEMES_URL}}vendor/linearicons/style.css">
<link rel="stylesheet" href="{{THEMES_URL}}vendor/chartist/css/chartist-custom.css">

@foreach( $styles ?? [] as $style ):
    <link rel="stylesheet" href="{{$style}}">
@endforeach:

<!-- MAIN CSS -->
<link rel="stylesheet" href="{{THEMES_URL}}css/main.css">
<!-- FOR DEMO PURPOSES ONLY. You should remove this in your project -->
<link rel="stylesheet" href="{{THEMES_URL}}css/demo.css">
<!-- GOOGLE FONTS -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
<!-- ICONS -->
<link rel="apple-touch-icon" sizes="76x76" href="{{THEMES_URL}}img/apple-icon.png">
<link rel="icon" type="image/png" sizes="96x96" href="{{THEMES_URL}}img/favicon.png">