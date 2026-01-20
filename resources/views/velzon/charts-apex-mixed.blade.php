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
                <h4 class="card-title mb-0">Line & Column Charts</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="line_column_chart" data-colors='["--vz-primary", "--vz-success"]' class="apex-charts" dir="ltr"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Multiple Y-Axis Charts</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="multi_chart" data-colors='["--vz-primary", "--vz-info", "--vz-success"]' class="apex-charts" dir="ltr"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Line & Area Charts</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="line_area_chart" data-colors='["--vz-primary", "--vz-success"]' class="apex-charts" dir="ltr"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Line, Column & Area Charts</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="line_area_charts" data-colors='["--vz-primary", "--vz-success", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->





 @endsection 
 @section('js') 


<!-- apexcharts -->
<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- mixed charts init -->
<script src="/assets/js/pages/apexcharts-mixed.init.js"></script>



 @endsection 