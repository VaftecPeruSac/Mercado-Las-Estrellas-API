<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exportar Reporte de Deudas</title>
  <style>
    *{
      font-family: Arial, Helvetica, sans-serif;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      font-size: 12px;
      border: 1px solid #dee2e6;
      padding: 8px;
    }

    th {
      background-color: #f8f9fa;
      font-weight: bold;
      text-align: center;
    }

    h2 {
      text-align: center;
      margin-top: 20px;
    }

    .right { text-align: right; }
  </style>
</head>
<body>
  <h2>MERCADO LAS ESTRELLAS - REPORTE DE DEUDAS</h2>
  <table>
    <thead>
      <tr>
        <th>Nombre del socio</th>
        <th>Bloque</th>
        <th>Nro. Puesto</th>
        <th>Area</th>
        <th>Giro de negocio</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $nombre_socio }}</td>
        <td>{{ $nombre_bloque }}</td>
        <td>{{ $numero_puesto }}</td>
        <td>{{ $area }}</td>
        <td>{{ $giro_negocio }}</td>
      </tr>
  </table>
  <table>
    <thead>
      <tr>
        <th>Año</th>
        <!-- <th>Mes</th> -->
        <th>Servicio</th>
        <!-- <th>Total(S/.)</th> -->
        <th>Imp. Pagado(S/.)</th>
        <!-- <th>Imp. Pagado(S/.)</th> -->
        <th>Imp. Por pagar(S/.)</th>
        <!-- <th>Imp. Por pagar(S/.)</th> -->
      </tr>
    </thead>
    <tbody>
      @foreach($deudas as $deuda)
        <tr>
          <td>{{ $deuda['anio'] }}</td>
          <!-- <td>{{ $deuda['mes'] }}</td> -->
          <td>{{ $deuda['servicio_descripcion'] }}</td>
          <td class="right">{{ $deuda['total'] }}</td>
          <td class="right">{{ $deuda['importe_pagado'] }}</td>
          <!-- <td class="right">{{ $deuda['importe_por_pagar'] }}</td> -->
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th colspan="2">Total(S/.)</th>
        <th class="right">{{ $total }}</th>
        <th class="right">{{ $total }}</th>
        <!-- <th class="right">{{ $total }}</th> -->
      </tr>
    </tfoot>
  </table>
</body>
</html>