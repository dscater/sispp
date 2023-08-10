<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PDF;

class ReporteController extends Controller
{
    public function usuarios(Request $request)
    {
        $filtro =  $request->filtro;
        $usuarios = User::where('id', '!=', 1)->orderBy("paterno", "ASC")->get();

        if ($filtro == 'Tipo de usuario') {
            $request->validate([
                'tipo' => 'required',
            ]);
            $usuarios = User::where('id', '!=', 1)->where('tipo', $request->tipo)->orderBy("paterno", "ASC")->get();
        }

        $pdf = PDF::loadView('reportes.usuarios', compact('usuarios'))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->download('Usuarios.pdf');
    }

    public function proteccion_personal(Request $request)
    {
        $filtro =  $request->filtro;
        $indumentaria =  $request->indumentaria;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $notificacions = Notificacion::where("tipo", "ALERTA")->get();

        if ($filtro == 'indumentaria') {
            $request->validate([
                'indumentaria' => 'required',
            ]);
            $notificacions = Notificacion::where("tipo", "ALERTA")->where("indumentaria", "LIKE", "%$indumentaria%")->get();
        }

        if ($filtro == 'fecha') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
            $notificacions = Notificacion::where("tipo", "ALERTA")->whereBetween("fecha", [$fecha_ini, $fecha_fin])->get();
        }

        $pdf = PDF::loadView('reportes.proteccion_personal', compact('notificacions'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->download('proteccion_personal.pdf');
    }

    public function g_proteccion_personal(Request $request)
    {
        $filtro =  $request->filtro;
        $indumentaria =  $request->indumentaria;
        $fecha_ini =  $request->fecha_ini;
        $fecha_fin =  $request->fecha_fin;

        $indumentaria_text = [
            "PANTALÓN AZUL",
            "CHAQUETA AZUL",
            "CHALECO DE SEGURIDAD",
            "GUANTES DE SEGURIDAD",
            "LENTES DE SEGURIDAD",
            "BOTAS DE SEGURIDAD",
            "CASCO DE SEGURIDAD",
            "PROTECTOR AUDITIVO",
        ];

        if ($filtro == 'fecha') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
        }

        if ($filtro == 'indumentaria') {
            $request->validate([
                'indumentaria' => 'required',
            ]);
            $indumentaria_text = [$indumentaria];
        }

        $data = [];

        foreach ($indumentaria_text as $indu) {
            if ($filtro == 'fecha') {
                $notificacions = Notificacion::where("tipo", "ALERTA")
                    ->where("indumentaria", "LIKE", "%$indumentaria%")
                    ->whereBetween("fecha", [$fecha_ini, $fecha_fin])
                    ->get();
            } else {
                $notificacions = Notificacion::where("tipo", "ALERTA")
                    ->where("indumentaria", "LIKE", "%$indumentaria%")
                    ->get();
            }

            $total = 0;
            foreach ($notificacions as $notificacion) {
                $indumentarias = explode(',', $notificacion->indumentaria);
                if (in_array($indu, $indumentarias)) {
                    $total++;
                }
            }
            $data[] = ["name" => $indu, "y" => (int)$total];
        }

        return response()->JSON(["datos" => $data]);
    }
}
