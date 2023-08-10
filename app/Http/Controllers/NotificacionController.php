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
        // Log::debug($request->prueba);
        // Log::debug($request->input('uniforme_existente'));
        // Log::debug(count($array_uniforme_existente));
        $array_uniforme_existente = explode(",", $request->input('uniforme_existente'));
        $nueva_notificacion = new Notificacion();
        if (count($array_uniforme_existente) >= 8) {
            $nueva_notificacion->tipo = "NORMAL";
        } else {
            // si se detecta falta de uniforme crear la notificación en ALERTA
            $nueva_notificacion->tipo = "ALERTA";
            $descripcion = "NO PORTA";
            $array_indu_faltante = [];
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
            $nueva_notificacion->descripcion = $descripcion;
            $nueva_notificacion->indumentaria = implode(",", $array_indu_faltante);
        }
        if ($request->hasFile('image')) {
            $image = $request["image"];
            $nom_img = time() . '.' . $image->getClientOriginalExtension();
            $nueva_notificacion->imagen = $nom_img;
            $image->move(public_path() . '/imgs/notificaciones/', $nom_img);
        }
        $nueva_notificacion->hora = date("H:i:s");
        $nueva_notificacion->fecha = date("Y-m-d");
        $nueva_notificacion->save();
        Notificacion::vaciaNormales($nueva_notificacion->id);
        return response()->JSON(true, 200);
    }
}
