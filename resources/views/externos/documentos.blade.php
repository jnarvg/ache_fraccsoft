@extends('layouts.admin')
@section('title')
Documentos
@endsection
@section('content')
<div class="content mt-3">
  @php
    $procedencia ='Menu';
  @endphp
  <div class="card">
    <div class="card-body">
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Titulo
            </th>
            <th class="center">
              Archivo
            </th>
            <th class="center">
              Fecha
            </th>
            <th class="center">
              Notas
            </th>
          </thead>
          <tbody>
            @foreach ($documentos as $doc)                
            <tr>
              <td class="center">
                {{ $doc->titulo}}
              </td>
              <td class="center">
                <a class="" href="{{ url('storage',$doc->archivo) }}" target="_bank"  style="width: 30%;">
                {{ $doc->archivo}}</a> 
              </td>
              <td class="center">
                {{ date('Y-m-d',strtotime($doc->fecha)) }}
              </td>            
              <td class="center">
                {{ $doc->notas}}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$documentos->render()}}
</div>
@push('scripts')
<script>
function ValidateSize(file) {
        var FileSize = file.files[0].size / 1024 / 1024; // in MB
        if (FileSize > 2) {
            alert('No se puede insertar un archivo excedente de 2 megas');
           // $(file).val(''); //for clearing with Jquery
        }
    }
</script>
@endpush 
@endsection