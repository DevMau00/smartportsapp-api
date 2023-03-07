
<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="keywords" content="Smartports" />
    <meta name="description" content="Smartports - API" />
    <meta name="author" content="api.smartports.app" />

     

    <!-- Title  -->
    <title>{{ env('APP_NAME') }} | @yield('title')</title>
    <link rel="icon" href="images/favicon.png" type="image/png" sizes="32x32">


    <link rel="stylesheet" href="css/hightlightjs-dark.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.8.0/highlight.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,400;0,500;1,300&family=Source+Code+Pro:wght@300&display=swap" rel="stylesheet"> 
    <link href="{{ url('css/style.css') }}" rel="stylesheet"/>
    <script>
        hljs.initHighlightingOnLoad();
    </script>

    @stack('styles')

    <style>
        
    </style>

    
</head>

{{-- <body class="one-content-column-version"> --}}
<body>
    <div class="left-menu">
        <div class="content-logo">
            <div class="logo">
                <img alt="" title="Smartports API" src="images/Smartports-Logo-2022-blue.png" height="50" />
            </div>
            <button class="burger-menu-icon" id="button-menu-mobile">
                <svg width="34" height="34" viewBox="0 0 100 100"><path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058"></path><path class="line line2" d="M 20,50 H 80"></path><path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942"></path></svg>
            </button>
        </div>
        <div class="mobile-menu-closer"></div>
        <div class="content-menu">
            <div class="content-infos">
                <div class="info"><b>Version:</b> {{ $version }}</div>
                <div class="info"><b>Last Updated:</b> {{ $last_update }}</div>
            </div>
            <ul>
                <li class="scroll-to-link active" data-target="get-api-key">
                    <a>GET API KEY</a>
                </li>
                <li class="scroll-to-link" data-target="track-bl">
                    <a>Track BL</a>
                </li>
                <li class="scroll-to-link" data-target="track-container">
                    <a>Track Container</a>
                </li>
                <li class="scroll-to-link" data-target="track-awb">
                    <a>Track AWB</a>
                </li>
                <li class="scroll-to-link" data-target="shipping-lines">
                    <a>Shipping Lines</a>
                </li>
                <li class="scroll-to-link" data-target="content-errors">
                    <a>Errors</a>
                </li>
            </ul>
        </div>
    </div>
    
<!-- App -->
<div id="app">
    @yield('content')

</div><!-- App -->









@stack('scripts')
<script src="{{url('js/script.js')}}"></script>


</body>

</html>