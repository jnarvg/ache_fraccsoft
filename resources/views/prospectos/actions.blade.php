<a href="{{URL::action('ProspectosController@show', [ $id_prospecto, 'prospectos' ])}}" ><button class="btn-ico" ><i class="fas fa-pencil-alt"></i></button></a>
<a href="#" data-target="#modal-delete{{$id_prospecto}}" data-toggle="modal" ><button class="btn-ico" ><i class="fas fa-trash-alt"></i></button></a>
@include('prospectos.modal')