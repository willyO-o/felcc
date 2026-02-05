<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'FELCC - Autenticación')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistema de Gestión de Mandamientos de Aprehensión" name="description" />
    <meta content="FELCC" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    @include('velzon.partials.head-css')

    @yield('css')
</head>

<body>

    @yield('content')

    @include('velzon.partials.vendor-scripts')

    @yield('scripts')

</body>

</html>
