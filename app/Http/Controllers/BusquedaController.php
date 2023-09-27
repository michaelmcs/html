<?php

namespace App\Http\Controllers;

use App\Mail\BusquedaMailable;
use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SplFileObject;
use TCPDF;

class BusquedaController extends Controller
{

    public function index()
    {
        return view("layouts/formBusqueda");
    }
    public function enviarCodigo($participante, $curso, $codigo)
    {
        try {
            $sql = DB::select(" select cod_verificacion from participante where id_participante=$participante ");
            foreach ($sql as $key => $value) {
                $codigoParti = $value->cod_verificacion;
            }
        } catch (\Throwable $th) {
            $codigoParti = 0;
        }

        if ($codigo == $codigoParti) {
            return response()->json(['mensaje' => 'success', "id_participante" => $participante, "codigo" => $codigo], 200);
        } else {
            return response()->json(['mensaje' => 'error'], 200);
        }
    }

    public function verCertificado($participante, $codigo = 0)
    {
        try {
            $sql = DB::select(" SELECT
            participante.cod_verificacion,
            participante.id_curso,
            participante.certificado,
            participante.participo_como,
            certificado.id_certificado
            FROM
            participante
            INNER JOIN curso ON participante.id_curso = curso.id_curso
            INNER JOIN certificado ON certificado.id_curso = curso.id_curso where id_participante=$participante ");
            foreach ($sql as $key => $value) {
                $codigoParti = $value->cod_verificacion;
                $id_certificado = $value->id_certificado;
                $certPart = $value->certificado;
                $participo_como = $value->participo_como;
            }

            return view("certificados/resultadoBusqueda")
                ->with("id_certificado", $id_certificado)
                ->with("id_participante", $participante)
                ->with("certPart", $certPart)
                ->with("participo_como", $participo_como);


            if ($codigo == $codigoParti) {
                return view("certificados/resultadoBusqueda")
                    ->with("id_certificado", $id_certificado)
                    ->with("id_participante", $participante)
                    ->with("certPart", $certPart)
                    ->with("participo_como", $participo_como);
            } else {
                return redirect()->route("welcome")->with("mensaje", "No puedes acceder a los Certificados, sin los permisos necesarios");
            }
        } catch (\Throwable $th) {
            return redirect()->route("welcome")->with("mensaje", "No puedes acceder a los Certificados, sin los permisos necesarios");
        }
    }

    public function buscar(Request $request)
    {
        $datos = DB::select(" SELECT
        participante.*,
        curso.nombre as 'curso'
        FROM
        participante
        INNER JOIN curso ON participante.id_curso = curso.id_curso where participante.dni='$request->dni' or codigo='$request->dni' order by id_participante desc ");
        $count = count($datos);
        if ($count >= 1) {
            session()->flash('MENSAJE', new HtmlString("<div class='alert alert-success'>¡Se han encontrado $count certificados!</div>"));
            return view("certificados/busqueda")->with("datosCert", $datos);
        } else {
            session()->flash('MENSAJE', new HtmlString("<div class='alert alert-danger'>No se han encontrado registros, Consulte con el Administrador</div>"));
            return view("certificados/busqueda")->with("datosCert", $datos);
        }
    }

    public function enviar($id)
    {
        $codigo = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        try {
            $sql = DB::select(" select correo from participante where id_participante=$id ");
            foreach ($sql as $key => $value) {
                $correoParti = $value->correo;
            }
        } catch (\Throwable $th) {
            $correoParti = 0;
        }
        $modCodigo = DB::update(" update participante set cod_verificacion='$codigo' where id_participante=$id ");
        $correo = new BusquedaMailable($codigo); //enviamos estos datos al MAILABLE
        try {
            Mail::to($correoParti)->send($correo);
            return response()->json(['mensaje' => 'Correo enviado correctamente'], 200);
        } catch (\Throwable $th) {
            return response()->json(['mensaje' => 'Falló el envío del correo electrónico'], 200);
        }
    }


    public function verPDF($id, $participante)
    {
        $datos = DB::select(" select * from certificado where id_certificado=$id ");
        $datosParticipante = DB::select(" select * from participante where id_participante=$participante ");
        foreach ($datosParticipante as $key => $value) {
            $asistemaComo = $value->participo_como;
            $nombres = $value->nombre . " " . $value->apellido;
            $codigo = $value->codigo;
            $curso = $value->id_curso;
            $dni = $value->dni;
            // $curso = $value->id_participante;
        }

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
            $text = strtoupper($nombres);
            $pdf->MultiCell($w, $h, $text, 1, "C", $fill, 1, $x, $y, true, 0, false, true, $h, 'T');


            $x = $value->asisX;
            $y = $value->asisY;
            $w = $value->asisA;
            $h = $value->asisL;
            $fill = false; // color de fondo
            $pdf->SetFont('helvetica', 'B', $value->asisF);
            $pdf->SetLineWidth(0.25);
            $pdf->SetDrawColor(255, 0, 0); // Establece el color de borde a rojo
            $text2 = strtoupper($asistemaComo);
            $pdf->MultiCell($w, $h, $text2, 1, "C", $fill, 1, $x, $y, true, 0, false, true, $h, 'T');


            $x = $value->codiX;
            $y = $value->codiY;
            $w = $value->codiA;
            $h = $value->codiL;
            $fill = false; // color de fondo
            $pdf->SetFont('helvetica', 'B', $value->codiF);
            $pdf->SetLineWidth(0.25);
            $pdf->SetDrawColor(255, 0, 0); // Establece el color de borde a rojo
            $text3 = $codigo;
            $pdf->MultiCell($w, $h, $text3, 1, "C", $fill, 1, $x, $y, true, 0, false, true, $h, 'T');



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
            $text4 = $domain . "/verMiCertificadoQR/$participante";

            // Genera el código QR en formato PNG
            QrCode::format('png')->size(500)->generate($text4, public_path("qr/$participante.png"));
            $image = new SplFileObject(public_path("qr/$participante.png"));
            $pdf->Image(public_path("qr/$participante.png"), $x, $y, $w, $h);

            $pdf->Output("cer_" . $curso . "_" . $dni . "_" . $codigo . "_" . $nombres . '.pdf', 'I');
        }
    }

    public function verPDFQR($id)
    {
        try {
            $datosPersona = DB::select(" SELECT
            participante.*,
            curso.horas,
            certificado.id_certificado
            FROM
            participante
            INNER JOIN curso ON participante.id_curso = curso.id_curso
            INNER JOIN certificado ON certificado.id_curso = curso.id_curso where id_participante=$id ");
        } catch (\Throwable $th) {
            $datosPersona = "";
        }

        foreach ($datosPersona as $key => $value) {
            $certificado = $value->certificado;
        }


        if ($datosPersona == "" or count($datosPersona) <= 0) {
            return redirect()->route("welcome")->with("aviso", "El certificado NO existe");
        } else {
            if ($certificado != "") {
                return view("certificados/vistaPorQR", compact("datosPersona"))->with("modo", "tiene");
            } else {
                return view("certificados/vistaPorQR", compact("datosPersona"))->with("modo", "notiene");
            }
        }
    }
}
