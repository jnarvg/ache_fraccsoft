<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Color;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class ColoresController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $colores = DB::table('color')
        ->select('id_color', 'color','codigo_hexadecimal')
        ->orderby('color','ASC')
        ->get();
        return view('catalogos.color.index',['colores'=>$colores]);
    }
    public function store(request $request)
    {
        $color = new Color();
        $color->color = $request->get('nuevo_color_mdl');
        $color->codigo_hexadecimal = $request->get('nuevo_codigo_mdl');
        $color->save();
        return redirect()->route('color');
    }
    public function show($id)
    {
        $color = DB::table('color')
        ->select('id_color', 'color','codigo_hexadecimal')
        ->where('id_color','=',$id)
        ->first();
        return view('catalogos.color.show',['color'=>$color]);
    }
    public function update(request $request, $id)
    {
        $color = Color::findOrFail($id);
        $color->color = $request->get('color');
        $color->codigo_hexadecimal = $request->get('codigo_hexadecimal');
        $color->update();
        return redirect()->route('color',['id'=>$id]);
    }
    public function destroy($id)
    {
        $color = Color::find($id);
        $color->delete();
        return redirect()->route('color');
    }
}
