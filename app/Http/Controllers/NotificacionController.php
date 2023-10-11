<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $notificacions = Notificacion::where("tipo", "ALERTA")->get();
        return response()->JSON(['notificacions' => $notificacions, 'total' => count($notificacions)], 200);
    }

    public function orderByLast()
    {
        $notificacions = Notificacion::where("tipo", "ALERTA")->orderBy("created_at", "desc")->get();
        $no_vistos = count(Notificacion::where("tipo", "ALERTA")
            ->where("visto", 0)
            ->get());
        return response()->JSON(['notificacions' => $notificacions, 'no_vistos' => $no_vistos, 'total' => count($notificacions)], 200);
    }

    public function show(Notificacion $notificacion)
    {
        $notificacion->update(["visto" => 1]);
        return response()->JSON($notificacion);
    }

    public function getNuevaNotificacion()
    {
        $notificacion = Notificacion::get()->last();
        return response()->JSON($notificacion);
    }

    public function recibeNotificacion(Request $request)
    {
        $indumentaria = [
            "pantalon",
            "chaqueta",
            "chaleco",
            "guantes",
            "lentes",
            "botas",
            "casco",
            "auditivo"
        ];

        $indumentaria_text = [
            "pantalon" => "PANTALÓN AZUL",
            "chaqueta" => "CHAQUETA AZUL",
            "chaleco" => "CHALECO DE SEGURIDAD",
            "guantes" => "GUANTES DE SEGURIDAD",
            "lentes" => "LENTES DE SEGURIDAD",
            "botas" => "BOTAS DE SEGURIDAD",
            "casco" => "CASCO DE SEGURIDAD",
            "auditivo" => "PROTECTOR AUDITIVO",
        ];
        $array_uniforme_existente = explode(",", $request->input('uniforme_existente'));
        // Log::debug($request->prueba);
        // Log::debug($request->input('uniforme_existente'));
        // Log::debug(count($array_uniforme_existente));
        $array_uniforme_existente =  array_unique($array_uniforme_existente);
        $array_uniforme_existente =  array_values($array_uniforme_existente);
        $tipo_notificacion = "NORMAL";
        $array_indu_faltante = [];
        $descripcion = "";
        if (count($array_uniforme_existente) >= 8) {
            $tipo_notificacion = "NORMAL";
        } else {
            // si se detecta falta de uniforme crear la notificación en ALERTA
            $tipo_notificacion = "ALERTA";
            $descripcion = "NO PORTA";
            // ARMANDO DESCRIPCIÓN E INDUMENTARIA FALTANTE
            foreach ($indumentaria as $key => $indu) {
                if (!in_array($indu, $array_uniforme_existente)) {
                    $txt_indu = $indumentaria_text[$indu];
                    if ($key == 0) {
                        $descripcion .= " " . $txt_indu;
                    } else {
                        $descripcion .= ", " . $txt_indu;
                    }
                    $array_indu_faltante[] = $txt_indu;
                }
            }
        }
        $indumentaria = implode(",", $array_indu_faltante);
        // antes de guardar verificar que no existe uno igual mediante la columna INDUMENTARIA
        $ultimo = Notificacion::where("tipo", "ALERTA")->get()->last();
        if (!$ultimo || $ultimo->indumentaria != $indumentaria) {
            $nueva_notificacion = new Notificacion();
            $nueva_notificacion->tipo = $tipo_notificacion;
            $nueva_notificacion->descripcion = $descripcion;
            $nueva_notificacion->indumentaria = $indumentaria;

            if ($request->hasFile('image')) {
                $image = $request["image"];
                $nom_img = time() . random_int(1, 1000) . '.' . $image->getClientOriginalExtension();
                $nueva_notificacion->imagen = $nom_img;
                $image->move(public_path() . '/imgs/notificaciones/', $nom_img);
            }
            $nueva_notificacion->hora = date("H:i:s");
            $nueva_notificacion->fecha = date("Y-m-d");
            $nueva_notificacion->visto = 0;
            $nueva_notificacion->save();
            Notificacion::vaciaNormales($nueva_notificacion->id);
        }

        return response()->JSON(true, 200);
    }
}
