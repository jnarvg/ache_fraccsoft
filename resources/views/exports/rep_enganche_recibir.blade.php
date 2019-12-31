<table class="table">
    <thead class="">
    <tr>
      <th></th>
      <th class="center">Total estimado</th>
      <th class="center">Recibido</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>Resumen</td>
      <td class="center">$ {{ number_format($total_enganche , 2 , "." , ",") }}</td>
      <td class="center">$ {{ number_format($total_pagado , 2 , "." , ",") }}</td>
    </tr>
    </tbody>
</table>
<table id="tblData">
    <thead class="">
        <tr>
          <th>Prospecto</th>
          <th class="center">Enganche estimado</th>
          <th class="center">Recibido</th>
          <th class="center">Estatus</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resultados as $ele)
        <tr>
          <td>{{ $ele->prospecto}}</td>
          <td class="center">$ {{ number_format($ele->total , 2 , "." , ",") }}</td>
          <td class="center">$ {{ number_format($ele->pagado , 2 , "." , ",") }}</td>
          <td class="center">{{ $ele->estatus}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
          <td style="text-align: right;">Total </td>
          <td class="center">$ {{ number_format($total_enganche , 2 , "." , ",") }}</td>
          <td class="center">$ {{ number_format($total_pagado , 2 , "." , ",") }}</td>
        </tr>
    </tfoot>
</table>