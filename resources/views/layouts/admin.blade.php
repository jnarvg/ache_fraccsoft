<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @laravelPWA
    <title>FRACCSOFT</title>
    <meta name="description" content="CRM inmobiliario">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="{{ asset('/grubsaico.png')}}" />

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{ asset('https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i')}}" rel="stylesheet">
    @yield('css') 
    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('css/admin-custom.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/bootstrap-select.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/datatables_cdn.css')}}"/>
    <link href="{{ asset('//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css') }}" rel="stylesheet">
    <style type="text/css">
      .oculto{
        display: none;
      }
    </style>
</head>

<body id="body">
  <!-- Left Panel -->
  <div id="wrapper">
    @if (Auth::check()) 
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion 
    " id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('welcome') }}">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fas fa-draw-polygon"></i>
        </div>
        <div class="sidebar-brand-text mx-3">FRACCSOFT</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('welcome') }}">
          <span>Dashboard</span></a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">
      @if (auth()->user()->rol == 5)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('plazos-externos') }}"> <span> Plazos de pago</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('documentos-externos') }}"> <span> Documentos</span></a>
        </li>
      @elseif(auth()->user()->rol == 6)
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOportunidad" aria-expanded="true" aria-controls="collapseOportunidad">
            <span>Prospectos</span>
          </a>
          <div id="collapseOportunidad" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('prospectos') }}">Todos </a>
              <a class="collapse-item" href="{{ route('v_pagando') }}">Cliente</a>
              <a class="collapse-item" href="{{ route('v_no_escriturado') }}">Por escriturar</a>
            </div>
          </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('plazos_pago') }}"> <span> Plazos de pago</span></a>
        </li>
      @else
        <!-- Nav Item - inmuebles -->  
        @if (auth()->user()->rol == 3)
          <li class="nav-item">
              <a class="nav-link" href="{{ route('proyectos') }}"><span> Proyectos</span></a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="{{ route('propiedades') }}"><span> Propiedades</span></a>
          </li>
        @endif
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOportunidad" aria-expanded="true" aria-controls="collapseOportunidad">
            <span>Prospectos</span>
          </a>
          <div id="collapseOportunidad" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('prospectos') }}">Todos </a>
              <a class="collapse-item" href="{{ route('v_prospecto') }}">Prospecto</a>
              <a class="collapse-item" href="{{ route('v_apartado') }}">Apartado</a>
              <a class="collapse-item" href="{{ route('v_contrato') }}">Contrato</a>
              <a class="collapse-item" href="{{ route('v_pagando') }}">Cliente</a>
              <a class="collapse-item" href="{{ route('v_no_escriturado') }}">Por escriturar</a>
              <a class="collapse-item" href="{{ route('v_escriturado') }}">Escriturado</a>
              <a class="collapse-item" href="{{ route('v_perdido') }}">Perdidos</a>
              <a class="collapse-item" href="{{ route('v_postergado') }}">Postergado</a>
            </div>
          </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('plano') }}"> <span> Plano</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cotizacion') }}"> <span> Cotizacion</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('actividad') }}"> <span> Actividades</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('calendario') }}"> <span> Calendario</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('plazos_pago') }}"> <span> Plazos de pago</span></a>
        </li>
        @if (auth()->user()->rol == 3)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('comision') }}"> <span>Comisiones</span></a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link" href="{{ route('documentos') }}"> <span> Documentos</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('contacto') }}"> <span> Contactos</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('reportes') }}"> <span> Reportes</span></a>
        </li>
        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCatalogos" aria-expanded="true" aria-controls="collapseCatalogos">
            <i class="fas fa-fw fa-cog"></i>
            <span>Catalogos</span>
          </a>
          <div id="collapseCatalogos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('empresa') }}">Empresas </a>
              <a class="collapse-item" href="{{ route('proyectos') }}">Proyectos </a>
              <a class="collapse-item" href="{{ route('grupo_esquema') }}">Grupo esquemas </a>
              <a class="collapse-item" href="{{ route('forma_pago') }}">Forma de pago</a>
              <a class="collapse-item" href="{{ route('motivo_perdida') }}">Motivo de perdida</a>
              <a class="collapse-item" href="{{ route('medio_contacto') }}">Medio de contacto</a>
              <a class="collapse-item" href="{{ route('tipo_modelo') }}">Tipo de propiedad</a>
              <a class="collapse-item" href="{{ route('tipo_operacion') }}">Tipo de operacion</a>
              <a class="collapse-item" href="{{ route('amenidades') }}">Amenidades</a>
              <a class="collapse-item" href="{{ route('requisito') }}">Requisitos</a>
              <a class="collapse-item" href="{{ route('uso-propiedad') }}">Uso de propiedad </a>
              <a class="collapse-item" href="{{ route('esquema_pago') }}">Esquema de pago </a>
              <a class="collapse-item" href="{{ route('condicion_entrega') }}">Condiciones entrega </a>
            </div>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseFijos" aria-expanded="true" aria-controls="collapseFijos">
            <i class="fas fa-fw fa-cog"></i>
            <span>Fijos</span>
          </a>
          <div id="collapseFijos" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('pais') }}">Pais </a>
              <a class="collapse-item" href="{{ route('estado') }}">Estado </a>
              <a class="collapse-item" href="{{ route('ciudades') }}">Ciudad</a>
              <a class="collapse-item" href="{{ route('regimen_fiscal') }}">Regimen fiscal</a>
              <a class="collapse-item" href="{{ route('banco') }}">Bancos </a>
              <a class="collapse-item" href="{{ route('moneda') }}">Moneda</a>
            </div>
          </div>
        </li>
        @if (auth()->user()->rol == 3)
        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseConfiguracion" aria-expanded="true" aria-controls="collapseConfiguracion">
            <i class="fas fa-fw fa-cog"></i>
            <span>Configuracion</span>
          </a>
          <div id="collapseConfiguracion" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('nivel') }}">Secciones </a>
              <a class="collapse-item" href="{{ route('usuarios') }}">Agentes y usuarios </a>
              <a class="collapse-item" href="{{ route('usuarios_externos') }}">Usuarios externos </a>
              <a class="collapse-item" href="{{ route('estatus_propiedad') }}">Estatus propiedad </a>
              <a class="collapse-item" href="{{ route('esquema') }}">Esquemas de comisiones</a>
              <a class="collapse-item" href="{{ route('color') }}">Colores</a>
            </div>
          </div>
        </li>
        @endif
        @if (auth()->user()->id == 1)
        <li class="nav-item">
          <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAdministrador" aria-expanded="true" aria-controls="collapseAdministrador">
            <i class="fas fa-fw fa-cog"></i>
            <span>Administrador</span>
          </a>
          <div id="collapseAdministrador" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
              <a class="collapse-item" href="{{ route('rol') }}">Roles </a>
              <a class="collapse-item" href="{{ route('tipo_propiedad') }}">Tipo de propiedad sistema </a>
              <a class="collapse-item" href="{{ route('estatus_prospecto') }}">Estatus prospecto</a>
              <a class="collapse-item" href="{{ route('configuracion_general') }}">Configuracion general</a>
              <a class="collapse-item" href="{{ route('campos_configuracion') }}">Configuracion campos</a>
              <a class="collapse-item" href="{{ route('condicion_entrega_detalle') }}">Condiciones entrega detalle</a>
              <a class="collapse-item" href="{{ route('detalle_esquema_pago') }}">Detalle esquema de pago </a>
            </div>
          </div>
        </li>
        @endif
      @endif
      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter" id="badge_actividades"></span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown" id="alertas_actividades">
                <h6 class="dropdown-header bg-dark">
                  Actividades pendientes
                </h6>
                <a class="dropdown-item text-center small text-gray-500" href="{{ route('actividad') }}">Show All Activitys</a>
              </div>
            </li>
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">@auth
                  {{ auth()->user()->name }}
                @endauth</span>
                @if (!empty(auth()->user()->foto_perfil))
                    <img class="img-profile rounded-circle" src="{{ asset(auth()->user()->foto_perfil) }}" alt="User">
                @else
                    <img class="img-profile rounded-circle" src="{{ asset('images/iconos/boss.png') }}" alt="User">
                @endif              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="{{ route('usuarios-profile',['id'=> auth()->id()]) }}">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Cerrar sesion</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="row justify-content-between mb-4" id="page-top">
            <div class="col-md-8">
              <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
            </div>
            <div class="col-md-4 h3 mb-0 " align="right">
              @yield('filter')
            </div>
          </div>

          <!-- Content Row -->
          @yield('content') 
          <!-- END Content Row -->
        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white" id="page-bottom">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; NEXTAPP <?php echo date('Y') ?></span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->
    @endif
  </div>
  <!-- End of Page Wrapper -->

  <!-- Right Panel -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
  </a>
  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
  <!-- Page level plugins -->
  <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
  <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/bootstrap-select.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/bootstrap-multiselect.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/bootstrap-notify.js') }}"></script>
  <script type="text/javascript" src="{{ asset('js/notification.js') }}"></script>
  <!-- Custom scripts for all pages-->
  <script src="{{ asset('js/sb-admin-2.min.js')}}"></script>
  <script src="{{ asset('js/jquery.rwdImageMaps.min.js')}}"></script>
  <script src="{{ asset('vendor/chart.js/Chart.min.js')}}"></script>
  <script src="{{ asset('js/maphilight-master/jquery.maphilight.js')}}"></script>
  <script type='text/javascript' src="{{ asset('https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js') }}"></script>
  <script type="text/javascript" src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js') }}"></script>

  <script>
    @if(Session::has('msj'))
      var type="{{Session::get('alert-type','info')}}";
      toastr.options.progressBar = true;
      switch(type){
        case 'info':
           toastr.info("{{ Session::get('msj') }}");
           break;
        case 'success':
            toastr.success("{{ Session::get('msj') }}");
            break;
        case 'warning':
            toastr.warning("{{ Session::get('msj') }}");
            break;
        case 'error':
          toastr.error("{{ Session::get('msj') }}");
          break;
        case '':
          toastr.info("{{ Session::get('msj') }}");
          break;
      }
    @endif
  </script>

  <script>
      function sizeWindow(){
          var widthW =  $(window).width();
          var tablet = 992;
          var movil = 768;
          if(widthW < tablet && widthW > movil){
          $('#accordionSidebar').removeClass('toggled');
          
          }else if(widthW > tablet){
          $('#accordionSidebar').removeClass('toggled');
          }else if(widthW < movil){
          $('#accordionSidebar').addClass('toggled');
          }
      }

      $(document).ready(function() {
          sizeWindow()
      }); 

      $(window).resize(function() {
          sizeWindow()
      }); 
  </script>
  <script>
    jQuery(document).ready(function($)
    {
      //enmacrar los input tio text con la clase mask
      $(".mask").inputmask({ 'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0.00'}); 

      $('img[usemap]').rwdImageMaps();
      $('#slippry-slider').slippry(
        defaults = {
          transition: 'vertical',
          useCSS: true,
          speed: 5000,
          pause: 3000,
          initSingle: false,
          auto: true,
          preload: 'visible',
          pager: false,
        }
      )
    });
  </script>
  <script>
    $(function() {
        $.fn.maphilight.defaults = {
            fill: true,
            fillColor: '71FF33',
            fillOpacity: 0.2,
            stroke: true,
            strokeColor: '7D1612',
            strokeOpacity: 0.5,
            strokeWidth: 1,
            fade: true,
            alwaysOn: false,
            neverOn: false,
            groupBy: false,
            wrapClass: true,
            shadow: false,
            shadowX: 0,
            shadowY: 0,
            shadowRadius: 6,
            shadowColor: '71FF33',
            shadowOpacity: 0.8,
            shadowPosition: 'outside',
            shadowFrom: false
        }
        $('.map').maphilight({
      
        });
       
    });
  </script>
  <script type="text/javascript">
    jQuery(document).ready(function($)
    {
      ////TraeS MENSAJE SY ACTIVIDADES
      div_actividades = document.getElementById('alertas_actividades');
      badge_actividades = document.getElementById('badge_actividades');
        $.ajax({

        type: "GET",
        url: "/actividades-hoy",
        success: function(data) {
              var htmlOptions = '';
              var total = String(data.length);

              htmlOptions = htmlOptions + '<h6 class="dropdown-header bg-dark">Actividades pendientes</h6>';
              if( data.length ){
                  for( item in data ) {
                    //en caso de ser un select
                    if(data[item].tipo_actividad == 'Llamada'){
                      fondo = 'bg-primary';
                      icono = '<i class="fas fa-phone text-white"></i>';
                    }else if(data[item].tipo_actividad == 'Correo'){
                      fondo = 'bg-danger';
                      icono = '<i class="fas fa-envelope text-white"></i>';
                    }else if(data[item].tipo_actividad == 'Cita'){
                      fondo = 'bg-info';
                      icono = '<i class="fas fa-calendar-day text-white"></i>'
                    }else if(data[item].tipo_actividad == 'Tarea'){
                      fondo = 'bg-warning';
                      icono = '<i class="fas fa-pencil-ruler text-white"></i>';
                    }else{
                      fondo = 'bg-gray-500';
                      icono = '<i class="fas fa-exclamation-triangle text-white"></i>';
                    }
                    html = '<a class="dropdown-item d-flex align-items-center" href="/actividad/show/'+data[item].id_actividad +'/welcome"><div class="mr-3"><div class="icon-circle '+fondo+'">'+icono+'</div></div><div><div class="small text-gray-500">'+data[item].fecha+' '+data[item].hora+'</div><span class="font-weight-bold">'+data[item].titulo+'!</span></div></a>';
                    htmlOptions = htmlOptions + html;
                  }
                  htmlOptions = htmlOptions + '<a class="dropdown-item text-center small text-gray-500" href="/actividad">Show All Activitys</a>';
                  // se agregan las opciones del catalogo en caso de ser un select 
                  div_actividades.innerHTML = htmlOptions;
                  div_actividades.innerHTML = htmlOptions;
                  if(data.length > 5){
                    badge_actividades.innerHTML = '5+'; 
                  }else{
                    badge_actividades.innerHTML = total; 
                  }
              }else{
                /// Mostrar que no actividades para hoy
                html = '<a class="dropdown-item d-flex align-items-center" href="#"><div class="mr-3"><div class="icon-circle bg-success"><i class="fas fa-laugh-beam text-white"></i></div></div><div><div class="small text-gray-500">No hay actividades</div><span class="font-weight-bold">No hay actividades para hoy, revisa las de mañana!</span></div></a>';
                htmlOptions = htmlOptions + html;
                div_actividades.innerHTML = htmlOptions;              
              }
        },
          error: function(error) {
          alert("No se pudieron cargar las actividades");
        }
        });

    });
  </script>
  @yield('scripts')
  @stack('scripts')
</body>

</html>
