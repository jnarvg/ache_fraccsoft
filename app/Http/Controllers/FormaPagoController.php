<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\FormaPago;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use DB;
use App\Http\Middleware\Authenticate;

class FormaPagoController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $formas_pago = DB::table('forma_pago')
        ->select('id_forma_pago', 'forma_pago')
        ->orderby('forma_pago','ASC')
        ->get();
        return view('catalogos.forma_pago.index',['formas_pago'=>$formas_pago]);
    }
    public function store(request $request)
    {
        $forma_pago = new FormaPago();
        $forma_pago->forma_pago = $request->get('nueva_forma_pago');
        $forma_pago->save();
        return redirect()->route('forma_pago');
    }
    public function show($id)
    {
        $forma_pago = DB::table('forma_pago')
        ->select('id_forma_pago', 'forma_pago')
        ->where('id_forma_pago','=',$id)
        ->first();
        return view('catalogos.forma_pago.show',['forma_pago'=>$forma_pago]);
    }
    public function update(request $request, $id)
    {
        $forma_pago = FormaPago::findOrFail($id);
        $forma_pago->forma_pago = $request->get('forma_pago');
        $forma_pago->update();
        return redirect()->route('forma_pago',['id'=>$id]);
    }
    public function destroy($id)
    {
        $forma_pago = FormaPago::find($id);
        $forma_pago->delete();
        return redirect()->route('forma_pago');
    }
}
