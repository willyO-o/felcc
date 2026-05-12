# GUÍA DE USO - INTERFACE DE REPORTES

## 📋 Descripción General
La interfaz de reportes permite exportar datos de los 6 módulos principales en formato CSV o PDF, con opciones de filtrado personalizadas.

## 🎯 Módulos Disponibles

### 1. **Mandamientos** 🏢
- **Descripción**: Exporta todos los mandamientos registrados
- **Filtros disponibles**:
  - Estado (Pendiente, Ejecutado, Cancelado)
  - Tipo de Mandamiento
  - Juzgado
  - Rango de fechas
  
### 2. **Registro Criminal** ⚠️
- **Descripción**: Exporta el registro criminal de personas
- **Filtros disponibles**:
  - Especialidad (Robo, Narcotráfico, etc.)
  - División responsable
  - Rango de fechas

### 3. **Personas** 👥
- **Descripción**: Exporta lista de personas registradas
- **Filtros disponibles**:
  - Género
  - Estado Civil
  - País
  - Rango de fechas

### 4. **Celulares** 📱
- **Descripción**: Exporta teléfonos y números celulares
- **Filtros disponibles**:
  - Empresa Operadora (VIVA, ENTEL, TIGO)
  - Con Persona Asociada (Sí/No)
  - Rango de fechas

### 5. **IMEIs** 🔌
- **Descripción**: Exporta códigos IMEI de dispositivos
- **Filtros disponibles**: (A completar según necesidad)

### 6. **Vehículos** 🚗
- **Descripción**: Exporta vehículos y datos asociados
- **Filtros disponibles**:
  - Tipo de Vehículo (Auto, Moto, Camión)
  - Con Persona Asociada (Sí/No)
  - Rango de fechas

---

## 🚀 Cómo Usar

### Opción 1: Exportación Directa (Sin Filtros)
1. Ingresa a **Reportes** desde el menú principal
2. Selecciona el módulo que deseas exportar
3. Haz clic en:
   - **"Exportar CSV"** para descargar en Excel
   - **"Exportar PDF"** para descargar en PDF

### Opción 2: Exportación con Filtros
1. Ingresa a **Reportes** desde el menú principal
2. Selecciona el módulo que deseas exportar
3. Haz clic en **"Con Filtros"**
4. Se abrirá un modal donde puedes:
   - Establecer un rango de fechas
   - Aplicar filtros específicos del módulo
   - Buscar por texto (nombre, CI, placa, etc.)
   - Seleccionar qué campos incluir en el reporte
5. Selecciona el formato (CSV o PDF)
6. Haz clic en **"Exportar"**

---

## 📊 Campos Disponibles

### Campos Básicos
Incluye la información esencial de cada registro:
- Identificadores
- Nombres/Descripciones
- Fechas principales
- Estados

### Campos Detallados
Incluye información adicional:
- Descripciones completas
- Datos de contacto
- Información de ubicación
- Características especiales

### Auditoría
Incluye información de seguimiento:
- Usuario que creó el registro
- Fecha de creación
- Usuario que modificó
- Fecha de última modificación

---

## 💾 Formatos de Exportación

### CSV
- **Ventajas**:
  - Abre directamente en Excel
  - Compatible con programas de análisis
  - Ideal para manipular datos
  - Archivo más liviano

- **Uso**: Cuando necesitas analizar o procesar los datos

### PDF
- **Ventajas**:
  - Formato listo para imprimir
  - Mantiene el formato visual
  - Ideal para reportes formales
  - Compatible con cualquier lector

- **Uso**: Cuando necesitas un reporte profesional o impresión

---

## ⚙️ Configuración Recomendada

**Para reportes gerenciales/formales**:
- ✅ Seleccionar "Campos Básicos"
- ✅ Incluir "Auditoría"
- ✅ Exportar a PDF

**Para análisis de datos**:
- ✅ Seleccionar "Campos Detallados"
- ✅ Aplicar filtros específicos
- ✅ Exportar a CSV

**Para auditoría interna**:
- ✅ Seleccionar "Campos Básicos"
- ✅ Incluir "Auditoría"
- ✅ Exportar a CSV

---

## 🔍 Búsqueda General

El campo de búsqueda permite buscar en múltiples campos según el tipo de reporte:

- **Mandamientos**: Número de mandamiento, Persona, Juzgado
- **Registro Criminal**: Nombre, Alias, Especialidad
- **Personas**: Nombres, Apellidos, CI
- **Celulares**: Número celular, Persona
- **Vehículos**: Placa, Descripción, Responsable

---

## 📌 Notas Importantes

- ✅ Todos los datos se procesan localmente (no se envían a servidores externos)
- ✅ Los reportes no incluyen datos eliminados (soft deletes)
- ✅ Los filtros se aplican cumulativamente (AND)
- ✅ Puedes descargar múltiples reportes en una sesión

---

## 🆘 Soporte

Para problemas o sugerencias, contacta al administrador del sistema.

---

**Última actualización**: Maio 2026
