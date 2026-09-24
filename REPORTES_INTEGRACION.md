# 🔧 INSTRUCCIONES DE INTEGRACIÓN - Interface de Reportes

## ✅ TAREAS COMPLETADAS

### 1. Backend - Controlador
- ✅ ReporteController actualizado con estructura base
- ✅ Métodos `index()`, `formulario()`, `exportar()` listos
- ✅ Métodos privados para obtener datos (placeholders listos)

### 2. Frontend - Vistas
- ✅ Dashboard principal (`reportes/index.blade.php`)
  - 6 tarjetas con iconos y colores distintos
  - Modal para filtros
  - Interfaz intuitiva y responsive
  
- ✅ Formulario detallado (`reportes/formulario.blade.php`)
  - Filtros específicos por módulo
  - Selector de campos
  - Resumen de datos
  - Diseño 2 columnas

### 3. Rutas
- ✅ 3 rutas agregadas en `routes/web.php`
- ✅ Dentro del middleware `auth`
- ✅ Nombres de rutas compatibles

### 4. Navegación
- ✅ Componente `sidebar-reportes.blade.php` creado

### 5. Documentación
- ✅ Guía de uso (`REPORTES_GUIA_USO.md`)
- ✅ Documentación técnica (`REPORTES_DOCUMENTACION_TECNICA.md`)

---

## 🚀 PRÓXIMAS TAREAS (Para completar la funcionalidad)

### PASO 1: Instalar Dependencias Necesarias
```bash
# Para CSV (opcional, Laravel maneja bien con arrays)
composer require maatwebsite/excel

# Para validación mejorada
composer require laravel/validation
```

### PASO 2: Implementar Exportación a CSV
En `app/Http/Controllers/ReporteController.php`, reemplazar el método `generarCSV()`:

```php
private function generarCSV($datos, $nombreArchivo)
{
    $response = response()->streamDownload(function () use ($datos) {
        $file = fopen('php://output', 'w');
        
        if (!empty($datos)) {
            // Escribir encabezados
            fputcsv($file, array_keys($datos[0]));
            
            // Escribir datos
            foreach ($datos as $row) {
                fputcsv($file, $row);
            }
        }
        
        fclose($file);
    }, $nombreArchivo, [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename=' . $nombreArchivo,
    ]);

    return $response;
}
```

### PASO 3: Implementar métodos para obtener datos

#### Mandamientos:
```php
private function obtenerMandamientos($request)
{
    $query = Mandamiento::with(['persona', 'juzgado', 'delito'])
        ->where('deleted_at', null);
    
    // Filtro de fecha
    if ($request->fecha_desde) {
        $query->whereDate('fecha_ejecucion', '>=', $request->fecha_desde);
    }
    if ($request->fecha_hasta) {
        $query->whereDate('fecha_ejecucion', '<=', $request->fecha_hasta);
    }
    
    // Filtro de estado
    if ($request->estado) {
        $query->where('estado', $request->estado);
    }
    
    // Búsqueda general
    if ($request->buscar) {
        $query->where('hoja_ruta', 'LIKE', '%' . $request->buscar . '%')
              ->orWhereHas('persona', function($q) {
                  $q->where('nombres', 'LIKE', '%' . $request->buscar . '%')
                    ->orWhere('apellidos', 'LIKE', '%' . $request->buscar . '%');
              });
    }
    
    return $query->get()->map(function($m) {
        return [
            'Hoja Ruta' => $m->hoja_ruta,
            'Estado' => $m->estado,
            'Persona' => $m->persona->nombres ?? 'N/A',
            'Juzgado' => $m->juzgado->nombre ?? 'N/A',
            'Fecha Ejecución' => $m->fecha_ejecucion,
            'Creado' => $m->created_at,
        ];
    })->toArray();
}
```

#### Personas:
```php
private function obtenerPersonas($request)
{
    $query = Persona::where('deleted_at', null);
    
    // Filtro de fecha
    if ($request->fecha_desde) {
        $query->whereDate('created_at', '>=', $request->fecha_desde);
    }
    if ($request->fecha_hasta) {
        $query->whereDate('created_at', '<=', $request->fecha_hasta);
    }
    
    // Filtro de género
    if ($request->genero) {
        $query->where('genero', $request->genero);
    }
    
    // Búsqueda
    if ($request->buscar) {
        $query->where('nombres', 'LIKE', '%' . $request->buscar . '%')
              ->orWhere('apellidos', 'LIKE', '%' . $request->buscar . '%')
              ->orWhere('ci', 'LIKE', '%' . $request->buscar . '%');
    }
    
    return $query->get()->map(function($p) {
        return [
            'Nombre' => $p->nombres . ' ' . $p->apellidos,
            'CI' => $p->ci,
            'Género' => $p->genero,
            'Teléfono' => $p->telefono,
            'Domicilio' => $p->domicilio,
            'Creado' => $p->created_at,
        ];
    })->toArray();
}
```

### PASO 4: Implementar Exportación a PDF

Usar la clase existente `ReportesPdf`:

```php
private function exportarPDF($tipo, Request $request)
{
    $datos = [];
    
    switch($tipo) {
        case 'mandamientos':
            $datos = $this->obtenerMandamientos($request);
            break;
        // ... otros casos
    }
    
    // Usar ReportesPdf para generar PDF
    $this->reportesPdf->generarReporte($datos, ucfirst($tipo));
}
```

### PASO 5: Agregar enlace en el menú lateral

En `resources/views/layouts/app.blade.php` o donde esté el sidebar:
```blade
@include('layouts.partials.sidebar-reportes')
```

### PASO 6: Testing

Acceder a: `http://localhost/reportes`

Verificar:
- [ ] Dashboard carga correctamente
- [ ] Tarjetas se visualizan bien
- [ ] Modal de filtros abre/cierra
- [ ] Clics en botones no generan errores
- [ ] Links de navegación funcionan

---

## 📋 CHECKLIST PARA COMPLETAR

```
[ ] Instalar dependencias (Excel si se desea)
[ ] Implementar método generarCSV()
[ ] Implementar obtenerMandamientos()
[ ] Implementar obtenerRegistroCriminal()
[ ] Implementar obtenerPersonas()
[ ] Implementar obtenerCelulares()
[ ] Implementar obtenerImeis()
[ ] Implementar obtenerVehiculos()
[ ] Implementar exportarPDF()
[ ] Agregar validaciones de filtros
[ ] Agregar tests unitarios
[ ] Agregar enlace en el menu principal
[ ] Pruebas E2E en todos los módulos
[ ] Documentar campos específicos de cada módulo
[ ] Configurar permisos por rol (si es necesario)
```

---

## 🎨 PERSONALIZACIÓN

### Cambiar colores de tarjetas
En `resources/views/reportes/index.blade.php`, modificar el array `$reportes`:
```php
[
    'color' => 'primary',  // Cambiar a: danger, info, success, warning, secondary
    ...
]
```

### Agregar más filtros
En `resources/views/reportes/formulario.blade.php`, agregar en el switch case correspondiente:
```blade
@case('mandamientos')
    <!-- Agregar aquí nuevos campos -->
@endcase
```

### Cambiar iconos
Usar iconos de MaterialDesignIcons: https://materialdesignicons.com/

Ejemplo: `mdi mdi-file-chart` → `mdi mdi-chart-box`

---

## 🔐 SEGURIDAD

Consideraciones implementadas:
- ✅ Rutas protegidas con middleware `auth`
- ✅ Usando Eloquent (previene SQL injection)
- ✅ Soft deletes (no incluye datos borrados)

Consideraciones futuras:
- [ ] Validar permisos por módulo
- [ ] Auditar descargas de reportes
- [ ] Limitar cantidad de registros por reporte
- [ ] Cifrar reportes sensibles

---

## 📞 SOPORTE

Si necesitas ayuda con la implementación de algún método específico, proporciona:
1. El módulo que necesitas completar
2. Los campos exactos que deseas exportar
3. Los filtros adicionales requeridos

---

**Creado**: Maio 2026
**Estado**: UI Completa - Funcionalidad Pendiente
**Versión**: 1.0
