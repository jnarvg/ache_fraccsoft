@extends('layouts.admin')
@section('title')
Importar desde excel
@endsection
@section('filter')
@endsection
@section('content')
<div class="content mt-3">
  <div class="card mt-3">
    <div class="card-body">
      {{ Form::open(array('action'=>array('ImportController@importar_excel_finally'),'method'=>'post','files'=>'true')) }}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Tipo de importacion</label>
              <select class="form-control form-control-sm" name="tipo_importacion" id="tipo_importacion" required="true">
                <option value="Actualizar">Actualizar</option>
                <option value="Importar">Importar</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Tabla</label>
              <input type="text" name="tabla" id="tabla" class="form-control form-control-sm" value="{{ $request->tabla }}">
            </div>
          </div>
          <div class="col-md-3">
              <div class="form-group">
                <label>Campo llave</label>
                <select class="form-control form-control-sm" name="campo_llave" id="campo_llave">
                  <option value=""></option>
                  @foreach ($campos as $h)
                    <option value="{{ $h->campo }}">{{ $h->label }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="path">*Archivo XLSX</label>
              <input type="text" name="path" id="path" class="form-control form-control-sm" value="{{ $path }}">
            </div>
          </div>
          @foreach ($campos as $e)
            <div class="col-md-3">
              <div class="form-group">
                <label>{{ $e->label }}</label>
                <select class="form-control form-control-sm" name="{{ $e->campo }}" id="{{ $e->campo }}">
                  <option value=""></option>
                  @foreach ($headings[0][0] as $h)
                    <option value="{{ $h }}">{{ $h }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          @endforeach
        </div>
        <div class="row">
          <div class="col-md-2 offset-md-8">
            <a href="{{ route('importar') }}" class="btn btn-dark btn-block">Cancelar</a>
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary btn-block">Importar</button>
          </div>
        </div>
      {{ Form::close()}} 
    </div>
  </div>
</div>
@push('scripts')
@endpush 
@endsection