<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box mt-3">
        <!-- Dark Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="/felcc/img/felcc.png" alt="" width="40">
            </span>
            <span class="logo-lg">
                <img src="/felcc/img/felcc.png" alt="" width="70">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="/felcc/img/felcc.png" alt="" width="40">
            </span>
            <span class="logo-lg">
                <img src="/felcc/img/felcc.png" alt="" width="70">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menú</span></li>

                <!-- Dashboard -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('dashboard') }}">
                        <i class="mdi mdi-speedometer"></i> <span>Dashboard</span>
                    </a>
                </li>

                <!-- Mandamientos -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('mandamientos.index') }}">
                        <i class="mdi mdi-gavel"></i> <span>Mandamientos</span>
                    </a>
                </li>

                <!-- Registro Criminal -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('registro-criminal.index') }}">
                        <i class="mdi mdi-file-document-outline"></i> <span>Registro Criminal</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('personas.index') }}">
                        <i class="mdi mdi-account"></i> <span>Personas</span>
                    </a>
                </li>



                @canany(['superadmin', 'administrador','tecnico'])
                    <!-- Usuarios -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarImportar" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarImportar">
                            <i class="mdi mdi-upload"></i> <span>Importar</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarImportar">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item d-none">
                                    <a href="{{ route('personas.importar.index') }}" class="nav-link">Importar Personas</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('importar.mandamientos.index') }}" class="nav-link">Importar Mandamientos</a>
                                </li>

                                <li class="nav-item d-none">
                                    <a href="{{ route('usuarios.index') }}" class="nav-link">Importar Registro</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarUsuarios" data-bs-toggle="collapse" role="button"
                            aria-expanded="false" aria-controls="sidebarUsuarios">
                            <i class="mdi mdi-account-group-outline"></i> <span>Usuarios</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarUsuarios">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('usuarios.index') }}" class="nav-link">Lista de Usuarios</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('usuarios.index') }}" class="nav-link" id="linkNuevoUsuario">Nuevo
                                        Usuario</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endcanany

            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
