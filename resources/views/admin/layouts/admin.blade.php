<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin panel</title>
    @yield('head')

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/admin-panel.css') }}">

</head>
<body class="admin-shell">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" id="adminSidebarToggle" href="#" role="button" aria-label="Open menu">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>
    </nav>

    @include('admin.pages.sidebar')
    <div class="content-wrapper">
        <div class="content">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <h4><i class="icon fa fa-check"></i> {{ session('success') }}</h4>
                </div>
            @endif

            @if (session('errors'))
                <div class="alert alert-danger" role="alert">
                    <h4><i class="icon fa fa-times"></i> {{ session('errors')->getBags()['default']->first() }}</h4>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEnv6iWIJ2L_KNn1Y_mq1BCvkgJSWlrSs&libraries=places" defer></script>
<script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('admin/admin.js') }}"></script>
<script>
    const sidebarToggle = document.getElementById('adminSidebarToggle');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function (event) {
            event.preventDefault();
            document.body.classList.toggle('admin-sidebar-open');
        });
    }

    document.addEventListener('click', function (event) {
        if (window.innerWidth > 991) {
            return;
        }

        const sidebar = document.querySelector('.admin-sidebar');
        const clickedInsideSidebar = sidebar && sidebar.contains(event.target);
        const clickedToggle = sidebarToggle && sidebarToggle.contains(event.target);

        if (!clickedInsideSidebar && !clickedToggle) {
            document.body.classList.remove('admin-sidebar-open');
        }
    });

    function goBack() {
        window.history.back();
    }
</script>
@yield('js_bottom')

</body>
</html>
