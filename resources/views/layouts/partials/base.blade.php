<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>


    <meta charset="utf-8" />
    <title>Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

     @include('velzon.partials.head-css')

     @yield('css')

</head>

<body>



    <!-- Begin page -->
    <div id="layout-wrapper">

         @include('velzon.partials.topbar');
         @include('velzon.partials.sidebar');

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0"> @yield("title") </h4>

                                 @yield("breadcrumb")

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <!-- contenido -->

                     @yield("content")

                    <!-- fin contenido -->


                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

             @include('velzon.partials.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->






     @include('velzon.partials.customizer')


    <!-- JAVASCRIPT -->
    <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="/assets/libs/node-waves/waves.min.js"></script>
    <script src="/assets/libs/feather-icons/feather.min.js"></script>
    <script src="/assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="/assets/js/plugins.js"></script>
    <script src="/assets/libs/sweetalert2/sweetalert2.min.js"></script>

     @yield('js')


    <script src="/assets/js/app.js"></script>


</body>

</html>
