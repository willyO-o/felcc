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

                @canany(['superadmin', 'administrador', 'tecnico_daci'])
                    <!-- Mandamientos -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('mandamientos.index') }}">
                            <i class="mdi mdi-gavel"></i> <span>Mandamientos</span>
                        </a>
                    </li>
                @endcanany


                @canany(['tecnico_felcc'])
                    <!-- Mandamientos -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('mandamientos.create') }}">
                            <i class="mdi mdi-plus"></i> <span>Registrar Mandamiento</span>
                        </a>
                    </li>
                @endcanany
                <li class="menu-title"><span data-key="t-menu">Consultas</span></li>
                <li class="nav-item">
                    <a class="nav-link menu-link" href="{{ route('consultas.mandamientos') }}">
                        <i class="mdi mdi-magnify"></i> <span>Consultar Mandamientos</span>
                    </a>
                </li>
                @canany(['superadmin', 'administrador', 'tecnico_daci'])
                    <li class="nav-item d-none">
                        <a class="nav-link menu-link" href="{{ route('consultas.mandamientos') }}">
                            <i class="mdi mdi-criminal"></i> <span>Consultar Registro Criminal</span>
                        </a>
                    </li>
                @endcanany

                @canany(['superadmin', 'administrador', 'tecnico_daci'])
                    <li class="menu-title"><span data-key="t-menu">EREBOR</span></li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('registro-criminal.index') }}">
                            <i class="mdi mdi-head"></i> <span>Registro Criminal</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('personas.index') }}">
                            <i class="mdi mdi-account-outline"></i> <span>Personas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('telefonos.index') }}">
                            <i class="mdi mdi-phone"></i> <span>Teléfonos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('imeis.index') }}">
                            <i class="mdi mdi-numeric"></i> <span>IMEIs</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('vehiculos.index') }}">
                            <i class="mdi mdi-car"></i> <span>Vehículos</span>
                        </a>
                    </li>
                @endcanany


                @canany(['superadmin', 'administrador'])
                    <!-- Usuarios -->
                    <li class="menu-title"><span data-key="t-menu">Importar</span></li>

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
                                    <a href="{{ route('importar.mandamientos.index') }}" class="nav-link">Importar
                                        Mandamientos</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('telefonos.importar.index') }}" class="nav-link">Importar
                                        Telefonos</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('vehiculos.importar.index') }}" class="nav-link">Importar
                                        Vehículos</a>
                                </li>
                                <li class="nav-item d-none">
                                    <a href="{{ route('usuarios.index') }}" class="nav-link">Importar Registro</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endcanany

                @canany(['superadmin', 'administrador'])
                    <li class="menu-title"><span data-key="t-menu">Seguridad</span></li>

                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('usuarios.index') }}">
                            <i class="mdi mdi-account-group-outline"></i> <span>Usuarios</span>
                        </a>
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
