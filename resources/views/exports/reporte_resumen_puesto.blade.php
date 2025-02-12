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
        <th>Nro. Pago</th>
        <th>Imp. Ingreso</th>
        <th>Imp. Gastos Administrativos</th>
        <th>Imp. Multas Inasistencia</th>
        <th>Imp. Pagos transferencia</th>
        <th>Imp. Cuotas Extraordinarias</th>
        <th>Imp. Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pagos as $pago)
        <tr>
          <td class="right">{{ $pago['numero_pago'] }}</td>
          <td class="right">{{ $pago['importe_ingreso'] }}</td>
          <td class="right">{{ $pago['importe_gastos_administrativo'] }}</td>
          <td class="right">{{ $pago['importe_multas_inasistencia'] }}</td>
          <td class="right">{{ $pago['importe_pagos_transferencia'] }}</td>
          <td class="right">{{ $pago['importe_cuotas_extraordinarias'] }}</td>
          <td class="right">{{ $pago['importe_total'] }}</td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <th>Total(S/.)</th>
        <th class="right">{{ $total_importe_ingreso }}</th>
        <th class="right">{{ $total_importe_gastos_administrativo }}</th>
        <th class="right">{{ $total_importe_multas_inasistencia }}</th>
        <th class="right">{{ $total_importe_pagos_transferencia }}</th>
        <th class="right">{{ $total_importe_cuotas_extraordinarias }}</th>
        <th class="right">{{ $total_importe_total }}</th>
      </tr>
    </tfoot>
  </table>
</body>
</html>