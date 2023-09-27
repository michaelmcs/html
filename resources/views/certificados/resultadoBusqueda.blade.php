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
    @if (session('CORRECTO'))
        {{ session('CORRECTO') }}
    @endif


    @if ($certPart == '' or $certPart == null)
        @if ($participo_como != 'otro')
            <iframe src="{{ route('busqueda.miCertificado', [$id_certificado, $id_participante]) }}" frameborder="0"
                style="width: 50vw; height: 50vh;min-width: 0;"></iframe>
        @else
        <div class="alert alert-danger">Aún no se ha subido tu certificado. UD. No está registrado como Asistente ni Ponente, Por favor consulte con el Administrador</div>
        @endif
    @else
        <iframe src="{{ asset("certificados/$certPart") }}" frameborder="0"
            style="width: 50vw; height: 50vh;min-width: 0;"></iframe>
    @endif
@endsection
