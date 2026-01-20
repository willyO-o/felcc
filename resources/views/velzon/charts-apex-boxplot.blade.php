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
                <h4 class="card-title mb-0">Basic Box Chart</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="basic_box" data-colors='["--vz-primary", "--vz-info"]' class="apex-charts" dir="ltr"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Boxplot with Scatter Chart</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="box_plot" data-colors='["--vz-danger", "--vz-info", "--vz-danger", "--vz-primary"]' class="apex-charts" dir="ltr"></div>
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
                <h4 class="card-title mb-0">Horizontal BoxPlot</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="box_plot_hori" data-colors='["--vz-light", "--vz-card-bg-custom"]' class="apex-charts" dir="ltr"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>






 @endsection 
 @section('js') 


<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- boxplotcharts init -->
<script src="/assets/js/pages/apexcharts-boxplot.init.js"></script>



 @endsection 