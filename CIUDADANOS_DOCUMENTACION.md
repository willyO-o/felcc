# Módulo de Gestión de Ciudadanos

## Descripción General

El módulo de ciudadanos es un sistema CRUD completo para gestionar información de ciudadanos registrados en el sistema. Está basado en la estructura del módulo de personas pero está completamente independiente y optimizado para evitar errores comunes.

## Características

### ✅ Funcionalidades Implementadas

- **CRUD Completo**: Crear, leer, actualizar y eliminar ciudadanos
- **Paginación AJAX**: Carga dinámica de datos sin recargar la página
- **Búsqueda Avanzada**: Búsqueda en múltiples campos
- **Filtros**:
  - Por Sexo (Masculino/Femenino)
  - Por Estado Civil (Soltero, Casado, etc.)
  - Por Estado del Registro (Activo/Inactivo)
  - Por Visibilidad (Activos, Inactivos, Eliminados)
- **Soft Delete**: Los registros eliminados se pueden restaurar
- **Validación**: Validación de datos en cliente y servidor
- **Manejo de Errores**: Mensaje de error claros y amigables
- **Seguridad**: CSRF tokens, SQL injection prevention (Eloquent)

## Estructura del Módulo

```
Modelo: app/Models/Ciudadano.php
Controlador: app/Http/Controllers/CiudadanoController.php
Vistas:
  - resources/views/ciudadanos/index.blade.php (Listado)
  - resources/views/ciudadanos/formulario.blade.php (Crear/Editar)
  - resources/views/ciudadanos/show.blade.php (Detalles)
  - resources/views/ciudadanos/partials/_frm-eliminar.blade.php (Modal eliminar)
JavaScript: public/assets/js/ciudadanos/index.js
Rutas: routes/web.php (Recursos registrados)
```

## Base de Datos

### Tabla: ciudadano

```sql
CREATE TABLE `ciudadano` (
  `id` bigint(20) unsigned NOT NULL,
  `ciudadano` varchar(255) DEFAULT NULL,
  `tipo_cedula_act` varchar(255) DEFAULT NULL,
  `cedula_act` varchar(255) DEFAULT NULL,
  `ap_pat` varchar(255) DEFAULT NULL,
  `ap_mat` varchar(255) DEFAULT NULL,
  `ap_esp` varchar(255) DEFAULT NULL,
  `nombres` varchar(255) DEFAULT NULL,
  `sexo` varchar(255) DEFAULT NULL,
  `estado_civil` varchar(50) DEFAULT NULL,
  `pais_nac` varchar(180) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `mesa_ciudadano` int(11) DEFAULT NULL,
  `partida_mesa_ciudadano` int(11) DEFAULT NULL,
  `fecha_ins` datetime DEFAULT NULL,
  `dom_1` text DEFAULT NULL,
  `dom_2` text DEFAULT NULL,
  `id_loc` int(11) DEFAULT NULL,
  `nom_dep` varchar(255) DEFAULT NULL,
  `nom_prov` varchar(255) DEFAULT NULL,
  `nom_mun` varchar(255) DEFAULT NULL,
  `ocupacion` varchar(255) DEFAULT NULL,
  `estado_registro` int(11) DEFAULT NULL,
  `id_departamento` int(11) DEFAULT NULL
)
```

## Rutas Disponibles

```php
GET    /ciudadanos                  // Listar ciudadanos
GET    /ciudadanos/create           // Formulario crear
POST   /ciudadanos                  // Guardar ciudadano
GET    /ciudadanos/{id}             // Ver detalles
GET    /ciudadanos/{id}/edit        // Formulario editar
PATCH  /ciudadanos/{id}             // Actualizar ciudadano
DELETE /ciudadanos/{id}             // Eliminar ciudadano

// Rutas adicionales
GET    /ciudadanos-search           // Buscar ciudadanos (AJAX)
GET    /ciudadanos/{id}/delete-modal // Modal de eliminación
POST   /ciudadanos-restore/{id}     // Restaurar eliminado
```

## Mejoras implementadas vs Módulo de Personas

### 1. **Mejor Manejo de Errores en JavaScript**

**Antes (Personas)**:
- Errores no capturados correctamente
- Falta validación de elementos HTML antes de usar

**Ahora (Ciudadanos)**:
```javascript
// Verificar existencia del elemento antes de usar
if ($form.length === 0 || $btnGuardar.length === 0) return;

// Mejor captura y visualización de errores
.fail(function (xhr) {
    if (xhr.status === 422) {
        const errors = xhr.responseJSON?.errors || {};
        // Manejo específico de errores de validación
    }
})
```

### 2. **Prevención de Memory Leaks**

**Mejora**: Limpieza correcta de listeners y modales

```javascript
// Cerrar todas las modales abiertas
const modals = document.querySelectorAll(".modal.show");
modals.forEach((modal) => {
    bootstrap.Modal.getInstance(modal)?.hide();
});
```

### 3. **Validación de Datos en Tiempo Real**

El modelo Ciudadano incluye:
- Accessors para formatear datos: `getNombreCompletoAttribute()`, `getCedulaFormattedAttribute()`
- Scopes para filtros: `scopeActivos()`, `scopePorSexo()`, `scopeBuscar()`
- Validación automática en el boot del modelo

### 4. **Mejor Gestión de Búsqueda**

```javascript
// Debounce para evitar múltiples requests
searchTimeout = setTimeout(() => {
    cargarCiudadanos(1);
}, 500); // Espera 500ms después de dejar de escribir
```

### 5. **Formateo Consistente**

Funciones dedicadas para formatear datos en la tabla:
- `formatSexo()`: M/F → Masculino/Femenino
- `formatEstadoCivil()`: Valores enum a strings legibles
- `getUbicacion()`: Concatena departamento/provincia/municipio

### 6. **XSS Protection**

```javascript
// Escapar HTML para prevenir ataques XSS
function escapeHtml(text) {
    if (!text) return "";
    return $("<div>").text(text).html();
}

// Uso en la tabla
<td>${escapeHtml(nombreCompleto)}</td>
```

### 7. **Estados Claros**

El módulo maneja correctamente:
- Registros activos/inactivos (estado_registro)
- Registros eliminados (soft delete)
- Estados visuales en la UI (badges de color)

### 8. **Validación Server-Side Mejorada**

```php
$reglas = [
    'nombres' => 'required|string|max:255',
    'cedula_act' => 'nullable|string|max:255',
    'sexo' => 'nullable|in:M,F,MASCULINO,FEMENINO',
    'estado_civil' => 'nullable|in:SOLTERO,CASADO,DIVORCIADO,VIUDO,UNION_LIBRE,CONYUGUE',
    // ... más validaciones específicas
];
```

## Uso del Módulo

### Acceder al Módulo

```
URL: /ciudadanos
```

### Crear un Ciudadano

1. Click en "Nuevo Ciudadano"
2. Completar formulario (nombre es obligatorio)
3. Click en "Guardar"

### Buscar Ciudadanos

- Usar barra de búsqueda para búsqueda por:
  - Nombre completo
  - Apellidos
  - Cédula
  - Ciudadano

### Filtrar

- **Sexo**: Filtrar por Masculino/Femenino
- **Estado Civil**: Filtrar por estado civil
- **Estado Registro**: Filtrar por activos/inactivos
- **Visibilidad**: Ver activos, todos, eliminados

### Editar

1. Click en botón Editar (lápiz)
2. Modificar datos
3. Click en "Guardar"

### Eliminar

1. Click en botón Eliminar (papelera)
2. Elegir tipo de eliminación:
   - **Eliminación Suave**: Marca como eliminado, se puede restaurar
   - **Eliminación Completa**: Elimina permanentemente
3. Confirmar

### Restaurar

Para registros eliminados (soft delete):
1. Seleccionar "Solo Eliminados" en filtro de visibilidad
2. Click en botón Restaurar (flecha circular)

## Permisos Requeridos

El módulo verifica los siguientes permisos:

- `ciudadanos_all`: Acceso total al módulo
- `ciudadanos_listar`: Ver listado
- `ciudadanos_crear`: Crear nuevos
- `ciudadanos_eliminar`: Eliminar registros

## Consideraciones de Seguridad

✅ **Implementado**:
- CSRF Token en formularios
- Prepared statements (Eloquent)
- Validación de permisos
- Escapado de HTML en vistas
- Validación server-side obligatoria

## Troubleshooting

### Los ciudadanos no se cargan

1. Verificar permisos: `ciudadanos_listar`
2. Verificar conexión a BD
3. Revisar consola del navegador (F12)

### Error al guardar

1. Completar todos los campos obligatorios
2. Verificar formato de datos
3. Revisar errores en la consola

### Búsqueda lenta

- Esto es normal con muchos registros
- Implementar indexación en BD si es necesario
- Aumentar el debounce a 1000ms en index.js

## Próximas Mejoras (Opcional)

- [ ] Exportar a CSV
- [ ] Importación masiva
- [ ] Reporte PDF
- [ ] Auditoría de cambios
- [ ] Fotos/multimedia
- [ ] Historial de cambios
- [ ] Búsqueda avanzada
- [ ] Integración con departamentos/provincias

## Soporte y Mantenimiento

Para reportar bugs o sugerencias, contacta al equipo de desarrollo.

---

**Versión**: 1.0.0  
**Fecha**: 2026-06-09  
**Autor**: Sistema de Gestión FELCC  
**Status**: ✅ Activo y Funcional
