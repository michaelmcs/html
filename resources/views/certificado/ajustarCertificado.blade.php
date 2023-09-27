@extends('layouts/app')
@section('titulo', 'configurar modelo certificado')

@section('content')


    {{-- notificaciones --}}

    @if (session('CORRECTO'))
        <div class="alert alert-success"><i class="fas fa-check"></i> {{ session('CORRECTO') }}</div>
    @endif

    @if (session('INCORRECTO'))
        <div class="alert alert-error"><i class="fas fa-times"></i> {{ session('INCORRECTO') }}</div>
    @endif

    <div class="d-flex">
        @foreach ($datos as $item)
            <form method="POST" action="{{ route('certificado.update', $item->id_certificado) }}" id="form_config"
                style="width: 280px;height: 82vh;overflow: scroll">
                @csrf
                @method('PUT')
                <input type="hidden" value="{{ $item->id_certificado }}" name="even_ide">
                <table class="table table-condensed table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>OTORGADO A</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">X:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="X:"
                                                value="{{ $item->otorX }}" step="0.1" name="even_otorX" required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Y:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="Y:"
                                                value="{{ $item->otorY }}" step="0.1" name="even_otorY" required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">L:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="L:"
                                                value="{{ $item->otorL }}" step="0.1" name="even_otorW" required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">A:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="A:"
                                                value="{{ $item->otorA }}" step="0.1" name="even_otorH" required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Fuente:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="Fuente:"
                                                value="{{ $item->otorF }}" step="0.1" name="even_otorF" required="">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-condensed table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>ASISTIO COMO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">X:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="X:"
                                                value="{{ $item->asisX }}" step="0.1" name="even_comoX" required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Y:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="Y:"
                                                value="{{ $item->asisY }}" step="0.1" name="even_comoY"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">L:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="L:"
                                                value="{{ $item->asisL }}" step="0.1" name="even_comoW"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">A:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="A:"
                                                value="{{ $item->asisA }}" step="0.1" name="even_comoH"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Fuente:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm"
                                                placeholder="Fuente:" value="{{ $item->asisF }}" step="0.1"
                                                name="even_comoF" required="">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-condensed table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>CODIGO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">X:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="X:"
                                                value="{{ $item->codiX }}" step="0.1" name="even_codeX"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Y:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="Y:"
                                                value="{{ $item->codiY }}" step="0.1" name="even_codeY"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">L:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="L:"
                                                value="{{ $item->codiL }}" step="0.1" name="even_codeW"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">A:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="A:"
                                                value="{{ $item->codiA }}" step="0.1" name="even_codeH"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Fuente:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm"
                                                placeholder="Fuente:" value="{{ $item->codiF }}" step="0.1"
                                                name="even_codeF" required="">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="table table-condensed table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>QR</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="row">

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">X:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="X:"
                                                value="{{ $item->qrX }}" step="0.1" name="qrX"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Y:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="Y:"
                                                value="{{ $item->qrY }}" step="0.1" name="qrY"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">L:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="L:"
                                                value="{{ $item->qrL }}" step="0.1" name="qrW"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">A:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm" placeholder="A:"
                                                value="{{ $item->qrA }}" step="0.1" name="qrH"
                                                required="">
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Fuente:</span>
                                            </div>
                                            <input type="number" class="form-control form-control-sm"
                                                placeholder="Fuente:" value="{{ $item->qrF }}" step="0.1"
                                                name="qrF" required="">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button class="btn btn-sm btn-primary btn-block">
                    Guardar
                </button>
            </form>


            <iframe src="{{ url('verPDF', $item->id_certificado) }}" frameborder="0"
                style="width: 100%; height: 82vh;"></iframe>
        @endforeach


    </div>



@endsection
