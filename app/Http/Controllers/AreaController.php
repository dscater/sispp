<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Equipo;
use App\Models\HistorialAccion;
use App\Models\Sistema;
use App\Models\Subunidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AreaController extends Controller
{
    public $validacion = [
        'codigo' => 'required|min:2|max:2|unique:areas,codigo',
        'nombre' => 'required|min:2',
    ];

    public $mensajes = [];

    public function index(Request $request)
    {
        $areas = Area::all();
        return response()->JSON(['areas' => $areas, 'total' => count($areas)], 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            // crear Area
            $nuevo_area = Area::create(array_map('mb_strtoupper', $request->all()));

            $datos_original = HistorialAccion::getDetalleRegistro($nuevo_area, "areas");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'CREACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' REGISTRO UN AREA',
                'datos_original' => $datos_original,
                'modulo' => 'AREAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return response()->JSON([
                'sw' => true,
                'area' => $nuevo_area,
                'msj' => 'El registro se realizó de forma correcta',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Area $area)
    {
        $this->validacion['codigo'] = 'required|min:2|max:2|unique:areas,codigo,' . $area->id;
        $request->validate($this->validacion, $this->mensajes);

        DB::beginTransaction();
        try {
            $datos_original = HistorialAccion::getDetalleRegistro($area, "areas");
            $area->update(array_map('mb_strtoupper', $request->all()));

            $datos_nuevo = HistorialAccion::getDetalleRegistro($area, "areas");
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'MODIFICACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' MODIFICÓ UN AREA',
                'datos_original' => $datos_original,
                'datos_nuevo' => $datos_nuevo,
                'modulo' => 'AREAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);

            DB::commit();
            return response()->JSON([
                'sw' => true,
                'area' => $area,
                'msj' => 'El registro se actualizó de forma correcta'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Area $area)
    {
        return response()->JSON([
            'sw' => true,
            'area' => $area
        ], 200);
    }

    public function destroy(Area $area)
    {
        DB::beginTransaction();
        try {
            $existe = Sistema::where("area_id", $area->id)->get();
            if (count($existe) > 0) {
                throw new Exception('No es posible eliminar el registro debido a que existen registros que lo utilizan');
            }
            $existe = Equipo::where("area_id", $area->id)->get();
            if (count($existe) > 0) {
                throw new Exception('No es posible eliminar el registro debido a que existen registros que lo utilizan');
            }
            $existe = Subunidad::where("area_id", $area->id)->get();
            if (count($existe) > 0) {
                throw new Exception('No es posible eliminar el registro debido a que existen registros que lo utilizan');
            }

            $datos_original = HistorialAccion::getDetalleRegistro($area, "areas");
            $area->delete();
            HistorialAccion::create([
                'user_id' => Auth::user()->id,
                'accion' => 'ELIMINACIÓN',
                'descripcion' => 'EL USUARIO ' . Auth::user()->usuario . ' ELIMINÓ UN AREA',
                'datos_original' => $datos_original,
                'modulo' => 'AREAS',
                'fecha' => date('Y-m-d'),
                'hora' => date('H:i:s')
            ]);
            DB::commit();
            return response()->JSON([
                'sw' => true,
                'msj' => 'El registro se eliminó correctamente'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->JSON([
                'sw' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
