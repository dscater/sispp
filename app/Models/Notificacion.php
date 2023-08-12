<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $notificacions = Notificacion::where("id", "<", $id)->where("tipo", "NORMAL")->get();
        foreach ($notificacions as $n) {
            \File::delete(public_path() . "/imgs/notificaciones/" . $n->imagen);
            $n->delete();
        }

        return true;
    }
}
