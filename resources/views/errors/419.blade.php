@extends('errors::layout')
 
@section('title', 'Se terminó la sesion')

@section('message')
<style type="text/css">
    @import url('https://fonts.googleapis.com/css?family=Major+Mono+Display|Courgette');

:root {
  --dark-blue: #1c0c33;
  --light-blue: #F8F3FE;
  --hot-pink: #fa243c;
  --fade-pink: #FB4D61;
  --dark-pink: #DC001A;
}

a.flat-btn {
  text-decoration: none;
  font-size: 1.5rem;
  font-family: 'Courgette';
  font-weight: bold;
  letter-spacing: 1px;
  color: #fff;
  background-color: var(--hot-pink);
  padding: 15px 50px;
  border-radius: 5px;
  box-shadow: 0 5px 0 0 var(--dark-pink);
}


a.flat-btn:hover {
  background-color: var(--fade-pink);
}

a.flat-btn:active {
  transform: translateY(5px);
  box-shadow: none;
}
</style>
<div class="col-md-12">
    <h3>Lo sentimos, la sesion se agotó.</h3>
    <p>Prueba utilizado este botón</p>
    <a class="flat-btn" href="{{ route('welcome') }}">Log in</a>
</div>
@endsection 