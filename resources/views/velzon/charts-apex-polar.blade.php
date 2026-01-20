 @extends('velzon.partials.base') 
 @section('css') 




 @endsection 
 @section('breadcrumb') 
<div class="page-title-right">
    <ol class="breadcrumb m-0">
        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
        <li class="breadcrumb-item active">Projects</li>
    </ol>
</div>
 @endsection 
 @section('content') 




<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Basic Polararea Chart</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="basic_polar_area" data-colors='["--vz-primary", "--vz-success", "--vz-warning","--vz-danger", "--vz-info", "--vz-success", "--vz-primary", "--vz-dark", "--vz-secondary"]' class="apex-charts" dir="ltr"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">PolarArea Monochrome</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="monochrome_polar_area" class="apex-charts" dir="ltr"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->






 @endsection 
 @section('js') 

<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- polarareacharts init -->
<script src="/assets/js/pages/apexcharts-polararea.init.js"></script>




 @endsection 