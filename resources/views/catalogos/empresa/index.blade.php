@extends('layouts.admin')
@section('title')
Empresas
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
        {!! Form::open(array('route'=>'empresa', 'method'=>'get', 'autocomplete'=>'off')) !!}
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
        <table class="table table-hover table-withoutborder text-sm">
          <thead class="thead-grubsa ">
            <th class="center">
              Tipo
            </th>
            <th class="center">
              Nombre comercial
            </th>
            <th class="center">
              RFC
            </th>
            <th class="center">
              Estado
            </th>
            <th class="center">
              Ciudad
            </th>
            <th class="center">
              Acciones
            </th>
          </thead>
          <tbody>
            @foreach ($empresas as $e)                
            <tr>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EmpresaController@show', $e->id_empresa)}}" >
                {{ $e->tipo}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EmpresaController@show', $e->id_empresa)}}" >
                {{ $e->nombre_comercial}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EmpresaController@show', $e->id_empresa)}}" >
                {{ $e->rfc}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EmpresaController@show', $e->id_empresa)}}" >
                {{ $e->estado}}</a>
              </td>
              <td class="center">
                <a class="text-dark" href="{{URL::action('EmpresaController@show', $e->id_empresa)}}" >
                {{ $e->ciudad}}</a>
              </td>
              <td class="center-acciones">
                <a href="{{URL::action('EmpresaController@show', $e->id_empresa)}}" ><button class="btn-ico"><i class="fas fa-pencil-alt"></i></button></a>
                <a href="#" data-target="#modal-delete{{$e->id_empresa}}" data-toggle="modal"><button class="btn-ico"><i class="fas fa-trash"></i></button></a>
              </td>
            </tr>
            @include('catalogos.empresa.modal')
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  {{$empresas->appends(Request::only('nombre_bs'))->render()}}
</div>
<div class="modal collapse" aria-hidden="true" role="dialog" tabindex="-1" id="modal_nuevo">
  {{ Form::open(array('action'=>array('EmpresaController@store'),'method'=>'post', 'files'=>true)) }}
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
                          <label for="tipo">*Tipo</label>
                          <select class="form-control" id="tipo" name="tipo">
                            @foreach ($tipos as $p)
                              <option value="{{ $p }}">{{ $p }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>  
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="razon_social">*Razón social</label>
                          <input type="text" name="razon_social" id="razon_social" value="" minlength="2" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="nombre_comercial">*Nombre comercial</label>
                          <input type="text" name="nombre_comercial" id="nombre_comercial" value="" minlength="2" class="letrasModal form-control" required="true" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="rfc">RFC</label>
                          <input type="text" name="rfc" id="rfc" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="calle">Calle</label>
                          <input type="text" name="calle" id="calle" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="n_exterior"># Exterior</label>
                          <input type="text" name="n_exterior" id="n_exterior" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="n_interior"># Interior</label>
                          <input type="text" name="n_interior" id="n_interior" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="colonia">Colonia</label>
                          <input type="text" name="colonia" id="colonia" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="codigo_postal">Codigo postal</label>
                          <input type="text" name="codigo_postal" id="codigo_postal" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="pais">Pais</label>
                          <select class="form-control" id="pais" name="pais">
                            <option value="Vacio">Selecciona...</option>
                            @foreach ($paises as $p)
                              <option value="{{ $p->id_pais }}">{{ $p->pais }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="estado">Estado</label>
                          <select class="form-control" id="estado" name="estado">
                            <option value="Vacio">Selecciona...</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="ciudad">Ciudad</label>
                          <select class="form-control" id="ciudad" name="ciudad">
                            <option value="Vacio">Selecciona...</option>
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="regimen_fiscal">Regimen fiscal</label>
                          <select class="form-control" id="regimen_fiscal" name="regimen_fiscal">
                            @foreach ($regimen_fiscales as $p)
                              <option value="{{ $p->id_regimen_fiscal }}">{{ $p->concatenado }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="banco">Banco</label>
                          <select class="form-control" id="banco" name="banco">
                            <option value="Vacio">Selecciona...</option>
                            @foreach ($bancos as $p)
                              <option value="{{ $p->id_banco }}">{{ $p->banco }}</option>
                            @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="rfc_banco">RFC Banco</label>
                          <input type="text" name="rfc_banco" id="rfc_banco" value=""  class="letrasModal form-control" />
                      </div>
                  </div>
                  <div class="col-md-6">
                      <div class="form-group">
                          <label for="cuenta_bancaria">Cuenta Bancaria</label>
                          <input type="text" name="cuenta_bancaria" id="cuenta_bancaria" value=""  class="letrasModal form-control" />
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
      $("#banco").on('change', function(){
        banco = $('#banco').val();
        $.ajax({

        type: "GET",
        url: "/catalogo-bancos/" + banco,
        success: function(data) {
              var htmlOptions = [];
              if( data.length ){
                  for( item in data ) {
                    //en caso de ser un select
                    html = data[item].rfc;
                  }

                  //en caso de ser un input
                  $("#rfc_banco").val(html);
                  
                  // se agregan las opciones del catalogo en caso de ser un select 
                  //selectCiudad.empty().append( htmlOptions.join('') );
              }else{
                html = '';
                htmlOptions[htmlOptions.length] = html;
                selectCiudad.empty().append( htmlOptions.join('') );
              }
        },
          error: function(error) {
          alert("No se pudo obtener la informacion");
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