<?php

namespace App\Http\Controllers;

use TCPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SplFileObject;

class CertificadoController extends Controller
{
    public function index()
    {
        $datos = DB::table('certificado')
            ->rightJoin('curso', 'certificado.id_curso', '=', 'curso.id_curso')
            ->select('certificado.id_certificado', 'certificado.modelo', 'curso.nombre', 'curso.id_curso')
            ->paginate(10);

        //$curso = DB::select(" select * from curso ");
        return view("certificado/listaCertificado")->with("datos", $datos);
    }

    public function store(Request $request)
    {
        //return $request;

        $request->validate([
            "curso" => "required",
            "modelo" => "required|image|mimes:jpeg,png,jpg",
        ]);


        //verificar si ya existe el curso
        $verificar = DB::select("select count(*) as 'total' from certificado where certificado.id_curso=$request->curso ");
        foreach ($verificar as $key => $value) {
            if ($value->total >= 1) {
                return back()->with("DUPLICADO", "El registro ya existe");
            }
        }

        $file = $request->file("modelo");
        $nombreFile = $request->curso . "." . $file->guessExtension();
        $ruta = public_path("modelo_certificados/" . $nombreFile);
        copy($file, $ruta);

        if (file_exists($ruta)) {
        } else {
            return back()->with("INCORRECTO", "Hubo un error al subir el Certificado ");
        }

        //ahora registramos
        try {
            $sql = DB::insert(" insert into certificado(id_curso,modelo)values(?,?) ", [
                $request->curso,
                $nombreFile
            ]);
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Certificado registrado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al registrar, intente nuevamente");
        }
    }


    //este es el codigo para modificar la tabla certificado
    public function add(Request $request, $id)
    {
        $request->validate([
            // "txtid" => "required",
            "modelo" => "required|image|mimes:jpeg,png,jpg",
            "curso" => "required",
        ]);

        //buscamos en nombre de la imagen anterior
        $buscar = DB::select("select modelo from certificado where id_certificado=$id");
        $nombreModelo = $buscar[0]->modelo;
        //eliminamos la img anterior
        $rutaAn = public_path("modelo_certificados/" . $nombreModelo);
        try {
            unlink("$rutaAn");
        } catch (\Throwable $th) {
            //throw $th;
        }

        $file = $request->file("modelo");
        $nombreFile = $request->curso . "." . $file->guessExtension();
        $ruta = public_path("modelo_certificados/" . $nombreFile);
        copy($file, $ruta);

        if (file_exists($ruta)) {
        } else {
            return back()->with("INCORRECTO", "Hubo un error al subir el Certificado");
        }

        try {
            $sql = DB::update(" update certificado set modelo=? where id_certificado=$id ", [
                $nombreFile
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "Certificado actualizado exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al actualizar, intente nuevamente");
        }
    }


    //este es el codigo para modificar los campos de posicion de la tabla certificado
    public function update(Request $request, $id)
    {
        $request->validate([
            "even_ide" => "required",
            "even_otorX" => "required",
            "even_otorY" => "required",
            "even_otorW" => "required",
            "even_otorH" => "required",
            "even_otorF" => "required",
            "even_comoX" => "required",
            "even_comoY" => "required",
            "even_comoW" => "required",
            "even_comoH" => "required",
            "even_comoF" => "required",
            "even_codeX" => "required",
            "even_codeY" => "required",
            "even_codeW" => "required",
            "even_codeH" => "required",
            "even_codeF" => "required",
            "qrX" => "required",
            "qrY" => "required",
            "qrW" => "required",
            "qrH" => "required",
            "qrF" => "required",
        ]);

        try {
            $sql = DB::update(" update certificado set otorX=?,otorY=?,otorL=?,otorA=?,otorF=?,asisX=?,asisY=?,asisL=?,asisA=?,asisF=?,codiX=?,codiY=?,codiL=?,codiA=?,codiF=?,qrX=?,qrY=?,qrL=?,qrA=?,qrF=? where id_certificado=? ", [
                $request->even_otorX,
                $request->even_otorY,
                $request->even_otorW,
                $request->even_otorH,
                $request->even_otorF,
                $request->even_comoX,
                $request->even_comoY,
                $request->even_comoW,
                $request->even_comoH,
                $request->even_comoF,
                $request->even_codeX,
                $request->even_codeY,
                $request->even_codeW,
                $request->even_codeH,
                $request->even_codeF,
                $request->qrX,
                $request->qrY,
                $request->qrW,
                $request->qrH,
                $request->qrF,
                $id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with("CORRECTO", "Cambios realizados exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al realizar cambios, intente nuevamente");
        }
    }

    public function show($id)
    {
        $datos = DB::select(" select * from certificado where id_certificado=$id ");
        return view("certificado/ajustarCertificado")->with("datos", $datos);
    }

    public function verPDF($id)
    {
        $datos = DB::select(" select * from certificado where id_certificado=$id ");
        foreach ($datos as $key => $value) {
            $modelo = $value->modelo;

            // Crear una nueva instancia de TCPDF
            $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
            $pdf->setPageOrientation('L'); // L para horizontal, P para vertical
            // Agregar la primera página
            $pdf->AddPage();
            $pdf->SetAutoPageBreak(false, 0);
            $pdf->SetMargins(0, 0, 0);
            $pdf->Image(public_path("modelo_certificados/$modelo"), 0, 0, $pdf->getPageWidth(), $pdf->getPageHeight());
            // Mostrar el PDF en el navegador


            // Posicionamiento y dimensiones del texto
            $x = $value->otorX;
            $y = $value->otorY;
            $w = $value->otorA;
            $h = $value->otorL;
            $fill = false; // color de fondo
            $pdf->SetFont('helvetica', 'B', $value->otorF);
            $pdf->SetLineWidth(0.25);
            $pdf->SetDrawColor(255, 0, 0); // Establece el color de borde a rojo
            $text = 'NOMBRES Y APELLIDOS';
            $pdf->MultiCell($w, $h, $text, 1, "C", $fill, 1, $x, $y, true, 0, false, true, $h, 'T');
            $pdf->Rect($x, $y, $w, $h, 'D');


            $x = $value->asisX;
            $y = $value->asisY;
            $w = $value->asisA;
            $h = $value->asisL;
            $fill = false; // color de fondo
            $pdf->SetFont('helvetica', 'B', $value->asisF);
            $pdf->SetLineWidth(0.25);
            $pdf->SetDrawColor(255, 0, 0); // Establece el color de borde a rojo
            $text2 = 'ASISTENTE';
            $pdf->MultiCell($w, $h, $text2, 1, "C", $fill, 1, $x, $y, true, 0, false, true, $h, 'T');
            $pdf->Rect($x, $y, $w, $h, 'D');


            $x = $value->codiX;
            $y = $value->codiY;
            $w = $value->codiA;
            $h = $value->codiL;
            $fill = false; // color de fondo
            $pdf->SetFont('helvetica', 'B', $value->codiF);
            $pdf->SetLineWidth(0.25);
            $pdf->SetDrawColor(255, 0, 0); // Establece el color de borde a rojo
            $text3 = 'CÓDIGO';
            $pdf->MultiCell($w, $h, $text3, 1, "C", $fill, 1, $x, $y, true, 0, false, true, $h, 'T');
            $pdf->Rect($x, $y, $w, $h, 'D');

            // Posicionamiento y dimensiones del texto
            $x = $value->qrX;
            $y = $value->qrY;
            $w = $value->qrA;
            $h = $value->qrL;
            $fill = false; // color de fondo
            $pdf->SetFont('helvetica', 'B', $value->qrF);
            $pdf->SetLineWidth(0.25);
            $pdf->SetDrawColor(255, 0, 0); // Establece el color de borde a rojo


            $baseUrl = url('/');
            $parsedUrl = parse_url($baseUrl);
            $domain = route("welcome");
            $text4 = $domain . "/verMiCertificadoQR/prueba";

            // Genera el código QR en formato PNG
            QrCode::format('png')->size(500)->generate($text4, public_path("qr/prueba.png"));
            $image = new SplFileObject(public_path('qr/prueba.png'));
            $pdf->Image(public_path("qr/prueba.png"), $x, $y, $w, $h);

            $pdf->Rect($x, $y, $w, $h, 'D');

            $pdf->Output('certificado-' . '.pdf', 'I');
        }
    }


    public function buscar($id)
    {
        try {
            $sql = DB::select("SELECT
            certificado.id_certificado,
            certificado.modelo,
            curso.nombre,
            curso.descripcion,
            curso.id_curso
            FROM
            certificado
            RIGHT JOIN curso ON certificado.id_curso = curso.id_curso where nombre like '%$id%' or descripcion like '%$id%' limit 10 ");
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if (count($sql) > 0) {
            return response()->json(['dato' => $sql], 200);
        } else {
            return response()->json(['error' => "Mensaje de error"], 500);
        }
    }

    public function ver($id)
    {
        $sql = DB::select(" SELECT
        certificado.id_certificado,
        certificado.modelo,
        curso.nombre,
        curso.id_curso
        FROM
        certificado
        RIGHT JOIN curso ON certificado.id_curso = curso.id_curso where curso.id_curso=$id ");
        return view("certificado/viewCertificado", compact("sql"));
    }

    public function delete($id)
    {
        $obtenerModelo = DB::select("select * from certificado where id_certificado=$id");
        $modelo = $obtenerModelo[0]->modelo;
        $rutaAn = public_path("modelo_certificados/" . $modelo);
        try {
            unlink("$rutaAn");
        } catch (\Throwable $th) {
            //throw $th;
        }

        try {
            $sql = DB::update(" update certificado set modelo='' where id_certificado=$id ");
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }

        if ($sql == 1) {
            return back()->with("CORRECTO", "El modelo del certificado se eliminó exitosamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar, intente nuevamente");
        }
    }
}
