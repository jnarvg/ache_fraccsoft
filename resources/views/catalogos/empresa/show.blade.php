@extends('layouts.admin')
@section('title')
Empresas
@endsection
@section('content')
<div class="content mt-3">
    <div class="card">
        <div class="card-body">
          {{ Form::open(array('action'=>array('EmpresaController@update',$empresa->id_empresa),'method'=>'post','files'=>true)) }}
            <div class="row">
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="tipo">*Tipo</label>
                      <select class="form-control" id="tipo" name="tipo">
                        @foreach ($tipos as $p)
                          @if ($p == $empresa->tipo)
                            <option selected="true" value="{{ $p }}">{{ $p }}</option>
                          @else
                            <option value="{{ $p }}">{{ $p }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>  
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="razon_social">*Razon social</label>
                      <input type="text" name="razon_social" id="razon_social" value="{{ $empresa->razon_social }}" minlength="2" class="letrasModal form-control" required="true" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="nombre_comercial">*Nombre comercial</label>
                      <input type="text" name="nombre_comercial" id="nombre_comercial" value="{{ $empresa->nombre_comercial }}" minlength="2" class="letrasModal form-control" required="true" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="rfc">RFC</label>
                      <input type="text" name="rfc" id="rfc" value="{{ $empresa->rfc }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="regimen_fiscal">Regimen fiscal</label>
                      <select class="form-control" id="regimen_fiscal" name="regimen_fiscal">
                        @foreach ($regimen_fiscales as $p)
                          @if ($p->id_regimen_fiscal == $empresa->regimen_fiscal_id )
                          <option selected="true" value="{{ $p->id_regimen_fiscal }}">{{ $p->concatenado }}</option>
                          @else
                          <option value="{{ $p->id_regimen_fiscal }}">{{ $p->concatenado }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-12">
                  <h4><em>Ubicacion</em></h4>
                  <hr class="hr-titulo" width="100%" size="10">
              </div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label for="calle">Calle</label>
                      <input type="text" name="calle" id="calle" value="{{ $empresa->calle }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="n_exterior"># Exterior</label>
                      <input type="text" name="n_exterior" id="n_exterior" value="{{ $empresa->n_exterior }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="n_interior"># Interior</label>
                      <input type="text" name="n_interior" id="n_interior" value="{{ $empresa->n_interior }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="colonia">Colonia</label>
                      <input type="text" name="colonia" id="colonia" value="{{ $empresa->colonia }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="codigo_postal">Codigo postal</label>
                      <input type="text" name="codigo_postal" id="codigo_postal" value="{{ $empresa->codigo_postal }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="pais">Pais</label>
                      <select class="form-control" id="pais" name="pais">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($paises as $p)
                          @if ($p->id_pais == $empresa->pais_id)
                            <option selected="true" value="{{ $p->id_pais }}">{{ $p->pais }}</option>
                          @else
                            <option value="{{ $p->id_pais }}">{{ $p->pais }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="estado">Estado</label>
                      <select class="form-control" id="estado" name="estado">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($estados as $p)
                          @if ($p->id_estado == $empresa->estado_id)
                            <option selected="true" value="{{ $p->id_estado }}">{{ $p->estado }}</option>
                          @else
                            <option value="{{ $p->id_estado }}">{{ $p->estado }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="ciudad">Ciudad</label>
                      <select class="form-control" id="ciudad" name="ciudad">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($ciudades as $p)
                          @if ($p->id_ciudad == $empresa->ciudad_id)
                            <option selected="true" value="{{ $p->id_ciudad }}">{{ $p->ciudad }}</option>
                          @else
                            <option value="{{ $p->id_ciudad }}">{{ $p->ciudad }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-12">
                  <h4><em>Bancos</em></h4>
                  <hr class="hr-titulo" width="100%" size="10">
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="banco">Banco</label>
                      <select class="form-control" id="banco" name="banco">
                        <option value="Vacio">Selecciona...</option>
                        @foreach ($bancos as $p)
                          @if ($p->id_banco == $empresa->banco_id)
                            <option selected="true" value="{{ $p->id_banco }}">{{ $p->banco }}</option>
                          @else
                            <option value="{{ $p->id_banco }}">{{ $p->banco }}</option>
                          @endif
                        @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="rfc_banco">RFC Banco</label>
                      <input type="text" name="rfc_banco" id="rfc_banco" value="{{ $empresa->rfc_banco }}"  class="letrasModal form-control" />
                  </div>
              </div>
              <div class="col-md-3">
                  <div class="form-group">
                      <label for="cuenta_bancaria">Cuenta Bancaria</label>
                      <input type="text" name="cuenta_bancaria" id="cuenta_bancaria" value="{{ $empresa->cuenta_bancaria }}"  class="letrasModal form-control" />
                  </div>
              </div>
            </div>
            <div class="row">    
                <div class="col-md-3 offset-md-3">
                    <div class="form-group">
                        <a href="{{ route('empresa') }}" class="btn btn-dark btn-block">REGRESAR</a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info btn-block">GUARDAR</button>
                    </div>
                </div> 
            </div>
          {{ Form::close()}}
        </div>
    </div>
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
                  html = '<option value="" disabled="true" selected="true">Selecciona...</option>';
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
                html = '<option value="" disabled="true" selected="true">No hay estados</option>';
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
                  html = '<option value="" disabled="true" selected="true">Selecciona...</option>';
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
                html = '<option value="" disabled="true" selected="true">No hay ciudades</option>';
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