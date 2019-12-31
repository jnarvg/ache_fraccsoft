@extends('layouts.admin')
@section('title')
Semaforo
@endsection
@section('content')
<div class="content mt-3">
  
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
      {!! Form::open(array('route'=>'semaforo', 'method'=>'get', 'autocomplete'=>'off')) !!}
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label for="proyecto_bs">Proyecto</label>
              <select class="form-control"  name="proyecto_bs" id="proyecto_bs">
                <option value="Vacio">Selecciona</option>
                @foreach ($proyectos as $p)
                  @if ($p->id_proyecto == $request->proyecto_bs)
                    <option selected="true" value="{{ $p->id_proyecto }}">{{ $p->nombre }}</option>
                  @else
                    <option value="{{ $p->id_proyecto }}">{{ $p->nombre }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label for="propiedad_bs">Propiedad</label>
              <select class="form-control selected"  name="propiedad_bs" id="propiedad_bs">
                <option value="Vacio">Selecciona</option>
                @foreach ($propiedades as $p)
                  @if ($p->id_propiedad == $request->propiedad_bs)
                    <option selected="true" value="{{ $p->id_propiedad }}">{{ $p->nombre }}</option>
                  @else
                    <option value="{{ $p->id_propiedad }}">{{ $p->nombre }}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-6 offset-md-3">
          <div class="form-group">
            <button type="submit" class="btn btn-info btn-block" ><span class="glyphicon"><i class="fas fa-search"></i></span></button>
          </div>
        </div>
        {!! Form::close() !!}
      </div>
      <div class="row">
        
        <div class="col-md-1 offset-md-10">
          <div class="form-group">
            {!! Form::open(array('route'=>'semaforo_pdf', 'method'=>'get', 'autocomplete'=>'off')) !!}
            <input type="hidden" value="{{ $request->proyecto_bs }}" name="proyecto_pdf" id="proyecto_pdf">
            <input type="hidden" value="{{ $request->propiedad_bs }}" name="propiedad_pdf" id="propiedad_pdf">
            <button class="btn btn-block btn-danger"><i class="fas fa-file-pdf"></i></button>
            {!! Form::close() !!}
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-group">
            <button class="btn  btn-block btn-success" onclick="exportTableToExcel('dataExport', 'semaforo_tbi')"><i class="fas fa-file-excel"></i></button>
          </div>
        </div>
      </div>
      <div class="table-responsive ">
        <table class="table table-hover table-withoutborder" id="dataExport">
          <thead class="thead-grubsa ">
            <th class="center">
              Proyecto
            </th>
            <th class="center">
              Propiedad
            </th>
            <th class="center">
              Cliente
            </th>
            <th class="center">
              Pagado
            </th>
            <th class="center">
              Saldo
            </th>
          </thead>
          <tbody>
            @foreach ($resultados as $r)
            <tr>
              <td class="center">
                {{ $r->proyecto }}
              </td>
              <td class="center">
                {{ $r->propiedad }}
              </td>
              <td class="center">
                {{ $r->cliente }}
              </td>
              <td class="center">
                ${{ number_format($r->pagado , 2 , "." , ",") }}
              </td>
              @if ($r->saldo <= 0.50)
              <td class="center" style="background-color: #82F787; color: #fff;">
                ${{ number_format($r->saldo , 2 , "." , ",") }}
              </td>
              @else
              <td class="center" style="background-color: #F78B82; color: #fff;">
                ${{ number_format($r->saldo , 2 , "." , ",") }}
              </td>
              @endif  
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th class="center">
              </th>
              <th class="center">
              </th>
              <th class="center">
              </th>
              <th class="center">
               ${{ number_format($sumapagado , 2 , "." , ",") }}
              </th>
              <th class="center">
               ${{ number_format($sumasaldo , 2 , "." , ",") }}
              </th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $("#proyecto_bs").on('change', function(){
    desarrollo = $('#proyecto_bs').val();
    selectPropiedad = $('#propiedad_bs');
    selectPropiedad.empty().append('<option>Cargando las propiedades</option>');

    $.ajax({

    type: "GET",
    url: "/catalogo-propiedades-desarrollo-estatus/" + desarrollo,
    success: function(data) {
          var htmlOptions = [];
          if( data.length ){
              html = '<option value="Vacio"  selected="true">Selecciona una propiedad</option>';
              htmlOptions[htmlOptions.length] = html;
              for( item in data ) {
                //en caso de ser un select
                html = '<option value="' + data[item].id_propiedad + '">' + data[item].nombre + '</option>';
                htmlOptions[htmlOptions.length] = html;
              }

              //en caso de ser un input
              //$("#precio_propiedadctz").val(html);
              
              // se agregan las opciones del catalogo en caso de ser un select 
              selectPropiedad.empty().append( htmlOptions.join('') );
          }else{
            html = '<option value="" disabled="true" selected="true">No hay propiedades</option>';
            htmlOptions[htmlOptions.length] = html;
            selectPropiedad.empty().append( htmlOptions.join('') );
          }
    },
      error: function(error) {
      alert("No se pudo cargar el catalogo de propiedades");
    }
    })
  });
  $("#proyecto_bs").on('change', function(){
    variable = $("#proyecto_bs").val();
    $('#proyecto_pdf').val(variable);
  });
  $("#fecha_inicio").on('change', function(){
    variable = $("#fecha_inicio").val();
    $('#fecha_inicio_pdf').val(variable);
  });
  $("#fecha_final").on('change', function(){
    variable = $("#fecha_final").val();
    $('#fecha_final_pdf').val(variable);
  });
  $("#propiedad_bs").on('change', function(){
    variable = $("#propiedad_bs").val();
    $('#propiedad_pdf').val(variable);
  });
  function exportTableToExcel(tableID, filename = ''){
      var downloadLink;
      var dataType = 'application/vnd.ms-excel';
      var tableSelect = document.getElementById(tableID);
      var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
      
      // Specify file name
      filename = filename?filename+'.xls':'excel_data.xls';
      
      // Create download link element
      downloadLink = document.createElement("a");
      
      document.body.appendChild(downloadLink);
      
      if(navigator.msSaveOrOpenBlob){
          var blob = new Blob(['ufeff', tableHTML], {
              type: dataType
          });
          navigator.msSaveOrOpenBlob( blob, filename);
      }else{
          // Create a link to the file
          downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
      
          // Setting the file name
          downloadLink.download = filename;
          
          //triggering the function
          downloadLink.click();
      }
  }
</script>
@endpush 
@endsection