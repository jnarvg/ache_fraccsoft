<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\ConfiguracionCampos ;
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use DB;
use Auth;
use App\Http\Middleware\Authenticate;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TablasImport;
class ImportController extends Controller
{
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $tablas = DB::table('campos_configuracion')
        ->select('tabla')->where('importable','SI')->groupby('tabla')->get();
        return view('imports.index', compact('tablas'));
    }
    public function importar_excel(request $request)
    {
        if (!empty($request->file('archivo_xlsx'))) 
        {   
            $file = $request->file('archivo_xlsx');

            $path =  Storage::disk('public')->put('uploads',$file);
            //$path = Storage::disk('public')->get($path);
            HeadingRowFormatter::default('none');
            $headings = (new HeadingRowImport)->toArray($file);

            $campos = DB::table('campos_configuracion')
            ->where('tabla',$request->get('tabla'))
            ->where('importable', 'SI')
            ->get();
            //$path = Storage::disk('public')->get('uploads',$file);

        }
        return view('imports.import', compact('headings','request','campos','path','file'));
    }
    public function importar_excel_finally(request $request)
    {
      
      
      $user = auth()->user()->id;
      $file = $request->file('archivo_xlsx');


      Excel::import(new TablasImport($request->tabla,$request->tipo_importacion, $request, $user, $request->get('campo_llave')), $request->get('path'));

      Storage::delete($request->get('path'));
      return redirect()->route('importar')->with('msj','Se ha importado la informacion con exito, revise la informacion');
    }
}
