<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@section('title') Sea Watch Desktop @show</title>
    @section('meta_keywords')
        <meta name="keywords" content="your, awesome, keywords, here"/>
    @show @section('meta_author')
        <meta name="author" content="Jon Doe"/>
    @show @section('meta_description')
        <meta name="description"
              content="Lorem ipsum dolor sit amet, nihil fabulas et sea, nam posse menandri scripserit no, mei."/>
    @show
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
        <link href="{{ asset('css/site.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <script src="{{ asset('js/config.js') }}"></script>
        <script src="{{ asset('js/site.js') }}"></script>

    @yield('styles')
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="shortcut icon" href="{!! asset('assets/site/ico/favicon.ico')  !!} ">
</head>
<body style="padding-top:64px;">
    
<!--loggedOut-->

<style>
    .tech-support-box{
            height: 100px;
            width: 220px;
            position: fixed;
            top: 50%;
            right: 0;
            bottom: 0;
            margin-top: -50px;
            background: rgb(55,60,68);
            z-index: 1337;
        
    }
    
</style>


    <div class="tech-support-box">
        <h2>Support</h2>
        <div class="btn btn-block btn-primary">Chat</div>
        <div class="btn btn-block btn-primary">Skype</div>
        <span>+49 12354198 (DE)</span>
        <span>+12 789712 (EN)</span>
    </div>
@include('partials.nav')
<div class="container-fluid">
@yield('content')
</div>
<!-- Scripts -->
@yield('scripts')
</body>
</html>
