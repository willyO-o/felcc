 @include('velzon.partials.main')

 <head>

     <meta charset="utf-8" />
     <title> Error 404 | Page Not Found </title>
     <meta name="csrf-token" content="{{ csrf_token() }}">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
     <meta content="Themesbrand" name="author" />
     <!-- App favicon -->
     <link rel="shortcut icon" href="/assets/images/favicon.ico">


      @include('layouts.partials.head-css')



 </head>

 <body>

     <!-- auth-page wrapper -->
     <div class="auth-page-wrapper py-5 d-flex justify-content-center align-items-center min-vh-100">

         <!-- auth-page content -->
         <div class="auth-page-content overflow-hidden p-0">
             <div class="container">
                 <div class="row justify-content-center">
                     <div class="col-xl-7 col-lg-8">
                         <div class="text-center">
                             <img src="/assets/images/error400-cover.png" alt="error img" class="img-fluid">
                             <div class="mt-3">
                                 <h3 class="text-uppercase"> Uppsi! Página no encontrada 😭</h3>
                                 <p class="text-muted mb-4">La página que estás buscando no está disponible!</p>
                                 <a href="{{ route('dashboard') }}" class="btn btn-success"><i
                                         class="mdi mdi-home me-1"></i>Regresar a la página principal</a>
                             </div>
                         </div>
                     </div><!-- end col -->
                 </div>
                 <!-- end row -->
             </div>
             <!-- end container -->
         </div>
         <!-- end auth-page content -->
     </div>
     <!-- end auth-page-wrapper -->

 </body>

 </html>
