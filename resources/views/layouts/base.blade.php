<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>【@yield('page_title')】SFF幻想的空想科学物語イフス[IFS]</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link href="/css/common.css" rel="stylesheet" type="text/css">
    @if(View::hasSection('contents'))<link href="/css/@yield('contents').css" rel="stylesheet" type="text/css">@endif
</head>

<body>
<div class="container">
    <header>
        <div id="header">
            <div class="wrap">
                <ul class="pt-2">
                    <li><a href="/" class="arrow">TOP</a></li>
                    <li><a href="/danwa" class="arrow">談話室</a></li>
                    <!--<li><a href="/contact" class="arrow">お問い合わせ</a></li>-->
                </ul>



            </div>
        </div>
    </header>

    @yield('content')

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="/js/jquery.colorbox.js"></script>
<script src="/js/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
@yield('scripts')
</body>
</html>
