<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exportar Reporte de Pagos</title>
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
  </style>
</head>
<body>
  <h2>MERCADO LAS ESTRELLAS - REPORTE DE PAGOS</h2>
  <table>
    <tbody>
      <td><strong>Nombre del socio</strong></td>
      <td> {{ $nombre_socio }}</td>
    </tbody>
  </table>
  <table>
    <thead>
      <tr>
        <th>Nro. Pago</th>
        <th>Nro. Serie</th>
        <th>Fecha de Pago</th>
        <th>Aporte(S/.)</th>
        <th>Total(S/.)</th>
        <th>Detalle del Pago</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pagos as $pago)
        <tr>
          <td>{{ $pago['numero'] }}</td>
          <td>{{ $pago['serie_numero'] }}</td>
          <td>{{ $pago['fecha'] }}</td>
          <td>{{ $pago['aporte'] }}</td>
          <td>{{ $pago['total'] }}</td>
          <td>{{ $pago['detalle_pagos'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>