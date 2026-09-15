@extends('layouts.auth')

@section('title', 'Iniciar Sesión - FELCC')

@section('css')
<style>
    .btn-google-signin {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        background-color: #fff;
        border: 1px solid #dadce0;
        border-radius: 0.375rem;
        color: #3c4043;
        font-family: inherit;
        font-size: 0.875rem;
        font-weight: 500;
        padding: 0.6rem 1rem;
        transition: background-color .15s ease, box-shadow .15s ease, border-color .15s ease;
    }
    .btn-google-signin:hover,
    .btn-google-signin:focus {
        background-color: #f8f9fa;
        border-color: #dadce0;
        color: #3c4043;
        box-shadow: 0 1px 2px 0 rgba(60, 64, 67, .3), 0 1px 3px 1px rgba(60, 64, 67, .15);
        text-decoration: none;
    }
    .btn-google-signin-icon {
        display: inline-flex;
        line-height: 0;
    }
    .signin-other-title {
        position: relative;
        text-align: center;
    }
    .signin-other-title:before {
        content: "";
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        border-top: 1px solid var(--vz-border-color, #e9ebec);
    }
    .signin-other-title span {
        position: relative;
        z-index: 1;
    }
</style>
@endsection

@section('content')
<div class="auth-page-wrapper pt-5">
    <!-- auth page bg -->
    <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
        <div class="bg-overlay"></div>

        <div class="shape">
            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
            </svg>
        </div>
    </div>

    <!-- auth page content -->
    <div class="auth-page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center mt-sm-5 mb-4 text-white-50">
                        <div>
                            <a href="/" class="d-inline-block auth-logo">
                                <img src="/assets/images/logo-light.png" alt="FELCC" height="20">
                            </a>
                        </div>
                        <p class="mt-3 fs-15 fw-medium">Sistema de Gestión de Mandamientos de Aprehensión</p>
                    </div>
                </div>
            </div>
            <!-- end row -->

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card mt-4">
                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">¡Bienvenido!</h5>
                                <p class="text-muted">Inicia sesión para continuar</p>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>¡Error!</strong>
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (Route::has('auth.google'))
                                <div class="mt-4">
                                    <a href="{{ route('auth.google') }}" class="btn-google-signin w-100">
                                        <span class="btn-google-signin-icon">
                                            <svg width="18" height="18" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                                <path fill="#4285F4" d="M17.64 9.2c0-.637-.057-1.251-.164-1.84H9v3.481h4.844a4.14 4.14 0 0 1-1.796 2.716v2.259h2.908c1.702-1.567 2.684-3.874 2.684-6.615z"/>
                                                <path fill="#34A853" d="M9 18c2.43 0 4.467-.806 5.956-2.184l-2.908-2.259c-.806.54-1.837.86-3.048.86-2.344 0-4.328-1.584-5.036-3.711H.957v2.332A8.997 8.997 0 0 0 9 18z"/>
                                                <path fill="#FBBC05" d="M3.964 10.706A5.41 5.41 0 0 1 3.682 9c0-.593.102-1.17.282-1.706V4.962H.957A8.996 8.996 0 0 0 0 9c0 1.452.348 2.827.957 4.038l3.007-2.332z"/>
                                                <path fill="#EA4335" d="M9 3.58c1.321 0 2.508.454 3.44 1.345l2.582-2.58C13.463.891 11.426 0 9 0A8.997 8.997 0 0 0 .957 4.962L3.964 7.294C4.672 5.167 6.656 3.58 9 3.58z"/>
                                            </svg>
                                        </span>
                                        <span>Iniciar sesión con Google</span>
                                    </a>
                                </div>

                                <div class="signin-other-title mt-4">
                                    <h5 class="fs-13 mb-0 title text-muted"><span class="bg-body px-3">O ingresa con tu correo</span></h5>
                                </div>
                            @endif

                            <div class="p-2 mt-4">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Ingrese su correo electrónico" required autocomplete="email" autofocus>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        @if (Route::has('password.request'))
                                            <div class="float-end">
                                                <a href="{{ route('password.request') }}" class="text-muted">¿Olvidó su contraseña?</a>
                                            </div>
                                        @endif
                                        <label class="form-label" for="password-input">Contraseña</label>
                                        <div class="position-relative auth-pass-inputgroup mb-3">
                                            <input type="password" class="form-control pe-5 password-input @error('password') is-invalid @enderror" placeholder="Ingrese su contraseña" id="password-input" name="password" required autocomplete="current-password">
                                            <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted shadow-none password-addon" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">Recordarme</label>
                                    </div>

                                    <div class="mt-4">
                                        <button class="btn btn-success w-100" type="submit">Iniciar Sesión</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- end card body -->
                    </div>
                    <!-- end card -->

                    @if (Route::has('register'))
                        <div class="mt-4 text-center">
                            <p class="mb-0">¿No tiene una cuenta? <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-underline"> Registrarse </a></p>
                        </div>
                    @endif

                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end auth page content -->

    <!-- footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <p class="mb-0 text-muted">&copy;
                            <script>document.write(new Date().getFullYear())</script> FELCC. Sistema de Gestión de Mandamientos
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end Footer -->
</div>
<!-- end auth-page-wrapper -->
@endsection

@section('scripts')
<!-- particles js -->
<script src="/assets/libs/particles.js/particles.js"></script>
<!-- particles app js -->
<script src="/assets/js/pages/particles.app.js"></script>
<!-- password-addon init -->
<script src="/assets/js/pages/password-addon.init.js"></script>
@endsection
