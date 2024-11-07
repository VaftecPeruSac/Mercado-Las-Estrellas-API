<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exportar Reporte de Cuotas por Metrado</title>
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
  <h2>MERCADO LAS ESTRELLAS - REPORTE DE CUOTAS POR METRADO</h2>
  <table>
      <thead>
          <th>Fecha de emisi贸n</th>
          <th>Fecha de vencimiento</th>
      </thead>
      <tbody>
          <td>{{ $fecha_emision }}</td>
          <td>{{ $fecha_vencimiento }}</td>
      </tbody>
  </table>
  <table>
    <thead>
      <tr>
        <th>ID Cuota</th>
        <th>Nombre Completo</th>
        <th>Número de Puesto</th>
        <th>Área</th>
        <th>Total</th>
        <th>Importe Pagado</th>
        <th>Fecha de Registro</th>
      </tr>
    </thead>
    <tbody>
      @foreach($deudas as $deuda)
        <tr>
          <td>{{ $deuda['id_cuota'] }}</td>
          <td>{{ $deuda['nombre_completo'] }}</td>
          <td>{{ $deuda['numero_puesto'] }}</td>
          <td>{{ $deuda['area'] }}</td>
          <td>{{ $deuda['total'] }}</td>
          <td>{{ $deuda['importe_pagado'] }}</td>
          <td>{{ $deuda['fecha_registro'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>