<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; /// por lo tanto aqui debe ir el nombre que seria App y no sisVentas
use App\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use DB;
use App\Http\Middleware\Authenticate;

class AgenteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store(Request $request) {
        $user = new User;
        $user->name = $request->input('nuevo_name_mdl');
        $user->email = $request->input('nuevo_email_mdl');
        $user->password = bcrypt( $request->input('nuevo_pass_mdl') );
        $user->password_nohash = Crypt::encryptString($request->input('nuevo_pass_mdl') );
        $user->rol = $request->input('nuevo_rol_mdl');
        $user->save();
        return redirect()->route('usuarios');
    }
    public function show($id)
    {   
        $usuario = DB::table('users as u')
        ->join('rol as r','r.id','=','u.rol','left',false)
        ->select('u.id','u.rol as rol_id','r.rol','r.id as id_rol','name','password','email','created_at','u.estatus','u.password_nohash')
        ->where('u.id','=',$id)
        ->first();
        if ($usuario->password_nohash != '') {
            $passsinHash = Crypt::decryptString($usuario->password_nohash);
        }else{
            $passsinHash = 'admin123';
        }

        $roles = DB::table('rol')->get();
        return view('catalogos.agentes.show',["usuario"=>$usuario,"roles"=>$roles,'passsinHash'=>$passsinHash]);

    }
    public function profile($id)
    {   
        $usuario = DB::table('users as u')
        ->join('rol as r','r.id','=','u.rol','left',false)
        ->select('u.id','r.rol','r.id as id_rol','name','password','email','created_at','u.estatus','u.foto_perfil','u.password_nohash')
        ->where('u.id','=',$id)
        ->first();
        if ($usuario->password_nohash != '') {
            $passsinHash = Crypt::decryptString($usuario->password_nohash);
        }else{
            $passsinHash = 'admin123';
        }

        
        return view('catalogos.agentes.profile',["usuario"=>$usuario,'passsinHash'=>$passsinHash]);

    }
    public function updateprofile(request $request, $id)
    {   
        $user = User::findOrFail($id);
        if (!empty($request->file('file-input'))) 
        {   //hasfile=si tiene algun archivo
            $file = $request->file('file-input');
            

            $path = Storage::disk('public')->put('avatar',$file);
            $foto_eliminar = $user->foto_perfil;
            $user->foto_perfil = $path;
            if ($foto_eliminar != null and $foto_eliminar != '') {
                 if (false !== strpos($foto_eliminar, "avatar/")){
                    Storage::disk('public')->delete($foto_eliminar);
                }else{
                    Storage::disk('public')->delete('avatar/'.$foto_eliminar);
                }
            }
        }
        $user->name = $request->get('nombre-user');
        $user->email = $request->get('nombre-email');
        $user->password = bcrypt($request->get('nombre-pass'));
        $user->password_nohash = Crypt::encryptString($request->input('nombre-pass') );
        $user->update();
        //return json_encode($request);  
        return back();
    }
    public function index(request $request)
    {
        $search = $request->get('search');
        $estatus_bs = $request->get('estatus_bs');
        $rol_bs = $request->get('rol_bs');
        $usuarios = DB::table('users as u')
        ->join('rol as r','r.id','=','u.rol','left',false)
        ->select('u.id','u.rol as rol_id','r.rol','r.id as id_rol','name','password','email','created_at','u.estatus')
        ->orderby('u.name','ASC')
        ->where('name','LIKE',"%$search%")
        ->where('r.rol','LIKE',"%$rol_bs%")
        ->where('u.estatus','LIKE',"%$estatus_bs%")
        ->where('r.id','!=',5)
        ->get();

        $roles = DB::table('rol')->get();
        $estatus = array('Activo','Inactivo');
        return view('catalogos.agentes.index', compact('estatus','roles','usuarios','request'));
    }
    public function activa($id)
    {
        $user = User::findOrFail($id);
        $user->estatus = 'Activo';
        $user->update();

        return redirect()->route('usuarios');
    }
    public function inactiva($id)
    {
        $user = User::findOrFail($id);
        $user->estatus = 'Inactivo';
        $user->update();
        
        return redirect()->route('usuarios');    
    }
    public function update(request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->get('nombre-user');
        $user->email = $request->get('nombre-email');
        $user->rol = $request->get('nombre-rol');
        $user->password = bcrypt($request->get('nombre-pass'));
        $user->password_nohash = Crypt::encryptString($request->input('nombre-pass') );
        $user->update();

        return redirect()->route('usuarios');
    }

}
