<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Propiedad;
use App\ImagenPropiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class CargaInfoController extends Controller
{
    public function subirImagenes()
    {
        $fotos = array( '1601', '1602', '1603', '1604', '1605', '1606', '1607', '1608', '1609', '1610');
        $folio = uniqid();
        $i = 0;
        $ruta = 'imagenes_propiedad/';
        $extension = '-planocolor.png';
        foreach ($fotos as $key) {
            $propiedad = DB::table('propiedad')
            ->select('id_propiedad')
            ->where('nombre', $key)
            ->first();
            if ($propiedad) {
                $imagen_propiedad = new ImagenPropiedad();
                $imagen_propiedad->titulo = 'Nivel '.$key;
                $imagen_propiedad->propiedad_id = $propiedad->id_propiedad;
                $imagen_propiedad->imagen_path= $ruta.$key.$extension;
                $imagen_propiedad->save();
            }
            $i = $i + 1;
        }

        $imagenes_propiedad = DB::table('imagen_propiedad')->get();
        return json_encode($imagenes_propiedad);
    }
}
