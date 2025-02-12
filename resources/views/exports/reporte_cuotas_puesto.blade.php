<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exportar Reporte de Cuotas por Puesto</title>
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
  <h2>MERCADO LAS ESTRELLAS - REPORTE DE CUOTAS POR PUESTO</h2>
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
        <th>ID Cuota</th>
        <th>Año</th>
        <th>Servicio</th>
        <th>Total(S/.)</th>
        <th>Imo. Pagado(S/.)</th>
        <th>Imp. Por pagar(S/.)</th>
        <th>Fecha de registro</th>
      </tr>
    </thead>
    <tbody>
      @foreach($deudas as $deuda)
        <tr>
          <td>{{ $deuda['id_cuota'] }}</td>
          <td>{{ $deuda['anio'] }}</td>
          <td>{{ $deuda['servicio_descripcion'] }}</td>
          <td class="right">{{ $deuda['total_deuda'] }}</td>
          <td class="right">{{ $deuda['importe_pagado'] }}</td>
          <td class="right">{{ $deuda['importe_por_pagar'] }}</td>
          <td>{{ $deuda['fecha_registro'] }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th colspan="3">Total(S/.)</th>
        <th class="right">{{ $total }}</th>
        <th class="right">{{ $total_importe_pagado }}</th>
        <th class="right">{{ $total_importe_por_pagar }}</th>
        <th></th>
      </tr>
    </tfoot>
  </table>
</body>
</html>