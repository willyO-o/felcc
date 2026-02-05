 <div class="modal-header">
     <h5 class="modal-title" id="miModalLabel">Registrar Mandamiento</h5>
     <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
 </div>
 <form
     action="{{ isset($mandamientos->id) ? route('mandamientos.update', $mandamientos->id) : route('mandamientos.store') }}"
     method="POST" id="mandamientoForm">
     @csrf
     @if (isset($mandamientos->id))
         @method('PUT')
     @endif



     <div class="modal-body">
         <div class="row g-3">
             <div class="col-md-6">
                 <div>
                     <label for="hoja_ruta" class="form-label">Hoja de Ruta</label>
                     <input type="text" class="form-control" id="hoja_ruta"
                         placeholder="Ingrese Nro. de  hoja de ruta" name="hoja_ruta"
                         value="{{ old('hoja_ruta', $mandamientos->hoja_ruta ?? '') }}">
                 </div>
             </div><!--end col-->
             <div class="col-md-6">
                 <div>
                     <label for="tipo_documento" class="form-label">Tipo Documento</label>
                     <input type="text" class="form-control" id="tipo_documento" placeholder="Ingrese Tipo Documento"
                         name="tipo_documento" value="{{ old('tipo_documento', $mandamientos->tipo_documento ?? '') }}">
                 </div>
             </div><!--end col-->
             <div class="col-xxl-6">
                 <div>
                     <label for="id_persona" class="form-label">Nombre</label>

                     <select name="id_persona" id="id_persona" class="form-select"></select>

                 </div>
             </div><!--end col-->


             <div class="col-xxl-6">
                 <div>
                     <label for="id_juzgado" class="form-label">Juzgado</label>
                     <select name="id_juzgado" id="id_juzgado" class="form-select"></select>
                 </div>
             </div><!--end col-->

             <div class="col-xxl-6">
                 <div>
                     <label for="id_delito" class="form-label">Delito</label>
                     <select name="id_delito" id="id_delito" class="form-select"></select>
                 </div>
             </div><!--end col-->
             <div class="col-xxl-6">
                 <label for="id_tipo_mandamiento" class="form-label">Tipo Mandamiento</label>

                 <div class="input-group">

                     <select name="id_tipo_mandamiento" id="id_tipo_mandamiento" class="form-select">
                         @foreach ($tipoMandamientos as $tipo)
                             <option value="{{ $tipo->id }}"
                                 {{ old('id_tipo_mandamiento', $mandamientos->id_tipo_mandamiento ?? '') == $tipo->id ? 'selected' : '' }}>
                                 {{ $tipo->tipo_mandamiento }}
                             </option>
                         @endforeach
                     </select>
                     <span class="input-group-text btn btn-info"><i class="ri-add-line"></i> </span>
                 </div>
             </div><!--end col-->

             <div class="col-lg-12">
                 <label for="estado" class="form-label">Estado</label>
                 <div>
                     <div class="form-check form-check-inline">
                         <input class="form-check-input" type="radio" name="estado" id="inlineRadio2"
                             value="PENDIENTE"
                             {{ old('estado', $mandamientos->estado ?? 'PENDIENTE') == 'PENDIENTE' ? 'checked' : '' }}>
                         <label class="form-check-label" for="inlineRadio2">PENDIENTE</label>
                     </div>
                     <div class="form-check form-check-inline">
                         <input class="form-check-input" type="radio" name="estado" id="inlineRadio1"
                             value="EJECUTADO"
                             {{ old('estado', $mandamientos->estado ?? '') == 'EJECUTADO' ? 'checked' : '' }}>
                         <label class="form-check-label" for="inlineRadio1">EJECUTADO</label>
                     </div>

                     <div class="form-check form-check-inline">
                         <input class="form-check-input" type="radio" name="estado" id="inlineRadio3"
                             value="CANCELADO"
                             {{ old('estado', $mandamientos->estado ?? '') == 'CANCELADO' ? 'checked' : '' }}>
                         <label class="form-check-label" for="inlineRadio3">CANCELADO</label>
                     </div>
                 </div>
             </div><!--end col-->
             <div class="col-md-12">
                 <div>
                     <label for="asignado" class="form-label">Asignado</label>
                     <input type="text" class="form-control" id="asignado" placeholder="Ingrese asignado"
                         name="asignado" value="{{ old('asignado', $mandamientos->asignado ?? '') }}">
                 </div>
             </div><!--end col-->


         </div><!--end row-->
     </div>
     <div class="modal-footer">
         <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
         <button type="button" class="btn btn-primary" id="confirmSave">
             <i class="ri-save-3-line align-middle me-1"></i>
             Guardar
         </button>
     </div>
 </form>
