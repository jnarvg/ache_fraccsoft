<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\ImagenPropiedad;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use DB;
use App\Http\Middleware\Authenticate;

class ImagenesPropiedadController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $TiposOperacion = DB::table('tipo_operacion')
        ->select('id_tipo_operacion', 'tipo_operacion')
        ->orderby('tipo_operacion','ASC')
        ->paginate(10);
        return view('catalogos.tipo_operacion.index',['TiposOperacion'=>$TiposOperacion]);
    }
    public function store(request $request)
    {
        $imagen_propiedad = new ImagenPropiedad();
        $imagen_propiedad->titulo = $request->get('titulo');
        $imagen_propiedad->propiedad_id = $request->get('propiedad_mdl_imagen');
        if (!empty($request->file('file-input'))) 
        {   
            $file = $request->file('file-input');

            $path = Storage::disk('public')->put('imagenes_propiedad',$file);
            $imagen_propiedad->imagen_path= $path;
        }
        $imagen_propiedad->save();
        return back();
    }
    public function show($id)
    {
        $i = DB::table('imagen_propiedad')
        ->where('id_imagen','=',$id)
        ->first();

        
        return view('propiedades.imagen',['i'=>$i]);
    }
    public function update(request $request, $id)
    {
        $imagen_propiedad = ImagenPropiedad::findOrFail($id);
        $imagen_propiedad->titulo = $request->get('titulo');
        if (!empty($request->file('file-input'))) 
        {   
            $file = $request->file('file-input');

            $path = Storage::disk('public')->put('imagenes_propiedad',$file);
            $foto_eliminar = $imagen_propiedad->imagen_path;
            $imagen_propiedad->imagen_path= $path;
            if ($foto_eliminar != null and $foto_eliminar != '') {
                 if (false !== strpos($imagen_propiedad->imagen_path, "imagenes_propiedad/")){
                    Storage::disk('public')->delete($foto_eliminar);
                }else{
                    Storage::disk('public')->delete('imagenes_propiedad/'.$foto_eliminar);
                }
            }
        }
        $imagen_propiedad->update();
        return back();
    }
    public function destroy($id)
    {
        $imagen_propiedad = ImagenPropiedad::find($id);
        $imagen_propiedad->delete();
        return back();
    }
}
