<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = "notificaciones";

    protected $fillable = [
        "tipo",
        "descripcion",
        "indumentaria",
        "fecha",
        "hora",
        "imagen",
        "visto"
    ];

    protected $appends = ['path_image', 'hace'];
    public function getPathImageAttribute()
    {
        if ($this->imagen && trim($this->imagen) != "") {
            return asset('imgs/notificaciones/' . $this->imagen);
        }
        return asset('imgs/notificaciones/default.png');
    }

    public function getHaceAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // FUNCION PARA VACIAR LAS IMAGENES CON ENTRADAS NORMALES
    public static function vaciaNormales($id)
    {
        $ultima_notificacion = Notificacion::get()->last();
        if ($ultima_notificacion) {
            $notificacions = Notificacion::where("id", "<", $id)->where("tipo", "NORMAL")->get();
            foreach ($notificacions as $n) {
                // Log::debug($n->id);
                // Log::debug($n->tipo);
                // Log::debug($n->imagen);
                if ($n->id != $ultima_notificacion->id) {
                    \File::delete(public_path() . "/imgs/notificaciones/" . $n->imagen);
                    $n->delete();
                }
            }
        }

        return true;
    }
}
