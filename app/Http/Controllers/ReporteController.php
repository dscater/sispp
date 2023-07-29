<?php

namespace App\Http\Controllers;

use App\Models\DetalleOrden;
use App\Models\Equipo;
use App\Models\GamaMantenimiento;
use App\Models\Herramienta;
use App\Models\HistorialFalla;
use App\Models\KardexRepuesto;
use App\Models\OrdenTrabajo;
use App\Models\Personal;
use App\Models\PlanMantenimiento;
use App\Models\Producto;
use App\Models\Repuesto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function kardex_herramientas(Request $request)
    {
        $herramientas = Herramienta::all();

        $pdf = PDF::loadView('reportes.kardex_herramientas', compact('herramientas'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 130, 123, "{PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->download('kardex_herramientas.pdf');
    }
    public function informacion_herramientas(Request $request)
    {
        $filtro = $request->filtro;
        $herramienta_id = $request->herramienta_id;

        $herramientas = Herramienta::all();
        if ($filtro != "Todos") {
            $request->validate([
                "herramienta_id" => "required"
            ], [
                "herramienta_id.required" => "Debe seleccionar una herramienta"
            ]);
            $herramientas = Herramienta::where("id", $herramienta_id)->get();
        }

        $pdf = PDF::loadView('reportes.informacion_herramientas', compact('herramientas'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->download('informacion_herramientas.pdf');
    }
    public function informe_solicitudes(Request $request)
    {
        $filtro = $request->filtro;
        $ot = $request->ot;
        $equipo = $request->equipo;
        $herramienta = $request->herramienta;

        $orden_trabajos = OrdenTrabajo::select("orden_trabajos.*")
            ->get();
        if ($filtro != "todos") {
            if ($filtro == 'ot') {
                $request->validate([
                    "ot" => "required"
                ], [
                    "ot.required" => "Debes seleccionar una orden de trabajo"
                ]);

                $orden_trabajos = OrdenTrabajo::select("orden_trabajos.*")
                    ->where("id", $ot)
                    ->get();
            }
            if ($filtro == 'equipo') {
                $request->validate([
                    "equipo" => "required"
                ], [
                    "equipo.required" => "Debes seleccionar un equipo"
                ]);

                $orden_trabajos = OrdenTrabajo::select("orden_trabajos.*")
                    ->join("gama_mantenimientos", "gama_mantenimientos.id", "=", "orden_trabajos.gama_id")
                    ->where("gama_mantenimientos.equipo_id", $equipo)
                    ->get();
            }
            if ($filtro == 'herramienta') {
                $request->validate([
                    "herramienta" => "required"
                ], [
                    "herramienta.required" => "Debes seleccionar una herramienta"
                ]);

                $orden_trabajos = OrdenTrabajo::select("orden_trabajos.*")
                    ->join("orden_generadas", "orden_generadas.orden_id", "=", "orden_trabajos.id")
                    ->join("detalle_herramientas", "detalle_herramientas.orden_generada_id", "=", "orden_generadas.id")
                    ->where("detalle_herramientas.herramienta_id", $herramienta)
                    ->get();
            }
        }

        $pdf = PDF::loadView('reportes.informe_solicitudes', compact('orden_trabajos'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS USANDO CANVAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->download('informacion_herramientas.pdf');
    }
    public function kardex_repuestos(Request $request)
    {
        $filtro = $request->filtro;
        $repuesto = $request->repuesto;

        if ($request->filtro == 'repuesto') {
            $request->validate([
                'repuesto' => 'required',
            ]);
        }
        $repuestos = Repuesto::all();
        if ($filtro != 'todos') {
            if ($filtro == 'repuesto') {
                $repuestos = Repuesto::where("id", $repuesto)->get();
            }
        }

        $array_kardex = [];
        $array_saldo_anterior = [];
        foreach ($repuestos as $registro) {
            $kardex = KardexRepuesto::where('repuesto_id', $registro->id)->get();
            $array_saldo_anterior[$registro->id] = [
                'sw' => false,
                'saldo_anterior' => []
            ];
            $array_kardex[$registro->id] = $kardex;
        }

        $pdf = PDF::loadView('reportes.kardex_repuestos', compact('repuestos', 'array_kardex', 'array_saldo_anterior'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('kardex_repuestos.pdf');
    }
    public function entrada_salida_repuestos(Request $request)
    {
        $filtro = $request->filtro;
        $repuesto = $request->repuesto;
        $fecha_ini = $request->fecha_ini;
        $fecha_fin = $request->fecha_fin;

        if ($request->filtro == 'repuesto') {
            $request->validate([
                'repuesto' => 'required',
            ]);
        }
        if ($request->filtro == 'fecha') {
            $request->validate([
                'fecha_ini' => 'required|date',
                'fecha_fin' => 'required|date',
            ]);
        }

        $repuestos = Repuesto::all();
        if ($filtro != 'todos') {
            if ($filtro == 'repuesto') {
                $repuestos = Repuesto::where("id", $repuesto)->get();
            }
        }

        $array_kardex = [];
        $array_saldo_anterior = [];
        foreach ($repuestos as $registro) {
            $kardex = KardexRepuesto::where('repuesto_id', $registro->id)->get();
            $array_saldo_anterior[$registro->id] = [
                'sw' => false,
                'saldo_anterior' => []
            ];
            if ($filtro == 'fecha') {
                $kardex = KardexRepuesto::where('repuesto_id', $registro->id)
                    ->whereBetween('fecha', [$fecha_ini, $fecha_fin])->get();
                // buscar saldo anterior si existe
                $saldo_anterior = KardexRepuesto::where('repuesto_id', $registro->id)
                    ->where('fecha', '<', $fecha_ini)
                    ->orderBy('created_at', 'asc')->get()->last();
                if ($saldo_anterior) {
                    $cantidad_saldo = $saldo_anterior->cantidad_saldo;
                    $monto_saldo = $saldo_anterior->monto_saldo;
                    $array_saldo_anterior[$registro->id] = [
                        'sw' => true,
                        'saldo_anterior' => [
                            'cantidad_saldo' => $cantidad_saldo,
                            'monto_saldo' => $monto_saldo,
                        ]
                    ];
                }
            }
            $array_kardex[$registro->id] = $kardex;
        }

        $pdf = PDF::loadView('reportes.entrada_salida_repuestos', compact('repuestos', 'array_kardex', 'array_saldo_anterior'))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('entrada_salida_repuestos.pdf');
    }

    public function plan_mantenimiento(Request $request)
    {
        $filtro = $request->filtro;
        $estado = $request->estado;

        $plan_mantenimientos = PlanMantenimiento::all();
        if ($filtro != "todos") {
            $request->validate([
                "estado" => "required"
            ]);
            $plan_mantenimientos = PlanMantenimiento::where("estado", $estado)->get();
        }

        $pdf = PDF::loadView('reportes.plan_mantenimiento', compact("plan_mantenimientos"))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('plan_mantenimiento.pdf');
    }
    public function maestro_plan_mantenimiento(Request $request)
    {
        $filtro = $request->filtro;
        $equipo = $request->equipo;
        $anio = $request->anio;
        $mes = $request->mes;
        $registros = [];

        if ($filtro != "todos") {
            if ($filtro == "equipo") {
                $request->validate([
                    "equipo" => "required"
                ]);
                $anios = OrdenTrabajo::getAniosOT();
                $meses = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
                $registros = [];
                $index_dias_mes = [];
                foreach ($anios as $anio) {
                    foreach ($meses as $mes) {
                        $dias_mes = OrdenTrabajo::obtenerDiasMes($anio, $mes);
                        $index_dias_mes[$anio][$mes] = $dias_mes;
                        foreach ($dias_mes as $dm) {
                            $registros[$anio][$mes][$dm] = [];
                            $fecha = $anio . '-' . $mes . '-' . ($dm < 10 ? '0' . $dm : $dm);
                            $ots = OrdenTrabajo::select("orden_trabajos.id", "orden_trabajos.estado")
                                ->join("gama_mantenimientos", "gama_mantenimientos.id", "=", "orden_trabajos.gama_id")
                                ->where("gama_mantenimientos.equipo_id", $equipo)
                                ->where("fecha_programada", $fecha)->get();
                            if (count($ots) > 0) {
                                $registros[$anio][$mes][$dm] = $ots;
                            }
                        }
                    }
                }
            }
            if ($filtro == "anio_mes") {
                $request->validate([
                    "anio" => "required",
                    "mes" => "required"
                ], [
                    "anio.required" => "Debes seleccionar un año",
                    "mes.required" => "Debes seleccionar un mes",
                ]);
                $anios = [$anio];
                $meses = [$mes];
                $registros = [];
                $index_dias_mes = [];
                foreach ($anios as $anio) {
                    foreach ($meses as $mes) {
                        $dias_mes = OrdenTrabajo::obtenerDiasMes($anio, $mes);
                        $index_dias_mes[$anio][$mes] = $dias_mes;
                        foreach ($dias_mes as $dm) {
                            $registros[$anio][$mes][$dm] = [];
                            $fecha = $anio . '-' . $mes . '-' . ($dm < 10 ? '0' . $dm : $dm);
                            $ots = OrdenTrabajo::select("id", "estado")
                                ->where("fecha_programada", $fecha)->get();
                            if (count($ots) > 0) {
                                $registros[$anio][$mes][$dm] = $ots;
                            }
                        }
                    }
                }
            }
        } else {
            $anios = OrdenTrabajo::getAniosOT();
            $meses = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
            $registros = [];
            $index_dias_mes = [];
            foreach ($anios as $anio) {
                foreach ($meses as $mes) {
                    $dias_mes = OrdenTrabajo::obtenerDiasMes($anio, $mes);
                    $index_dias_mes[$anio][$mes] = $dias_mes;
                    foreach ($dias_mes as $dm) {
                        $registros[$anio][$mes][$dm] = [];
                        $fecha = $anio . '-' . $mes . '-' . ($dm < 10 ? '0' . $dm : $dm);
                        $ots = OrdenTrabajo::select("id", "estado")->where("fecha_programada", $fecha)->get();
                        if (count($ots) > 0) {
                            $registros[$anio][$mes][$dm] = $ots;
                        }
                    }
                }
            }
        }

        $pdf = PDF::loadView('reportes.maestro_plan_mantenimiento', compact("anios", "meses", "index_dias_mes", "registros"))->setPaper('legal', 'landscape');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('maestro_plan_mantenimiento.pdf');
    }
    public function historial_fallas(Request $request)
    {
        $filtro = $request->filtro;
        $equipo = $request->equipo;
        $anio = $request->anio;
        $mes = $request->mes;

        $historial_fallas = HistorialFalla::orderBy("fecha_falla", "asc")->get();
        if ($filtro != "todos") {
            if ($filtro == "equipo") {
                $historial_fallas = HistorialFalla::orderBy("fecha_falla", "asc")
                    ->where("equipo_id", $equipo)->get();
            }
            if ($filtro == "anio_mes") {
                $historial_fallas = HistorialFalla::orderBy("fecha_falla", "asc")
                    ->where("fecha_falla", "LIKE", "$anio-$mes%")->get();
            }
        }

        $pdf = PDF::loadView('reportes.historial_fallas', compact("historial_fallas"))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('historial_fallas.pdf');
    }
    public function seguimiento_costos(Request $request)
    {
        $filtro = $request->filtro;
        $equipo = $request->equipo;
        $anio = $request->anio;
        $mes = $request->mes;
        $tipo_mantenimiento = $request->tipo_mantenimiento;

        $orden_trabajos = OrdenTrabajo::select("orden_trabajos.*");

        if ($filtro == 'equipo') {
            $orden_trabajos->join("gama_mantenimientos", "gama_mantenimientos.id", "=", "orden_trabajos.gama_id")
                ->where("gama_mantenimientos.equipo_id", $equipo);
        }

        if ($filtro == 'anio_mes') {
            $orden_trabajos->where("orden_trabajos.fecha_programada", "LIKE", "$anio-$mes%");
        }

        if ($filtro == 'tipo_mantenimiento') {
            $orden_trabajos->where("orden_trabajos.tipo_mantenimiento", $tipo_mantenimiento);
        }

        $orden_trabajos = $orden_trabajos->where("orden_trabajos.estado", "TERMINADO")->get();

        $pdf = PDF::loadView('reportes.seguimiento_costos', compact("orden_trabajos"))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('seguimiento_costos.pdf');
    }
    public function informe_general(Request $request)
    {
        $filtro = $request->filtro;
        $equipo = $request->equipo;
        $anio = $request->anio;
        $mes = $request->mes;
        $tipo_mantenimiento = $request->tipo_mantenimiento;

        $registros = [];
        $equipos = Equipo::all();
        if ($filtro == 'equipo') {
            $equipos = Equipo::where("id", $equipo)->get();
        }

        foreach ($equipos as $equipo) {

            if ($filtro != "todos" && $filtro != "equipo") {
                if ($filtro == "anio_mes") {
                    $registros[$equipo->id]["mantenimientos"] = OrdenTrabajo::select("orden_trabajos.*")
                        ->join("gama_mantenimientos", "gama_mantenimientos.id", "=", "orden_trabajos.gama_id")
                        ->where("gama_mantenimientos.equipo_id", $equipo->id)
                        ->where("orden_trabajos.fecha_programada", "LIKE", "$anio-$mes%")
                        ->get();

                    $registros[$equipo->id]["fallas"] = count(HistorialFalla::where("equipo_id", $equipo->id)
                        ->where("fecha_falla", "LIKE", "$anio-$mes%")->get());
                }
                if ($filtro == "tipo_mantenimiento") {
                    $registros[$equipo->id]["mantenimientos"] = OrdenTrabajo::select("orden_trabajos.*")
                        ->join("gama_mantenimientos", "gama_mantenimientos.id", "=", "orden_trabajos.gama_id")
                        ->where("gama_mantenimientos.equipo_id", $equipo->id)
                        ->where("orden_trabajos.tipo_mantenimiento", $tipo_mantenimiento)
                        ->get();

                    $registros[$equipo->id]["fallas"] = count(HistorialFalla::select("historial_fallas.id")
                        ->join("orden_trabajos", "orden_trabajos.id", "=", "historial_fallas.orden_id")
                        ->where("historial_fallas.equipo_id", $equipo->id)
                        ->where("orden_trabajos.tipo_mantenimiento", $tipo_mantenimiento)
                        ->get());
                }
            } else {
                $registros[$equipo->id]["mantenimientos"] = OrdenTrabajo::select("orden_trabajos.*")
                    ->join("gama_mantenimientos", "gama_mantenimientos.id", "=", "orden_trabajos.gama_id")
                    ->where("gama_mantenimientos.equipo_id", $equipo->id)->get();

                $registros[$equipo->id]["fallas"] = count(HistorialFalla::where("equipo_id", $equipo->id)->get());
            }
        }

        $pdf = PDF::loadView('reportes.informe_general', compact("equipos", "registros"))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('informe_general.pdf');
    }
    public function informe_ot_correctivas(Request $request)
    {
        $filtro = $request->filtro;
        $equipo = $request->equipo;
        $ot = $request->ot;

        $orden_trabajos = OrdenTrabajo::all();
        if ($filtro == "equipo") {
            $request->validate([
                "equipo" => "required",
            ]);
            $orden_trabajos = OrdenTrabajo::select("orden_trabajos.*")
                ->join("gama_mantenimientos", "gama_mantenimientos.id", "=", "orden_trabajos.gama_id")
                ->where("gama_mantenimientos.equipo_id", $equipo)
                ->where("orden_trabajos.tipo_mantenimiento", "CORRECTIVO")
                ->get();
        }

        if ($filtro == "equipo") {
            $request->validate([
                "equipo" => "required",
            ]);
            $orden_trabajos = OrdenTrabajo::where("id", $ot)
                ->get();
        }

        $pdf = PDF::loadView('reportes.informe_ot_correctivas', compact("orden_trabajos"))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('informe_ot_correctivas.pdf');
    }
    public function resumen_ots(Request $request)
    {
        $filtro = $request->filtro;
        $anio = $request->anio;
        $mes = $request->mes;

        $registros = [];
        $registros_anio_mes = [];
        $registros_personal = [];

        $orden_trabajos = OrdenTrabajo::all();
        $meses = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
        $anios = OrdenTrabajo::getAniosOT();
        $tipo_mantenimientos = [
            "PR" => "PREVENTIVO",
            "CO" => "CORRECTIVO",
            "PRE" => "PREDICTIVO",
            "IN" => "INSPECCIÓN",
            "LU" => "LUBRICACIÓN",
        ];
        if ($filtro != "todos") {
            $request->validate([
                "anio" => "required",
                "mes" => "required",
            ], [
                "anio.required" => "Debes seleccionar un año",
                "mes.required" => "Debes seleccionar un mes",
            ]);
            $anios = [$anio];
            $meses = [$mes];
        }

        // DESDE TIPOS MANTENIMIENTO
        foreach ($tipo_mantenimientos as $tm) {
            foreach ($anios as $anio) {
                foreach ($meses as $mes) {
                    $realizadas = OrdenTrabajo::where("fecha_programada", "LIKE", "$anio-$mes%")
                        ->where("tipo_mantenimiento", $tm)
                        ->whereIn("estado", ["TERMINADO", "PENDIENTE TERMINADO"])
                        ->get();
                    $pendientes = OrdenTrabajo::where("fecha_programada", "LIKE", "$anio-$mes%")
                        ->where("tipo_mantenimiento", $tm)
                        ->whereIn("estado", ["PENDIENTE"])
                        ->get();

                    $estimado_total_realizadas = OrdenTrabajo::where("fecha_programada", "LIKE", "$anio-$mes%")
                        ->where("tipo_mantenimiento", $tm)
                        ->whereIn("estado", ["TERMINADO", "PENDIENTE TERMINADO"])
                        ->sum("tiempo");
                    $ejecutado_total_realizadas = OrdenTrabajo::where("fecha_programada", "LIKE", "$anio-$mes%")
                        ->where("tipo_mantenimiento", $tm)
                        ->whereIn("estado", ["TERMINADO", "PENDIENTE TERMINADO"])
                        ->sum("tiempo_ejecucion");
                    $estimado_total_pendientes = OrdenTrabajo::where("fecha_programada", "LIKE", "$anio-$mes%")
                        ->where("tipo_mantenimiento", $tm)
                        ->whereIn("estado", ["PENDIENTE"])
                        ->sum("tiempo");
                    $ejecutado_total_pendientes = OrdenTrabajo::where("fecha_programada", "LIKE", "$anio-$mes%")
                        ->where("tipo_mantenimiento", $tm)
                        ->whereIn("estado", ["PENDIENTE"])
                        ->sum("tiempo_ejecucion");

                    $count_realizadas = count($realizadas);
                    $count_pendientes = count($pendientes);
                    $filas = $count_realizadas > $count_pendientes ? $count_realizadas : $count_pendientes;
                    $registros[$tm][$anio][$mes] = [
                        "filas" => $filas,
                        "realizadas" => $realizadas,
                        "estimado_total_realizadas" => $estimado_total_realizadas,
                        "ejecutado_total_realizadas" => $ejecutado_total_realizadas,
                        "pendientes" => $pendientes,
                        "estimado_total_pendientes" => $estimado_total_pendientes,
                        "ejecutado_total_pendientes" => $ejecutado_total_pendientes,
                    ];
                }
            }
        }

        // DESDE ANIO Y MES
        foreach ($anios as $anio) {
            foreach ($meses as $mes) {
                foreach ($tipo_mantenimientos as $tm) {
                    $total = count(OrdenTrabajo::where("fecha_programada", "LIKE", "$anio-$mes%")
                        ->where("tipo_mantenimiento", $tm)
                        ->get());
                    $total_horas = OrdenTrabajo::where("fecha_programada", "LIKE", "$anio-$mes%")
                        ->where("tipo_mantenimiento", $tm)
                        ->sum("tiempo_ejecucion");
                    $registros_anio_mes[$anio][$mes][$tm] = [
                        "total" => $total,
                        "total_horas" => $total_horas,
                    ];
                }
            }
        }

        // POR PERSONAL
        $personals = Personal::all();
        foreach ($personals as $personal) {
            $registros_personal[$personal->id] = [
                "horas_ejecutadas" => 0,
                "tipos" => [],
            ];
            if ($filtro == "todos") {
                $total_horas = OrdenTrabajo::select("orden_trabajos.*")
                    ->join("orden_generadas", "orden_generadas.orden_id", "=", "orden_trabajos.id")
                    ->join("detalle_personals", "detalle_personals.orden_generada_id", "=", "orden_generadas.id")
                    ->where("detalle_personals.personal_id", $personal->id)
                    ->sum("tiempo_ejecucion");
            } else {
                $total_horas = OrdenTrabajo::select("orden_trabajos.*")
                    ->join("orden_generadas", "orden_generadas.orden_id", "=", "orden_trabajos.id")
                    ->join("detalle_personals", "detalle_personals.orden_generada_id", "=", "orden_generadas.id")
                    ->where("detalle_personals.personal_id", $personal->id)
                    ->where("orden_trabajos.fecha_programada", "LIKE", "$anio-$mes%")
                    ->sum("tiempo_ejecucion");
            }
            $registros_personal[$personal->id]["horas_ejecutadas"] = $total_horas;
            foreach ($tipo_mantenimientos as $tm) {
                if ($filtro == "todos") {
                    $total_horas = OrdenTrabajo::select("orden_trabajos.*")
                        ->join("orden_generadas", "orden_generadas.orden_id", "=", "orden_trabajos.id")
                        ->join("detalle_personals", "detalle_personals.orden_generada_id", "=", "orden_generadas.id")
                        ->where("orden_trabajos.tipo_mantenimiento", $tm)
                        ->where("detalle_personals.personal_id", $personal->id)
                        ->sum("orden_trabajos.tiempo_ejecucion");
                } else {
                    $total_horas = OrdenTrabajo::select("orden_trabajos.*")
                        ->join("orden_generadas", "orden_generadas.orden_id", "=", "orden_trabajos.id")
                        ->join("detalle_personals", "detalle_personals.orden_generada_id", "=", "orden_generadas.id")
                        ->where("orden_trabajos.tipo_mantenimiento", $tm)
                        ->where("detalle_personals.personal_id", $personal->id)
                        ->where("orden_trabajos.fecha_programada", "LIKE", "$anio-$mes%")
                        ->sum("orden_trabajos.tiempo_ejecucion");
                }
                $registros_personal[$personal->id]["tipos"][$tm] = $total_horas;
            }
        }


        $pdf = PDF::loadView('reportes.resumen_ots', compact("tipo_mantenimientos", "anios", "meses", "registros", "registros_anio_mes", "personals", "registros_personal"))->setPaper('letter', 'portrait');

        // ENUMERAR LAS PÁGINAS
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $alto = $canvas->get_height();
        $ancho = $canvas->get_width();
        $canvas->page_text($ancho - 90, $alto - 25, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 9, array(0, 0, 0));

        return $pdf->stream('resumen_ots.pdf');
    }
    public function grafico_ots(Request $request)
    {
        $filtro = $request->filtro;
        $anio = $request->anio;
        $mes = $request->mes;
        if ($filtro != "todos") {
            $request->validate([
                "anio" => "required",
                "mes" => "required",
            ], [
                "anio.required" => "Debes seleccionar un año",
                "mes.required" => "Debes seleccionar un mes",
            ]);
        }

        $tipo_mantenimientos = [
            "PR" => "PREVENTIVO",
            "CO" => "CORRECTIVO",
            "PRE" => "PREDICTIVO",
            "IN" => "INSPECCIÓN",
            "LU" => "LUBRICACIÓN",
        ];
        $data = [];
        foreach ($tipo_mantenimientos as $tm) {
            $orden_trabajos = count(OrdenTrabajo::where("tipo_mantenimiento", $tm)->get());
            if ($filtro == 'anio_mes') {
                $orden_trabajos = count(OrdenTrabajo::where("tipo_mantenimiento", $tm)
                    ->where("fecha_programada", "LIKE", "$anio-$mes%")->get());
            }
            $data[] = ["name" => $tm, "y" => (int)$orden_trabajos];
        }

        $fecha = date("d/m/Y");
        return response()->JSON([
            "sw" => true,
            "datos" => $data,
            "fecha" => $fecha
        ]);
    }
}
