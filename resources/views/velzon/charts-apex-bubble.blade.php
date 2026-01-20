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
                <h4 class="card-title mb-0">Basic Bubble Chart</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="simple_bubble" data-colors='["--vz-primary", "--vz-info", "--vz-warning", "--vz-success"]' class="apex-charts" dir="ltr"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">3D Bubble Chart</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="bubble_chart" data-colors='["--vz-primary", "--vz-success", "--vz-warning", "--vz-danger"]' class="apex-charts" dir="ltr"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>






 @endsection 
 @section('js') 


<script src="/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- bubblecharts init -->
<script src="/assets/js/pages/apexcharts-bubble.init.js"></script>



 @endsection 