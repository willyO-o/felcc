 @extends('velzon.partials.base') 
 @section('css') 


<link href="/assets/libs/leaflet/leaflet.css" rel="stylesheet" type="text/css" />

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
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Example</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="leaflet-map" class="leaflet-map"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Markers, Circles and Polygons</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="leaflet-map-marker" class="leaflet-map"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Working with Popups</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="leaflet-map-popup" class="leaflet-map"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Markers with Custom Icons</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="leaflet-map-custom-icons" class="leaflet-map"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->

<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Interactive Choropleth Map</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="leaflet-map-interactive-map" class="leaflet-map"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Layer Groups and Layers Control</h4>
            </div><!-- end card header -->

            <div class="card-body">
                <div id="leaflet-map-group-control" class="leaflet-map"></div>
            </div><!-- end card-body -->
        </div><!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->







 @endsection 
 @section('js') 


<!-- prismjs plugin -->
<script src="/assets/libs/prismjs/prism.js"></script>

<!-- leaflet plugin -->
<script src="/assets/libs/leaflet/leaflet.js"></script>

<!-- leaflet map.init -->
<script src="/assets/js/pages/leaflet-us-states.js"></script>
<script src="/assets/js/pages/leaflet-map.init.js"></script>



 @endsection 