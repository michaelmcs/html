@extends('layouts/formBusqueda')
<style>
    main {
        display: flex;
        justify-content: center !important;
        flex-wrap: wrap;
    }

    .container {
        margin: 0 !important;
    }

    table {
        width: 600px;
        max-width: 600px;
    }

    @media screen and (max-width:680px) {
        table {
            width: 100%;
            max-width: auto;
        }
    }
</style>
@section('resultado')
    @if (session('MENSAJE'))
        {{ session('MENSAJE') }}
    @endif
    <table class="table">
        <thead class="table-dark">
            <th>N°</th>
            <th>DETALLES</th>
            <th>Descargar</th>
            <th></th>
        </thead>

        <tbody>
            @foreach ($datosCert as $key => $item5)

                    
                <tr>
                    
                    <td>{{ $key + 1 }}</td>
                     
                    <td>
                        
                        <h6>{{ $item5->curso }}</h6>
                        <span class="badge text-bg- p-2">{{ $item5->codigo }}</span>
                        <span class="badge text-bg-success p-2">{{ $item5->participo_como }}</span>
                        <span class="badge text-bg-primary p-2">{{ $item5->codigo }}</span>
                       
                    </td>

                    <td>
                         <!-- <a type="button" data-certificado-id="{{ $item5->id_participante }}" data-bs-toggle="modal"
                            type="button" data-bs-target="#staticBackdrop{{ $item5->id_participante }}"
                            class="btn btn-danger ver-pdf"><i class="fa-solid fa-file-pdf"></i></a>  -->
                        <a href="{{ route('busqueda.ver', $item5->id_participante) }}" class="btn btn-danger"><i
                                class="fa-solid fa-file-pdf"></i></a>
                    </td>



                    <!-- Modal -->
                    <div class="modal fade" id="staticBackdrop{{ $item5->id_participante }}" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content p-3">
                                <div class="modal-header">
                                    <h4 class="modal-title fs-5" id="staticBackdropLabel">Ver mi Certificado</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert" data-id="enviando-correo">Enviando correo...</div>
                                    <p><b>Sr. {{ $item5->nombre }} {{ $item5->apellido }}</b>
                                        Se ha enviado un correo a: <span class="text-primary">{{ $item5->correo }}</span>
                                        indicando el <b>CODIGO</b> a emplear</p>
                                </div>
                                <form id="form-{{ $item5->id_participante }}" class="formulario">
                                    <input type="hidden" name="participante" value="{{ $item5->id_participante }}">
                                    <input type="hidden" name="curso" value="{{ $item5->id_curso }}">

                                    <div class="p-2 form-group">
                                        <label><b>Ingrese el código aquí</b></label>
                                        <input type="text" class="form-control border-1" name="txtcodigo">
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary continuar">Continuar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                </tr>
                
            @endforeach
        </tbody>
    </table>

    <script>
        document.querySelectorAll('.ver-pdf').forEach((btn) => {
            btn.addEventListener('click', (e) => {
                $('[data-id]').text("Enviando correo...");
                $('[data-id]').removeClass("alert-success")
                $('[data-id]').removeClass("alert-danger")
                e.preventDefault();
                const certificadoId = btn.getAttribute('data-certificado-id');
                var ruta = "{{ url('enviarCorreo/') }}/" + certificadoId + "";
                $.ajax({
                    url: ruta,
                    type: "get",
                    success: function(data) {
                        if (data.mensaje == "Correo enviado correctamente") {
                            $('[data-id]').text(data.mensaje);
                            $('[data-id]').addClass("alert-success")
                        } else {
                            $('[data-id]').text(data.mensaje);
                            $('[data-id]').addClass("alert-danger")
                        }

                    },
                    error: function(data) {
                        $('[data-id]').text(data.mensaje);
                        $('[data-id]').addClass("alert-danger")
                    }
                })
            });
        });
    </script>

    {{-- enviar codigo ingresa para validar por ajax --}}
    <script>
        $(document).ready(function() {
            // Agregar controlador de eventos submit a los formularios
            $('.formulario').on('submit', function(e) {
                e.preventDefault(); // Evitar que se recargue la página al enviar el formulario
                // Obtener los datos del formulario como un array de objetos
                var formDataArray = $(this).serializeArray();
                // Declarar variables para cada campo del formulario
                var participante, curso, txtcodigo;

                // Iterar sobre el array de datos del formulario
                $.each(formDataArray, function(index, item) {
                    // Asignar el valor correspondiente a la variable correspondiente
                    switch (item.name) {
                        case 'participante':
                            participante = item.value;
                            break;
                        case 'curso':
                            curso = item.value;
                            break;
                        case 'txtcodigo':
                            txtcodigo = item.value;
                            break;
                        default:
                            // No hacemos nada con los campos que no nos interesan
                            break;
                    }
                });

                if (txtcodigo != "") {
                    // Enviar solicitud AJAX
                    var url =
                        "{{ route('correo.enviarCodigo', ['participante' => ':participante', 'curso' => ':curso', 'codigo' => ':codigo']) }}";
                    url = url.replace(':participante', encodeURIComponent(participante));
                    url = url.replace(':curso', encodeURIComponent(curso));
                    url = url.replace(':codigo', encodeURIComponent(txtcodigo));

                    console.log(participante, curso, txtcodigo);
                    // Enviar solicitud AJAX
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            if (data.mensaje == "success") {

                                window.location.href =
                                    "{{ route('busqueda.ver', [':participante', ':codigo']) }}"
                                    .replace(':participante', data.id_participante).replace(
                                        ':codigo',
                                        data.codigo);

                            } else {
                                alert("El codigo ingresado no es el correcto")
                            }
                        },
                        error: function(xhr, status, error) {}
                    });
                } else {
                    alert("Ingrese el codigo, por favor")
                }

            });
        });
    </script>
@endsection
