<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index(Request $request)
    {
        $notificacions = Notificacion::all();
        return response()->JSON(['notificacions' => $notificacions, 'total' => count($notificacions)], 200);
    }
}
