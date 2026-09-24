<!-- Sidebar Item para Reportes -->
<li class="nav-item">
    <a class="nav-link @if(Route::currentRouteName() === 'reportes.index' || Route::currentRouteName() === 'reportes.formulario' || Route::currentRouteName() === 'reportes.exportar') active @endif"
       href="{{ route('reportes.index') }}" role="button">
        <i class="mdi mdi-file-chart"></i>
        <span>Reportes</span>
    </a>
</li>
