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
      {{ Form::open(array('action'=>array('ImportController@importar_excel'),'method'=>'post','files'=>'true')) }}
      <div class="row">
        <div class="col-md-12">
          <h3>Bienvenido a importacion masiva desde excel</h3>
          <hr>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="tabla">*Tabla</label>
            <select name="tabla" id="tabla" class="letrasModal form-control selectpicker"  data-live-search="true"  required="true">
              <option value=""></option>
              @foreach ($tablas as $pros)
                <option value="{{ $pros->tabla }}">{{ $pros->tabla }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label for="archivo_xlsx">*Archivo XLSX</label>
            <input type="file" name="archivo_xlsx" id="archivo_xlsx" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <button type="submit" class="btn btn-primary down">Subir</button>
          </div>
        </div>
      </div>
      {{ Form::close()}}
      @if (session()->has('msj'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong>{{ session('msj') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
    </div>
  </div>
</div>
@push('scripts')
@endpush 
@endsection