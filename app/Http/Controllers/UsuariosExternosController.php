<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; /// por lo tanto aqui debe ir el nombre que seria App y no sisVentas
use App\User;
use App\Prospecto;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use DB;
use App\Http\Middleware\Authenticate;

class UsuariosExternosController extends Controller
{
    public function index(request $request)
    {
        $search = $request->get('search');
        $rows_pagina = array('10','25','50','100');
        $rows_page = $request->get('rows_per_page');

        if ($rows_page == '') {
            $rows_page = 10;
        }
        $usuarios = DB::table('users as u')
        ->join('rol as r','r.id','=','u.rol','left',false)
        ->join('prospectos as p','u.prospecto_id','=','p.id_prospecto','left', false)
        ->select('u.id','u.rol as rol_id','r.rol','r.id as id_rol','name','password','email','created_at','u.estatus','u.prospecto_id','p.nombre as prospecto')
        ->orderby('u.name','ASC')
        ->where('name','LIKE',"%$search%")
        ->where('email','LIKE',"%$search%")
        ->where('r.id','=',5)
        ->get();

        $roles = DB::table('rol')
        ->where('id',5)
        ->get();

        $prospectos = DB::table('prospectos as p')
        ->join('estatus_crm as e','p.estatus','=','e.id_estatus_crm','left',false)
        ->select('id_prospecto','nombre')
        ->orderby('nombre','ASC')
        ->where('e.nivel','>=',4)
        ->where('e.nivel','!=',7)
        ->get();

        return view('catalogos.usuarios_externos.index',["usuarios"=>$usuarios, 'roles'=>$roles,'request'=>$request,'rows_pagina'=>$rows_pagina,'prospectos'=>$prospectos]);
    }
    public function store(Request $request) {

        $id_prospecto = $request->input('prospecto');
        if (!empty($id_prospecto)) {
            $prospecto = Prospecto::find($id_prospecto);
            if ($prospecto->correo != 'correo@correo' and $prospecto->correo != '') {
                $user = new User;
                $user->name = $prospecto->nombre;//nombre_prospecto
                $user->email = $prospecto->correo; //email_prospecto si no lo tiene no crear
                $user->password = bcrypt('123456');
                $user->password_nohash = Crypt::encryptString('123456');
                $user->rol = 5;//rol usuario externo
                $folio_externo = uniqid();
                $user->prospecto_id = $prospecto->id_prospecto;
                $user->save();
                $prospecto->cuenta_externa = 'Creada';
                $prospecto->update();
                return back()->with('msj','Usuario creado.');
            }else{
                return back()->with('msj','El correo no es valido.');
            }
        }else{
            return back()->with('msj','No elegiste el prospecto.');
        }
        
        return redirect()->route('usuarios_externos');
    }
    public function show($id)
    {   
        $usuario = DB::table('users as u')
        ->join('rol as r','r.id','=','u.rol','left',false)
        ->select('u.id','u.rol as rol_id','r.rol','r.id as id_rol','name','password','email','created_at','u.estatus','password_nohash')
        ->where('u.id','=',$id)
        ->first();

        if ($usuario->password_nohash != '') {
            $passsinHash = Crypt::decryptString($usuario->password_nohash);
        }else{
            $passsinHash = '123456';
        }

        $roles = DB::table('rol')->get();
        return view('catalogos.usuarios_externos.show',["usuario"=>$usuario,"roles"=>$roles, 'passsinHash'=>$passsinHash]);

    }
    

    public function update(request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->get('nombre-user');
        $user->email = $request->get('nombre-email');
        $user->password = bcrypt($request->get('nombre-pass'));
        $user->password_nohash = Crypt::encryptString('nombre-pass');
        $user->update();

        return redirect()->route('usuarios_externos');
    }

    public function destroy( $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('usuarios_externos')->with('msj','Usuario eliminado');
    }
}
