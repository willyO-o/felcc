<div class="modal-header">
    <h5 class="modal-title" id="modalUsuarioLabel">
        {{ isset($user->id) ? 'Editar Usuario' : 'Registrar Usuario' }}
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form
    action="{{ isset($user->id) ? route('usuarios.update', $user->id) : route('usuarios.store') }}"
    method="POST" id="usuarioForm">
    @csrf
    @if (isset($user->id))
        @method('PUT')
    @endif

    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-12">
                <label for="name" class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $user->name ?? '') }}" placeholder="Ingrese nombre completo" required>
                <div class="invalid-feedback" id="error-name"></div>
            </div>

            <div class="col-md-12">
                <label for="email" class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email"
                    value="{{ old('email', $user->email ?? '') }}" placeholder="Ingrese correo electrónico" required>
                <div class="invalid-feedback" id="error-email"></div>
            </div>

            <div class="col-md-12">
                <label for="role_id" class="form-label">Rol <span class="text-danger">*</span></label>
                <select class="form-select" id="role_id" name="role_id" required>
                    <option value="">Seleccione un rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ old('role_id', $user->role_id ?? '') == $role->id ? 'selected' : '' }}>
                            {{ ucfirst($role->nombre) }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback" id="error-role_id"></div>
            </div>

            <div class="col-md-6">
                <label for="password" class="form-label">
                    Contraseña
                    @if (!isset($user->id))
                        <span class="text-danger">*</span>
                    @endif
                </label>
                <div class="position-relative auth-pass-inputgroup">
                    <input type="password" class="form-control pe-5" id="password" name="password"
                        placeholder="{{ isset($user->id) ? 'Dejar vacío para mantener' : 'Ingrese contraseña' }}"
                        {{ !isset($user->id) ? 'required' : '' }}>
                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon"
                        type="button" id="password-addon">
                        <i class="ri-eye-fill align-middle"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="error-password"></div>
            </div>

            <div class="col-md-6">
                <label for="password_confirmation" class="form-label">
                    Confirmar Contraseña
                    @if (!isset($user->id))
                        <span class="text-danger">*</span>
                    @endif
                </label>
                <div class="position-relative auth-pass-inputgroup">
                    <input type="password" class="form-control pe-5" id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirme contraseña"
                        {{ !isset($user->id) ? 'required' : '' }}>
                    <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon-confirm"
                        type="button" id="password-addon-confirm">
                        <i class="ri-eye-fill align-middle"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="error-password_confirmation"></div>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarUsuario">
            <i class="ri-save-3-line align-middle me-1"></i> Guardar
        </button>
    </div>
</form>
