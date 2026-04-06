<form method="POST" id="mandamientoForm" action="/mandamientos">

    <input type="hidden" name="_method" id="formMethod" value="POST">
    <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-6">
                <div>
                    <label for="hoja_ruta" class="form-label">Hoja de Ruta</label>
                    <input type="text" class="form-control txtMayuscula" id="hoja_ruta"
                        placeholder="Ingrese Nro. de  hoja de ruta" name="hoja_ruta" value="">
                </div>
            </div><!--end col-->
            <div class="col-md-6">
                <div>
                    <label for="tipo_documento" class="form-label">Tipo </label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo_documento" id="rado-origina"
                                value="ORIGINAL">
                            <label class="form-check-label" for="rado-origina">ORIGINAL</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="tipo_documento" id="radio-fotocopia"
                                value="FOTOCOPIA" checked>
                            <label class="form-check-label" for="radio-fotocopia">FOTOCOPIA</label>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
            <div class="col-lg-6">
                <div>
                    <label for="id_persona" class="form-label">Persona <span id="btnPersona"
                            class="btn btn-sm btn-primary">+ Añadir</span></label>

                    <select name="id_persona" id="id_persona" class="form-select" required></select>

                </div>
            </div><!--end col-->


            <div class="col-lg-6">
                <div>
                    <label for="id_juzgado" class="form-label">Juzgado</label>
                    <select name="id_juzgado" id="id_juzgado" class="form-select" required></select>
                </div>
            </div><!--end col-->

            <div class="col-lg-6">
                <div>
                    <label for="id_delito" class="form-label">Delito</label>
                    <select name="id_delito" id="id_delito" class="form-select" required></select>
                </div>
            </div><!--end col-->
            <div class="col-lg-6">

                <div>
                    <label for="id_tipo_mandamiento" class="form-label">Tipo Mandamiento</label>

                    <select name="id_tipo_mandamiento" id="id_tipo_mandamiento" class="form-select" required>
                    </select>
                </div>
            </div><!--end col-->


            <div class="col-lg-6 ">
                <div>
                    <label for="asignado" class="form-label">Asignado A</label>
                    <input type="text" class="form-control txtMayuscula" id="asignado" placeholder="persona a cargo"
                        name="asignado" value="">
                </div>
            </div><!--end col-->
            <div class="col-lg-6 ">
                <div>
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" id="telefono" placeholder="Ingrese teléfono"
                        name="telefono" value="">
                </div>
            </div><!--end col-->
            <div class="col-12 ">
                <div>
                    <label for="actividades_realizadas" class="form-label">Actividades realizadas</label>
                    <textarea name="actividades_realizadas" id="actividades_realizadas" class="form-control" rows="2"></textarea>
                </div>
            </div><!--end col-->
            <div class="col-lg-6 ">
                <div>
                    <label for="domicilio" class="form-label">Domicilio</label>
                    <textarea name="domicilio" id="domicilio" class="form-control" rows="2"></textarea>
                </div>
            </div><!--end col-->
            <div class="col-lg-6 ">
                <div>
                    <label for="vehiculos" class="form-label">Vehículos</label>
                    <textarea name="vehiculos" id="vehiculos" class="form-control" rows="2"></textarea>
                </div>
            </div><!--end col-->

            <div class="col-12">
                <label for="imagen_mandamiento" class="form-label">Adjuntar Foto/PDF del
                    Mandamiento</label>
                <div class="input-group">
                    <input type="file" class="form-control" id="inputGroupFile02"
                        accept=".jpg,.png,.jpeg,.webp, .pdf" name="imagen_mandamiento">
                    <label class="input-group-text" for="inputGroupFile02">Seleccionar</label>
                </div>
            </div>
        </div><!--end col-->

        <fieldset class="border p-3 mt-3">
            <div class="row g-3">


                <div class="col-lg-6">
                    <div>

                        <label for="estado" class="form-label">Estado</label>
                        <input list="estados-sugeridos" class="form-control txtMayuscula" id="estado"
                            placeholder="Ingrese estado" name="estado" value="">

                        <datalist id="estados-sugeridos">
                            @foreach ($estados as $est)
                                <option value="{{ $est }}">
                            @endforeach

                        </datalist>


                    </div>
                </div><!--end col-->
                <div class="col-lg-6 requerido_ejecutado" style="display: none;">
                    <div>
                        <label for="fecha_ejecucion" class="form-label">Fecha Ejecución</label>
                        <input type="date" class="form-control" id="fecha_ejecucion"
                            max="{{ now()->format('Y-m-d') }}" placeholder="Ingrese fecha" name="fecha_ejecucion"
                            value="">
                    </div>
                </div><!--end col-->
                <div class="col-lg-6 requerido_ejecutado" style="display: none;">
                    <div>
                        <label for="ejecutado_por" class="form-label">Ejecutado Por</label>
                        <input type="text" class="form-control txtMayuscula" id="ejecutado_por"
                            placeholder="Ingrese nombre de quien ejecutó el mandamiento" name="ejecutado_por"
                            value="">
                    </div>
                </div><!--end col-->
                <div class="col-lg-6 requerido_ejecutado" style="display: none;">
                    <div>
                        <label for="acta_ejecucion" class="form-label">Subir Acta</label>
                        <input type="file" name="acta_ejecucion" class="form-control" id="acta_ejecucion"
                            accept="image/*, .pdf">
                    </div>
                </div><!--end col-->




            </div><!--end row-->
        </fieldset>



    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary" id="confirmSave">
            <i class="ri-save-3-line align-middle me-1"></i>
            Guardar
        </button>
    </div>
</form>
