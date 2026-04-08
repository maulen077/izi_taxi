<!DOCTYPE HTML>
<html lang="en" style="height: auto;">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin panel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBEnv6iWIJ2L_KNn1Y_mq1BCvkgJSWlrSs&libraries=places" defer></script>
{{--    <script src="{{ asset('js/app.js') }}" defer></script>--}}
    {{--    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
    <!-- Bootstrap 4 -->
{{--    <script src="{{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>--}}
    <!-- AdminLTE App -->
    <script src="{{asset('admin/dist/js/adminlte.min.js')}}"></script>
    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
{{--    <script src="{{asset('admin/plugins/ekko-lightbox/ekko-lightbox.min.js')}}"></script>--}}
{{--    <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>--}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
{{--    <script>--}}
{{--        $('#description').summernote()--}}
{{--        $('#descriptions').summernote()--}}
{{--        $('#description_Жаналықтар').summernote()--}}
{{--        $('#terms_of_use').summernote()--}}
{{--        $('#privacy_policy').summernote()--}}
{{--    </script>--}}
    @yield('head')
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="{{asset('css/app.css')}}" rel="stylesheet">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="/admin/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin/dist/css/adminlte.min.css?v=3.2.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
{{--    <link rel="stylesheet" href="{{asset('/bootstrap/css/bootstrap.min.css')}}">--}}
{{--    <link rel="stylesheet" href="{{asset('admin/plugins/fontawesome-free/css/all.min.css')}}">--}}
    <link rel="stylesheet" href="{{asset('admin/dist/css/adminlte.min.css')}}">
{{--    <link rel="stylesheet" href="{{asset('admin/plugins/summernote/summernote-bs4.min.css')}}">--}}
    <link rel="stylesheet" href="https://unpkg.com/pyloncss@latest/css/pylon.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.5.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="sidebar-mini sidebar-closed sidebar-collapse" style="height: auto;">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            {{--            @if(auth('service')->user())--}}
            {{--                <li class="nav-item d-none d-sm-inline-block">--}}
            {{--                    Сервис--}}
            {{--                </li>--}}
            {{--            @endif--}}
        </ul>

    </nav>
    <!-- /.navbar -->

    @include('admin.pages.sidebar')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('title')</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6 justify-content-end d-flex">
                        @yield('top_right_content')
                    </div><!-- /.col -->
                </div><!-- /.row -->
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                        <h4><i class="icon fa fa-check"></i>{{session('success')}}</h4>
                    </div>
                @endif
                @if (session('errors'))
                    <div class="alert alert-danger" role="alert">
                        <h4><i class="icon fa fa-times"></i>{{session('errors')->getBags()['default']->first()}}</h4>
                    </div>
                @endif
            </div><!-- /.container-fluid -->
        </div>
        <!-- Main content -->
        <div class="content">
            @yield('content')
        </div>
        <div class="content">
            @yield('title2')
            @yield('content2')
        </div>
    </div>
    <!-- /.content-wrapper -->

</div>
<!-- ./wrapper -->

<!-- jQuery -->
{{--<script src="/admin/plugins/jquery/jquery.min.js"></script>--}}
<!-- Bootstrap 4 -->
{{--<script src="/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>--}}
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>--}}
<!-- bs-custom-file-input -->
{{--<script src="/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>--}}
<!-- AdminLTE App -->
<script src="/admin/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>--}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>

{{--<script src="/admin/plugins/select2/js/select2.full.min.js"></script>--}}


<script src="/admin/admin.js"></script>
{{--<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>--}}
<!-- Page specific script -->
<script>
    // $(function () {
    //     bsCustomFileInput.init();
    // });
    function goBack() {
        window.history.back();
    }
</script>
@yield('js_bottom')

</body>
</html>

