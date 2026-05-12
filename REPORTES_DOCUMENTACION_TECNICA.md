<!-- Documentación Técnica - Interface de Reportes -->

# 📚 Documentación Técnica - Interface de Reportes

## Estructura de Carpetas

```
resources/
└── views/
    └── reportes/
        ├── index.blade.php          (Dashboard principal)
        └── formulario.blade.php     (Formulario con filtros)

routes/
└── web.php                          (Rutas agregadas)

app/
└── Http/
    └── Controllers/
        └── ReporteController.php    (Controlador actualizado)
```

## Rutas Disponibles

```php
GET  /reportes                          → ReporteController@index
GET  /reportes/formulario/{tipo}        → ReporteController@formulario
GET  /reportes/exportar                 → ReporteController@exportar
```

## Controlador - ReporteController.php

### Métodos Principales

#### 1. `index()` 
- **Propósito**: Mostrar dashboard principal
- **Retorna**: Vista con array de reportes disponibles
- **Variables de vista**:
  - `$reportes` (array con config de cada reporte)

#### 2. `formulario($tipo)`
- **Propósito**: Mostrar formulario de filtros específicos
- **Parámetros**: 
  - `$tipo`: mandamientos, registro-criminal, personas, celulares, imeis, vehiculos
- **Retorna**: Vista de formulario con filtros
- **Variables de vista**:
  - `$tipo` (tipo de reporte)
  - `$datos` (conteos de cada módulo)

#### 3. `exportar(Request $request)`
- **Propósito**: Procesar exportación
- **Parámetros esperados**:
  - `tipo`: tipo de reporte
  - `formato`: csv o pdf
  - `fecha_desde`, `fecha_hasta`: filtros de fecha
  - `campos[]`: campos a incluir
  - `buscar`: texto de búsqueda
  - Otros filtros específicos por tipo
- **Retorna**: Archivo descargado o respuesta JSON

### Métodos Privados (A Implementar)

```php
private obtenerMandamientos($request)      // Obtener datos filtrados
private obtenerRegistroCriminal($request)  // Obtener datos filtrados
private obtenerPersonas($request)          // Obtener datos filtrados
private obtenerCelulares($request)         // Obtener datos filtrados
private obtenerImeis($request)             // Obtener datos filtrados
private obtenerVehiculos($request)         // Obtener datos filtrados
private generarCSV($datos, $nombreArchivo) // Generar archivo CSV
private exportarPDF($tipo, $request)       // Generar archivo PDF
```

## Modelos Utilizados

| Modelo | Tabla | Campos Clave |
|--------|-------|--------------|
| Mandamiento | mandamiento | hoja_ruta, estado, fecha_ejecucion, id_persona, id_juzgado |
| RegistroCriminal | registro_criminal | nro_registro, fecha_registro, especialidad, id_persona, id_division |
| Persona | persona | nombres, apellidos, ci, genero, estado_civil, id_pais |
| Telefono | telefono | numero_celular, empresa, persona_id, callapp, truecall |
| Imei | imei | imei, caracteristicas |
| Vehiculo | vehiculo | placa, descripcion, responsable, caso_relacionado |

## Variables de Sesión/Request

| Variable | Tipo | Descripción |
|----------|------|-------------|
| `tipo` | string | Tipo de reporte a exportar |
| `formato` | string | Formato de exportación (csv/pdf) |
| `fecha_desde` | date | Fecha inicial del filtro |
| `fecha_hasta` | date | Fecha final del filtro |
| `campos[]` | array | Campos a incluir (basico, detallado, auditoria) |
| `buscar` | string | Texto de búsqueda general |
| Estado/tipo filtro específico | various | Según el tipo de reporte |

## Flujo de Datos

```
Usuario selecciona módulo
    ↓
[Index] Dashboard con 6 tarjetas
    ↓
Usuario elige: Con Filtros / Exportar Directo
    ↓
├─ Con Filtros → [Formulario] Con opciones específicas → Exportar
│
└─ Exportar Directo → [Exportar] Sin filtros → Archivo
    ↓
ReporteController@exportar
    ├─ Obtiene datos (métodos privados)
    ├─ Aplica filtros
    ├─ Selecciona campos
    ├─ Formatea datos
    └─ Descarga archivo
```

## Estilos CSS Customizados

```css
/* Gradiente para headers */
.bg-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }

/* Colores por tipo de badge */
.bg-primary     /* Mandamientos */
.bg-danger      /* Registro Criminal */
.bg-info        /* Personas */
.bg-success     /* Celulares */
.bg-warning     /* IMEIs */
.bg-secondary   /* Vehículos */
```

## Validaciones (A Implementar)

1. **Fechas**:
   - fecha_desde debe ser menor o igual a fecha_hasta
   - No pueden ser fechas futuras

2. **Filtros**:
   - Validar que el tipo existe
   - Validar que el formato es csv o pdf
   - Validar que los campos seleccionados son válidos

3. **Permisos**:
   - Debe estar autenticado
   - Puede haber permisos por módulo (future)

## Opciones de Mejora Futura

- [ ] Guardar reportes frecuentes como "plantillas"
- [ ] Programar exportaciones automáticas
- [ ] Enviar reportes por email
- [ ] Historial de exportaciones
- [ ] Vista previa de datos antes de exportar
- [ ] Más opciones de filtrado avanzado
- [ ] Gráficos en PDF
- [ ] Reportes combinados (múltiples módulos)
- [ ] Exportación a Excel con múltiples hojas
- [ ] Cifrado de reportes sensibles

## Dependencias

- **Laravel**: Framework base
- **Maatwebsite/Excel**: Para generar CSV (opcional, si se instala)
- **barryvdh/laravel-dompdf**: Para generar PDF (ya existe ReportesPdf)
- **Bootstrap 5**: Para estilos (ya está en Velzon)

## Testing

```bash
# Rutas de prueba
GET /reportes
GET /reportes/formulario/mandamientos
GET /reportes/exportar?tipo=personas&formato=csv

# Con filtros
GET /reportes/exportar?tipo=mandamientos&formato=csv&estado=pendiente&fecha_desde=2026-01-01
```

---

**Última actualización**: Maio 2026
**Versión**: 1.0 (UI Completada, Exportación Pendiente)
