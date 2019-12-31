@extends('layouts.admin')
@section('title')
Regimen fiscal
@endsection
@section('filter')
  <a href="#" data-toggle="modal" data-target="#modal_nuevo"><button class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus"></i></button></a>
  <button href="#" data-toggle="collapse" data-target="#filtros" role="button" aria-expanded="false" aria-controls="filtros" class="mb-0 d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-filter"></i></button>
@endsection
@section('content')
<div class="content mt-3">
  <div class="card show" id="filtros">
    <div class="card-body">
      
      <div class="row">
        <div class="col-lg-12">
        {!! Form::open(array('route'=>'regimen_fiscal', 'method'=>'get', 'autocomplete'=>'off')) !!}
          <div class="input-group md-form form-sm form-4 pl-0">

            <input type="text" class="form-control" placeholder="Search" name="nombre_bs" id="nombre_bs" value="{{ $request->nombre_bs }}">

            <button type="submit" class="btn btn-info" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
          </div>
        {!! Form::close() !!}
        </div>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-body">
      @if (session()->has('msj'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          <strong>{{ session('msj') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder text-sm" id="tabla">
          <thead class="thead-grubsa ">
            <th class="center">
              Clave
            </th>
            <th class="center">
              Regimen Fiscal
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($regimen_fiscal as $r)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('RegimenFiscalController@show', $r->id_regimen_fiscal)}}" >
                {{ $r->clave}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('RegimenFiscalController@show', $r->id_regimen_fiscal)}}" >
                {{ $r->regimen_fiscal}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('RegimenFiscalController@show', $r->id_regimen_fiscal)}}" ><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
                <a href="#" data-target="#modal-delete{{$r->id_regimen_fiscal}}" data-toggle="modal"><button class="btn-ico" ><i class="fas fa-trash"></i></button></a>
              </td>
            </tr>
            @include('catalogos.regimen_fiscal.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{-- {{$regimen_fiscal->appends(Request::only('nombre_bs','estado_bs','ciudad_bs','id_bs'))->render()}} --}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_nuevo">
  {{ Form::open(array('action'=>array('RegimenFiscalController@store'),'method'=>'post', 'files'=>true)) }}
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h4 class="modal-title text-dark">Nuevo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="clave">*Clave</label>
                          <input type="text" name="clave" id="clave" value=""  class="letrasModal form-control" required="true" />
                      </div>
                  </div>             
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="regimen_fiscal">*Regimen fiscal</label>
                          <input type="text" name="regimen_fiscal" id="regimen_fiscal" value="" minlength="2" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-dark" data-dismiss="modal">Cerrar</button>
              <button type="submit" class="btn btn-info">Confirmar</button>
          </div>
      </div>
  </div>
  {{ Form::close()}}  
</div>
@push('scripts')
<script type="text/javascript">
  jQuery(document).ready(function($)
  {
    $('#tabla').DataTable();
  });
</script>
<script>
  jQuery(document).ready(function($)
    {
      $("#pais").on('change', function(){
        pais = $('#pais').val();
        selectEstado = $('#estado');
        selectEstado.empty().append('<option>Cargando paises</option>');


        $.ajax({

        type: "GET",
        url: "/catalogo-estados/" + pais,
        success: function(data) {
              var htmlOptions = [];
              if( data.length ){
                  html = '<option value="Vacio" disabled="true" selected="true">Selecciona...</option>';
                  htmlOptions[htmlOptions.length] = html;
                  for( item in data ) {
                    //en caso de ser un select
                    html = '<option value="' + data[item].id_estado + '">' + data[item].estado + '</option>';
                    htmlOptions[htmlOptions.length] = html;
                  }

                  //en caso de ser un input
                  //$("#precio_propiedadctz").val(html);
                  
                  // se agregan las opciones del catalogo en caso de ser un select 
                  selectEstado.empty().append( htmlOptions.join('') );
              }else{
                html = '<option value="Vacio" disabled="true" selected="true">No hay estados</option>';
                htmlOptions[htmlOptions.length] = html;
                selectEstado.empty().append( htmlOptions.join('') );
              }
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de estados");
        }
        })
      });
      $("#estado").on('change', function(){
        pais = $('#estado').val();
        selectCiudad = $('#ciudad');
        selectCiudad.empty().append('<option>Cargando paises</option>');


        $.ajax({

        type: "GET",
        url: "/catalogo-ciudades/" + pais,
        success: function(data) {
              var htmlOptions = [];
              if( data.length ){
                  html = '<option value="Vacio" disabled="true" selected="true">Selecciona...</option>';
                  htmlOptions[htmlOptions.length] = html;
                  for( item in data ) {
                    //en caso de ser un select
                    html = '<option value="' + data[item].id_ciudad + '">' + data[item].ciudad + '</option>';
                    htmlOptions[htmlOptions.length] = html;
                  }

                  //en caso de ser un input
                  //$("#precio_propiedadctz").val(html);
                  
                  // se agregan las opciones del catalogo en caso de ser un select 
                  selectCiudad.empty().append( htmlOptions.join('') );
              }else{
                html = '<option value="Vacio" disabled="true" selected="true">No hay ciudades</option>';
                htmlOptions[htmlOptions.length] = html;
                selectCiudad.empty().append( htmlOptions.join('') );
              }
        },
          error: function(error) {
          alert("No se pudo cargar el catalogo de ciudades");
        }
        })
      });
    });
</script>
<script type="application/javascript">
    
    jQuery('input[type=file]').change(function(){
     var filename = jQuery(this).val().split('\\').pop();
     var idname = jQuery(this).attr('id');
     jQuery('span.'+idname).next().find('span').html(filename);
    });
</script>
<script src="{{ asset('js/uploadfotos.js') }}"></script>
@endpush 
@endsection