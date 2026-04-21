<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;

    }

    .document-container {
        max-width: 8.5in;
        min-height: 13in;
        margin: 20px auto;
        padding: 0.5in 1in;
        background: white;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-family: 'Times New Roman', Times, serif;
        font-size: 12pt;
        line-height: 1.4;
        color: #333;
    }

    /* Encabezado */
    .document-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0;
        padding-bottom: 0;
        gap: 40px;
    }

    .header-left,
    .header-right {
        text-align: center;
        font-size: 9pt;
    }

    .header-left {
        flex: 1;
        /* padding-right: 30px; */
        display: flex;
        flex-direction: column;
        align-items: start;
        justify-content: center;
        min-height: 100px;

    }


    .header-right {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: end;
        justify-content: center;
        min-height: 100px;
    }

    .underline {
        text-decoration: underline;
    }

    .header-logo {
        font-weight: 900;
        /* margin-bottom: 5px; */
        text-align: center;
        font-size: 6pt;
    }

    .header-img img {
        width: 50px;
        height: auto;
        vertical-align: middle;
        margin-right: 8px;
        display: block;
        margin: 0 auto 5px auto;
    }

    .header-subtitle {
        font-size: 6pt;
        margin: 2px 0;
        font-weight: 900;
        text-align: center;

    }

    .case-number-container {
        display: flex;
        justify-content: flex-end;
    }

    /* Número de caso */
    .case-number {
        text-align: left;
        font-weight: bold;
        margin-bottom: 15px;
        font-size: 10pt;
    }

    /* Título principal */
    .document-title {
        text-align: center;
        font-weight: bold;
        font-size: 13pt;
        margin-bottom: 15px;
        padding: 8px;
        /* background: #ff00ff; */
        color: #000;
        text-decoration: underline;
    }

    /* Contenido */
    .document-content {
        margin-bottom: 15px;
    }

    .content-paragraph {
        text-align: justify;
        margin-bottom: 12px;
        font-size: 12pt;
        /* text-indent: 20px; */
    }

    .content-label {
        font-weight: bold;
        margin-top: 10px;
        margin-bottom: 5px;
    }

    .highlight-section {
        margin: 10px 0;
        font-size: 12pt;

    }

    .text-justify {
        text-align: justify;
    }

    .highlight-note {
        /* background: #ffccff; */
        padding: 8px;
        margin: 10px 0;
        font-size: 12pt;
        font-style: italic;
    }

    .bullet-point {
        /* margin-left: 20px; */
        text-indent: 60px;
        margin-bottom: 8px;
        text-align: justify;
    }

    .signature {
        display: flex;
        justify-content: space-between;
        font-size: 7pt;
    }

    /* Firma */
    .signature-section {
        margin-top: 30px;
        text-align: center;
        font-size: 7pt;

    }

    .margin-top {
        margin-top: 75px;
    }

    .signature-line {
        margin-top: 40px;
        border-top: 1px solid #000;
        display: inline-block;
        width: 200px;
    }

    .signature-name {
        margin-top: 5px;
        /* font-size: ; */
        /* font-weight: bold; */
    }

    .signature-title {
        font-size: 6pt;
        margin-top: 2px;
    }

    .footer-date {
        text-align: right;
        margin-top: 20px;
        font-size: 12pt;
    }

    /* Estilos de impresión */
    @media print {
        body {
            background: white;
            margin: 0;
            padding: 0;
        }

        .document-container {
            max-width: 100%;
            height: auto;
            margin: 0;
            padding: 0.5in;
            box-shadow: none;
        }

        .no-print {
            display: none !important;
        }
    }

    /* Botones para acción */
    .print-controls {
        text-align: center;
        margin: 20px 0;
    }

    .btn-print {
        padding: 10px 20px;
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin: 0 5px;
        font-size: 11pt;
    }

    .btn-print:hover {
        background: #0056b3;
    }

    .weight-bold {
        font-weight: bold;
    }

    .ident {
        text-indent: 40px;
    }


</style>

<div class="no-print print-controls">
    <button class="btn-print" id="generarPdf" >
        <i class="mdi mdi-file-powerpoint text-danger"></i> Generar PDF
    </button>
    <button class="btn-print d-none" id="editarPdf">✏️ Editar Ficha </button>
</div>

<div class="document-container" id="documentContent">
    <input type="hidden" id="registro_criminal_id" value="{{ $registrCriminalId }}">
    <input type="hidden" id="codigo" value="">
    <!-- ENCABEZADO -->
    <div class="document-header">
        <div class="header-left">
            <div class="left-contain">
                <div class="header-img">
                    <img src="{{ asset('felcc/img/felcc.png') }}" alt="Logo">
                </div>
                <div class="header-logo">
                    POLICIA BOLIVIANA <br>
                    DIRECCION NACIONAL <br>
                    FUERZA ESPECIAL DE LUCHA CONTRA EL CRIMEN <br>
                    La Paz - Bolivia
                </div>
            </div>

        </div>
        <div class="header-right">
            <div class="header-logo">"DEPARTAMENTO DE ANÁLISIS <br> CRIMINAL E INTELIGENCIA" "D.A.C.I."</div>
        </div>
    </div>

    <!-- NÚMERO DE CASO -->
    <div class="case-number-container">
        <div class="case-number">
            <div>No: {{ $nroFicha }}</div>
            <div contenteditable="true" data-field="caso_cud">CASO CUD - ...............✏️</div>
        </div>

    </div>

    <!-- TÍTULO PRINCIPAL -->
    <div class="document-title">FICHA DE REGISTRO</div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="document-content">
        <div class="content-paragraph" contenteditable="true" data-field="introduccion">{{$plantilla->introduccion }}</div>

        <div class="content-label">REQUIERE:</div>

        <div class="highlight-section ident" contenteditable="true" data-field="requerimiento">{{$plantilla->requerimiento }}</div>


        <div class="bullet-point weight-bold" contenteditable="true" data-field="persona">•  {{$plantilla->persona}}</div>

        <div class="highlight-section underline weight-bold text-justify " contenteditable="true"
            data-field="resultado_busqueda">{{$plantilla->resultado_busqueda }}</div>


        <div class="highlight-note text-justify weight-bold" style="margin-top: 15px;" contenteditable="true"
            data-field="nota_certificacion">{{$plantilla->nota_certificacion }}</div>


        <div class="highlight-section text-justify " contenteditable="true" data-field="nota_general">{{$plantilla->nota_general }}</div>

    </div>
    <div class="footer-date" contenteditable="true" data-field="fecha_literal">La Paz, {{ $fechaActual }}</div>
    <!-- FIRMA -->

    <div class="signature">
        <div class="signature-section">
            <div class="signature-line"></div>
            <div class="signature-name">Sgto. 2do. María Magdalena Huanca Churqui</div>
            <div class="signature-title weight-bold">PERSONAL DE SERVICIO DEL DEPARTAMENTO </div>
            <div class="signature-title weight-bold">ANÁLISIS CRIMINAL E INTELIGENCIA</div>
            <div class="signature-title weight-bold">REGISTRO FOTOSTATICO SOMÁTICO "GRIA"</div>
        </div>

        <!-- FIRMA ADICIONAL -->
        <div class="signature-section margin-top">
            <div class="signature-line"></div>

            <div class="signature-name">Cap. Alberto Marcio Poma García</div>
            <div class="signature-title weight-bold">JEFE DEL DEPARTAMENTO DE</div>
            <div class="signature-title weight-bold">ANÁLISIS CRIMINAL E INTELIGENCIA</div>
            <div class="signature-title weight-bold">"D.A.C.I."</div>
        </div>
    </div>


    <!-- FECHA FINAL -->

</div>

<div class=" d-none" id="pdfContainer" >
    <embed src="" type="" id="pdfViewer" class="w-100 " height="800px">
</div>
