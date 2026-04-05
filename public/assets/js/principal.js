
$(function () {
    'use strict';



    window.processError = function (jqXHR, element = null) {

        // console.log(jqXHR);

        if (jqXHR.status == 422) {
            let errors = jqXHR.responseJSON.errors;
            let html = convertErrors(errors);
            Swal.fire({
                title: 'Error!.',
                html: html,
                icon: 'error',
                confirmButtonText: 'Aceptar',
            })
                .then((result) => {
                    if (element) {
                        element.resume();
                    }
                })
        }

        if (jqXHR.status == 500) {

            let mensage = jqXHR.responseJSON.message || "Ocurrió un error inesperado, por favor intente nuevamente";

            // verificar  si existe el texto  : "SQLSTATE[23000]" en el mensaje
            if (mensage.indexOf("SQLSTATE[23000]") != -1) {
                mensage = "No se puede eliminar el registro, porque tiene elementos relacionados";
            }

            Swal.fire({
                title: 'Error',
                html: mensage,
                icon: 'error',
                confirmButtonText: 'Aceptar'
            })
        }

        if (jqXHR.status == 404) {
            Swal.fire({
                title: 'Error',
                html: "No se Encontró el recurso solicitado",
                icon: 'error',
                confirmButtonText: 'Aceptar'
            })
        }
        if (jqXHR.status == 403) {
            Swal.fire({
                title: 'Error',
                html: jqXHR.responseJSON.message || "Ocurrió un error inesperado, por favor intente nuevamente",
                icon: 'error',
                confirmButtonText: 'Aceptar'
            })
        }

        if (jqXHR.status == 423) {
            Swal.fire({
                title: 'Error',
                html: jqXHR.responseJSON.message || "Ocurrió un error inesperado, por favor intente nuevamente",
                icon: 'error',
                confirmButtonText: 'Aceptar'
            })
        }

        if (jqXHR.status == 0) {
            Swal.fire({
                title: 'Error',
                html: jqXHR.responseJSON.message || "Ocurrio un error inesperado, se recargará la página",
                icon: 'error',
                confirmButtonText: 'Aceptar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();

                }
            })


        }

    }

    window.notification = function (message, altMessage, time = 9000, type = "success", position = "top") {

        // const clasNotify = {
        //     "success": "bg-light text-dark border-bottom  border-success border-3 rounded-0 shadow-lg  mdi mdi-check-circle mdi-18px notify-icon",
        //     "danger": "bg-light text-dark border-bottom  border-danger border-3 rounded-0 shadow-lg  mdi mdi-close-circle mdi-18px notify-icon",
        //     "warning": "bg-light text-dark border-bottom  border-warning border-3 rounded-0 shadow-lg  mdi mdi-alert-circle mdi-18px notify-icon",
        //     "info": "bg-light text-dark border-bottom  border-info border-3 rounded-0 shadow-lg  mdi mdi-information-outline mdi-18px notify-icon",
        // }

        // Toastify({
        //     text: message || altMessage,
        //     duration: time,
        //     newWindow: true,
        //     close: true,
        //     gravity: "top",
        //     position: position,
        //     stopOnFocus: true,
        //     className: clasNotify[type || "info"],
        //     stopOnFocus: true,
        // }).showToast();


        const toast = Swal.mixin({
            toast: true,
            position: position,
            showConfirmButton: false,
            timer: time,
            timerProgressBar: true,
            customClass: {
                popup: 'bg-light',
                timerProgressBar: 'bg-' + type,
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
                // toast.addEventListener('click', Swal.close)
            }
        })

        toast.fire({
            icon: type,
            title: message || altMessage
        })


    }

    window.confirmarEnvio = async function (txtButon = 'Sí, Registrar!', mensaje = "Confirma si desea Registrar la Inscripción") {
        let result = await Swal.fire({
            title: '¿Estás seguro?',
            icon: 'warning',
            html: mensaje,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: txtButon,
            cancelButtonText: 'No, cancelar',
        });

        return result.isConfirmed;
    }


    window.loadingTable = function (tBody = "", message = "Cargando...") {
        let html = /*html*/`
        <tr>
            <td colspan="100" class="text-center">
                <div class="text-center mt-3">
                    <a href="javascript:void(0);" class="text-success "><i class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i> ${message} </a>
                </div>
            </td>
        </tr>
        `;

        if (tBody == undefined || tBody == null || tBody == "") {
            return html;
        }

        $(tBody).html(html);

    }

    window.loadingDiv = function (div = "", message = "Cargando...") {
        let html = /*html*/`
        <div class="text-center mt-3 py-5">
            <a href="javascript:void(0);" class="text-success "><i class="mdi mdi-loading mdi-spin fs-20 align-middle me-2"></i> ${message} </a>
        </div>
        `;
        if (div == undefined || div == null || div == "") {
            return html;
        }

        $(div).html(html);

    }



    window.convertErrors = function (errors) {
        let html = "";

        if (typeof errors == "string") {
            return `<p class="text-danger">${errors}</p>`;
        }

        if (typeof errors == "object") {
            Object.keys(errors).forEach(function (key) {
                html += /*html*/`<p class="text-danger">${errors[key]}</p>`
            });

            return html;
        }

        if (typeof errors == "array") {
            errors.forEach(function (error) {
                html += /*html*/`<p class="text-danger">${error}</p>`
            });
            return html;
        }

        if (typeof errors == "undefined") {
            return "Ocurrió un error inesperado, por favor intente nuevamente";
        }

        return html;
    }

    window.fomatDate = function (fecha, formato = 'f') {


        // Asegurarse de que la fecha se interpreta como UTC

        if (fecha == null || fecha == undefined || fecha == '' || fecha == '0000-00-00') {
            return '';
        }

        const meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];

        const parsedDate = new Date(fecha + (formato == 'f' ? 'T00:00:00' : 'Z'));
        // const parsedDate = new Date(fecha +'T00:00:00');


        const dia = parsedDate.getUTCDate();
        const mes = meses[parsedDate.getUTCMonth()];
        const año = parsedDate.getUTCFullYear();
        const horas = parsedDate.getUTCHours();
        const minutos = parsedDate.getUTCMinutes();
        const ampm = horas >= 12 ? 'pm' : 'am';

        const diaFormateado = dia < 10 ? `0${dia}` : dia;
        const horaFormateada = horas < 10 ? `0${horas}` : horas;
        const minutoFormateado = minutos < 10 ? `0${minutos}` : minutos;

        let fechaFormateada = fecha;

        if (formato == 'f') {
            fechaFormateada = `${diaFormateado} ${mes}, ${año}`;
        }
        if (formato == 'fh') {
            fechaFormateada = `${diaFormateado} ${mes}, ${año} - ${horaFormateada}:${minutoFormateado} ${ampm}`;
        }
        if (formato == 'h') {
            fechaFormateada = `${horaFormateada}:${minutoFormateado} ${ampm}`;
        }

        return fechaFormateada;



        // ANTERIOR
        // const meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
        // const parsedDate = new Date(fecha);

        // const dia = parsedDate.getDate();
        // const mes = meses[parsedDate.getMonth()];
        // const año = parsedDate.getFullYear();
        // const horas = parsedDate.getHours();
        // const minutos = parsedDate.getMinutes();
        // const ampm = horas >= 12 ? 'pm' : 'am';

        // const diaFormateado = dia < 10 ? `0${dia}` : dia;
        // const horaFormateada = horas < 10 ? `0${horas}` : horas;
        // const minutoFormateado = minutos < 10 ? `0${minutos}` : minutos;

        // let fechaFormateada = fecha;

        // if (formato == 'f') {
        //     fechaFormateada = `${diaFormateado} ${mes}, ${año}`;
        // }
        // if (formato == 'fh') {
        //     fechaFormateada = `${diaFormateado} ${mes}, ${año} - ${horaFormateada}:${minutoFormateado} ${ampm}`;
        // }

        // return fechaFormateada;

    }

    window.fechaInput = function (fecha) {

        if (fecha == null || fecha == undefined || fecha == '' || fecha == '0000-00-00') {
            return '';
        }
        const d = new Date(fecha);

        // Extraemos las partes (getMonth() empieza en 0, por eso sumamos 1)
        const mm = String(d.getUTCMonth() + 1).padStart(2, '0');
        const dd = String(d.getUTCDate()).padStart(2, '0');
        const yyyy = d.getUTCFullYear();

        const resultado = `${yyyy}-${mm}-${dd}`;
        return resultado;
    }

    window.calcularEdad = function (fecha) {


        // Asegurarse de que la fecha se interpreta como UTC

        if (fecha == null || fecha == undefined || fecha == '') {
            return '';
        }


        // calular edad exacta tomando en cuenta los dias y meses

        let fechaNacimiento = new Date(fecha);
        let fechaActual = new Date();

        let edad = fechaActual.getFullYear() - fechaNacimiento.getFullYear();
        let mes = fechaActual.getMonth() - fechaNacimiento.getMonth();
        let dia = fechaActual.getDate() - fechaNacimiento.getDate();

        if (mes < 0 || (mes == 0 && dia < 0)) {
            edad--;
        }


        return edad + " años";

    }

    // funcion para extraer primero nombre y un apellido si no existe 1 apellido tomar el otro campos nombre, paterno materno
    window.getFullName = function (datos, msj = "Sin Responsable") {
        let nombreCompleto = "";

        if (!datos.nombre) {
            return msj;
        }

        let arrNombre = datos.nombre.split(" ");
        nombreCompleto = arrNombre[0];
        nombreCompleto += " " + (datos.paterno || datos.materno);

        return nombreCompleto;
    }

    window.numeroMonto = function (valor) {

        if (valor == null || valor == undefined || valor == '' || valor == 0) {
            return '0.00';
        }

        let numero = Number(valor);
        const formatoNumerico = numero.toLocaleString('es-ES', { minimumFractionDigits: 2 });

        return formatoNumerico;


    }
    // convertir arrya en objeto segun una llave y un valor pasados como parametros
    window.arrayToObject = function (array, key, value) {
        let object = {};
        array.forEach(function (element) {
            object[element[key]] = element[value];
        });
        return object;
    }

    // funcion para completar plural  segun si el ultimo valor del texto es vocal o consonante
    window.textoPlural = function (text, lower = true) {

        if (text == null || text == undefined || text == '') {
            return '';
        }

        const vocales = ['a', 'e', 'i', 'o', 'u'];

        text = text.trim();
        if (lower) {
            text = text.toLowerCase();
        }

        let ultimoCaracter = text.charAt(text.length - 1);
        let textoPlural = text;

        if (vocales.includes(ultimoCaracter)) {
            textoPlural += 's';
        } else {
            textoPlural += 'es';
        }


        return textoPlural;

    }

    window.generatePassword = function (length = 10) {

        let charset = "abcdefghijklmnopqrstuvwxyz.ABCDEFGHIJKLMNOPQRSTUVWXYZ.0123456789.!#$%&/()=?¡*+{}[]-_.",
            retVal = "";
        for (let i = 0, n = charset.length; i < length; ++i) {
            retVal += charset.charAt(Math.floor(Math.random() * n));

        }
        return retVal;
    }


    window.completaarCeros = function (numero, longitud = 3) {
        let ceros = "";
        let cantidad = longitud - String(numero).length;
        for (let i = 0; i < cantidad; i++) {
            ceros += "0";
        }
        return ceros + numero;

    }

    window.extraerAnio = function (fecha) {
        let anio = fecha.split('-')[0];
        return anio;
    }



    $(document).on("input", ".txtMayuscula", function (e) {
        $(this).val($(this).val().toUpperCase());
    });

    $(document).on("input", ".txtNumero", function (e) {
        $(this).val($(this).val().replace(/[^0-9]/g, ''));
    });

    $(document).on("input", ".max-length", function (e) {
        let max = $(this).attr("maxlength");
        if ($(this).val().length > max) {
            $(this).val($(this).val().substr(0, max));
        }
    });

    $(document).on("input", ".sinEspacios", function (e) {
        $(this).val($(this).val().replace(/ /g, ''));
    });

    $(document).on("input", ".txtNormal", function (e) {
        // $(this).val($(this).val().replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ.*\-_+#:; \n]/g, ''));
        $(this).val($(this).val().replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ.*\/\-_+#:;()\r?\n ]/g, ''));
    });


    $(document).on("input", ".txtDesc", function (e) {
        // $(this).val($(this).val().replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ.*\-_+#:; \n]/g, ''));
        $(this).val($(this).val().replace(/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑ.,*\/\-_+#:;$()%\r?\n ]/g, ''));
    });


    $(document).on("input", ".expReg", function (e) {
        let expresion = $(this).attr("expresion");
        // validar la expresion

        if (expresion != undefined && expresion != null && expresion != "") {
            $(this).val($(this).val().replace(new RegExp(expresion, 'g'), ''));
        }

    });

    $(document).on("input", ".txtDecimal", function (e) {
        e.preventDefault();

        let inputValue = $(this).val();

        // Remover todos los caracteres no numéricos, excepto el primer punto decimal
        inputValue = inputValue.replace(/[^0-9.,]/g, '');
        inputValue = inputValue.replace(/,/g, '.');

        // Verificar si el valor contiene más de un punto decimal
        let decimalIndex = inputValue.indexOf('.');
        if (decimalIndex !== -1) {
            let decimalPart = inputValue.substring(decimalIndex + 1);
            if (decimalPart.length > 2) {
                decimalPart = decimalPart.slice(0, 2);
            }



            inputValue = inputValue.substring(0, decimalIndex + 1) + decimalPart;

            if (inputValue.length > 9) {
                inputValue = inputValue.slice(0, 8);
            }
        }

        // Verificar si hay más de un punto decimal y eliminar los adicionales
        let count = inputValue.split('.').length - 1;
        if (count > 1) {
            let firstDotIndex = inputValue.indexOf('.');
            let secondDotIndex = inputValue.indexOf('.', firstDotIndex + 1);
            inputValue = inputValue.substring(0, secondDotIndex) + inputValue.substring(secondDotIndex).replace(/\./g, '');
        }

        if (decimalIndex !== -1) {

            if (inputValue.length > 9) {
                inputValue = inputValue.slice(0, 8);
            }

        } else {
            if (inputValue.length > 6) {
                inputValue = inputValue.slice(0, 6);
            }
        }


        $(this).val(inputValue);

    })



    window.imagenesArray = function (texto) {
        if (texto == null || texto == undefined || texto == '') {
            return [];
        }

        try {
            let array = JSON.parse(texto);
            if (Array.isArray(array)) {
                return array;
            } else {
                return [];
            }
        } catch (error) {
            console.error("Error al parsear el texto a un array:", error);
            return [];
        }
    }

    window.primeraImagen = function (texto) {
        let imagenes = imagenesArray(texto);
        if (imagenes.length > 0) {
            return imagenes[0];
        } else {
            return '';
        }
    }

});
