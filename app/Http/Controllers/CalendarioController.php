<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests; 
use App\Event;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\User;
use DB;
use App\Http\Middleware\Authenticate;

class CalendarioController extends Controller
{
    //
    public function _construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $calendarios = DB::table('events')
        ->select('id','title' ,'start_date','end_date')
        ->orderby('id','ASC')
        ->paginate(10);
        return view('Calendario.calendario',['calendarios'=>$calendarios]);
    }

    public function store(Request $request)
     {  

        $calendario = new Event();  
        $FechaI=$request->get('fechaI');
        $HoraI=$request->get('horaI');
        $FechaF=$request->get('fechaF');
        $HoraF=$request->get('horaF');
        $calendario->title = $request->get('titulo');
        $calendario->start_date = $FechaI.' '.$HoraI;

        $calendario->end_date = $FechaF.' '.$HoraF;
        $calendario->save();
        return redirect()->route('calendarios');
     }
     public function show($id)
      {
          $calendario = DB::table('events')
          ->select('id','title' ,'start_date','end_date')
          ->where('id','=',$id)
          ->first();
          return view('Calendario.show',['calendario'=>$calendario]);
      }
      public function update(request $request, $id)
    {

        $calendario = Event::findOrFail($id);
        
        $FechaI=$request->get('fechaI');
        $HoraI=$request->get('horaI');
        $FechaF=$request->get('fechaF');
        $HoraF=$request->get('horaF');
        $calendario->title = $request->get('titulo');
        $calendario->start_date = $FechaI.' '.$HoraI;
        $calendario->end_date = $FechaF.' '.$HoraF;
        $calendario->update();
        return redirect()->route('calendarios',['id'=>$id]);
    }
    public function destroy($id)
    {
        $calendario = Event::find($id);
        $calendario->delete();
        return redirect()->route('calendarios');
    }
}
