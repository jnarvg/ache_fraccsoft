@extends('layouts.admin')
@section('title')
Configuracion Campos
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
            {{ Form::open(array('action'=>array('ConfiguracionCamposController@update',$campos_configuracion->id_campos_configuracion),'method'=>'post','files'=>'true', 'id')) }}
              <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tabla">*Tabla</label>
                        <select name="tabla" id="tabla" class="letrasModal form-control" required="true">
                          @foreach ($tablas as $pros)
                            @if ($pros->Tables_in_db_ache == $campos_configuracion->tabla)
                              <option selected value="{{ $pros->Tables_in_db_ache }}">{{ $pros->Tables_in_db_ache }}</option>
                            @else
                              <option value="{{ $pros->Tables_in_db_ache }}">{{ $pros->Tables_in_db_ache }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="campo">Campo</label>
                        <input type="text" name="campo" id="campo" value="{{ $campos_configuracion->campo }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="label">Label</label>
                        <input type="text" name="label" id="label" value="{{ $campos_configuracion->label }}"  class="letrasModal form-control" />
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="tipo_dato">Tipo dato</label>
                        <select name="tipo_dato" id="tipo_dato" class="letrasModal form-control" required="true">
                          @foreach ($tipos as $s)
                            @if ($s == $campos_configuracion->tipo_dato )
                            <option selected value="{{ $s }}">{{ $s }}</option>
                            @else
                            <option value="{{ $s }}">{{ $s }}</option>
                            @endif
                          @endforeach
                        </select>

                    </div>
                </div>
                <div class="col-md-12">
                  <h3>Configuracion nivel campo</h3>
                  <hr>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="pk">PK</label>
                        <select name="pk" id="pk" class="letrasModal form-control" required="true">
                          @foreach ($siorno as $s)
                            @if ($s == $campos_configuracion->pk )
                            <option selected value="{{ $s }}">{{ $s }}</option>
                            @else
                            <option value="{{ $s }}">{{ $s }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="unique">*Unique</label>
                        <select name="unique" id="unique" class="letrasModal form-control" required="true">
                          @foreach ($siorno as $s)
                            @if ($s == $campos_configuracion->unique )
                            <option selected value="{{ $s }}">{{ $s }}</option>
                            @else
                            <option value="{{ $s }}">{{ $s }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="hidden">*Hidden</label>
                        <select name="hidden" id="hidden" class="letrasModal form-control" required="true">
                          @foreach ($siorno as $s)
                            @if ($s == $campos_configuracion->hidden )
                            <option selected value="{{ $s }}">{{ $s }}</option>
                            @else
                            <option value="{{ $s }}">{{ $s }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>  
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="readonly">*Readonly</label>
                        <select name="readonly" id="readonly" class="letrasModal form-control" required="true">
                          @foreach ($siorno as $s)
                            @if ($s == $campos_configuracion->readonly )
                            <option selected value="{{ $s }}">{{ $s }}</option>
                            @else
                            <option value="{{ $s }}">{{ $s }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div> 
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="requerido">*Requerido</label>
                        <select name="requerido" id="requerido" class="letrasModal form-control" required="true">
                          @foreach ($siorno as $s)
                            @if ($s == $campos_configuracion->requerido )
                            <option selected value="{{ $s }}">{{ $s }}</option>
                            @else
                            <option value="{{ $s }}">{{ $s }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>  
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="importable">*Importable</label>
                        <select name="importable" id="importable" class="letrasModal form-control" required="true">
                          @foreach ($siorno as $s)
                            @if ($s == $campos_configuracion->importable )
                            <option selected value="{{ $s }}">{{ $s }}</option>
                            @else
                            <option value="{{ $s }}">{{ $s }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="actualizable">*Actualizable</label>
                        <select name="actualizable" id="actualizable" class="letrasModal form-control" required="true">
                          @foreach ($siorno as $s)
                            @if ($s == $campos_configuracion->actualizable )
                            <option selected value="{{ $s }}">{{ $s }}</option>
                            @else
                            <option value="{{ $s }}">{{ $s }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                  <h3>RELACION LLAVE FORANEA</h3>
                  <hr>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fk_tabla">*FK Tabla</label>
                        <select name="fk_tabla" id="fk_tabla" class="letrasModal form-control" >
                          <option value=""></option>
                          @foreach ($tablas as $pros)
                            @if ($pros->Tables_in_db_ache == $campos_configuracion->fk_tabla)
                              <option selected value="{{ $pros->Tables_in_db_ache }}">{{ $pros->Tables_in_db_ache }}</option>
                            @else
                              <option value="{{ $pros->Tables_in_db_ache }}">{{ $pros->Tables_in_db_ache }}</option>
                            @endif
                          @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fk_campo">*FK Campo</label>
                        <input type="text" name="fk_campo" id="fk_campo" value="{{ $campos_configuracion->fk_campo }}" class="letrasModal form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="fk_pk">*FK ID primaria</label>
                        <input type="text" name="fk_pk" id="fk_pk" value="{{ $campos_configuracion->fk_pk }}" class="letrasModal form-control">
                    </div>
                </div>      
              </div>
              <div class="row">
                  <div class="col-md-3 offset-md-3">
                      <div class="form-group">
                          <a href="{{ route('campos_configuracion') }}" class="btn btn-dark btn-block">CANCELAR</a>
                      </div>
                  </div>
                  @if ($id == 1)
                  <div class="col-md-3">
                      <div class="form-group">
                          <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                      </div>
                  </div>
                  @endif
              </div>
            
            {{ Form::close()}}
        </div>
    </div>
</div>

@push('scripts')
@endpush 
@endsection