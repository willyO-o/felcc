@extends('layouts.app')

@section('page-title', 'Mi Perfil')

@section('breadcrumb')
    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Mi Perfil</li>
        </ol>
    </div>
@endsection

@section('content')
    <div class="row">
        {{-- Info del usuario --}}
        <div class="col-xxl-3">
            <div class="card">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                            <div class="avatar-lg">
                                <div class="avatar-title bg-soft-primary text-primary rounded-circle fs-24">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            </div>
                        </div>
                        <h5 class="fs-16 mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-0">
                            @if($user->role)
                                <span class="badge bg-{{ $user->role->nombre === 'superadmin' ? 'danger' : ($user->role->nombre === 'administrador' ? 'primary' : 'warning') }}">
                                    {{ ucfirst($user->role->nombre) }}
                                </span>
                            @else
                                <span class="badge bg-secondary">Sin Rol</span>
                            @endif
                        </p>
                    </div>

                    <div class="mt-4">
                        <div class="table-responsive">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                    <tr>
                                        <th class="ps-0" scope="row">Nombre:</th>
                                        <td class="text-muted">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Email:</th>
                                        <td class="text-muted">{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th class="ps-0" scope="row">Registro:</th>
                                        <td class="text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="col-xxl-9">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ri-lock-password-line align-middle me-1"></i> Cambiar Contraseña
                    </h5>
                </div>
                <div class="card-body">
                    <form id="formCambiarPassword">
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label for="current_password" class="form-label">Contraseña Actual <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="current_password"
                                        name="current_password" placeholder="Ingrese contraseña actual">
                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted toggle-password"
                                        type="button" data-target="current_password">
                                        <i class="ri-eye-fill align-middle"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="error-current_password"></div>
                            </div>

                            <div class="col-lg-4">
                                <label for="password" class="form-label">Nueva Contraseña <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="password"
                                        name="password" placeholder="Ingrese nueva contraseña">
                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted toggle-password"
                                        type="button" data-target="password">
                                        <i class="ri-eye-fill align-middle"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="error-password"></div>
                            </div>

                            <div class="col-lg-4">
                                <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Confirme nueva contraseña">
                                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted toggle-password"
                                        type="button" data-target="password_confirmation">
                                        <i class="ri-eye-fill align-middle"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="error-password_confirmation"></div>
                            </div>

                            <div class="col-lg-12">
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary" id="btnCambiarPassword">
                                        <i class="ri-save-3-line align-middle me-1"></i> Cambiar Contraseña
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('js')
<script>
(function() {
    "use strict";

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");

    // Toggle visibilidad contraseña
    document.querySelectorAll(".toggle-password").forEach(function(btn) {
        btn.addEventListener("click", function() {
            const input = document.getElementById(this.dataset.target);
            input.type = input.type === "password" ? "text" : "password";
        });
    });

    // Enviar formulario
    const form = document.getElementById("formCambiarPassword");
    form.addEventListener("submit", function(e) {
        e.preventDefault();

        // Limpiar errores
        form.querySelectorAll(".is-invalid").forEach(el => el.classList.remove("is-invalid"));

        const btn = document.getElementById("btnCambiarPassword");
        btn.disabled = true;
        btn.innerHTML = '<i class="ri-loader-4-line align-middle me-1"></i> Guardando...';

        const formData = new FormData(form);

        fetch("{{ route('perfil.password') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrfToken,
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "application/json",
            },
            body: formData,
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-save-3-line align-middle me-1"></i> Cambiar Contraseña';

            if (status === 422 && body.errors) {
                Object.keys(body.errors).forEach(field => {
                    const input = form.querySelector('[name="' + field + '"]');
                    const errorDiv = form.querySelector('#error-' + field);
                    if (input) input.classList.add("is-invalid");
                    if (errorDiv) errorDiv.textContent = body.errors[field][0];
                });
                return;
            }

            if (status >= 400) {
                Swal.fire("Error", body.error || "Ocurrió un error.", "error");
                return;
            }

            form.reset();
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: body.success,
                timer: 2500,
                showConfirmButton: false,
            });
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ri-save-3-line align-middle me-1"></i> Cambiar Contraseña';
            Swal.fire("Error", "Error de conexión.", "error");
        });
    });
})();
</script>
@endsection
