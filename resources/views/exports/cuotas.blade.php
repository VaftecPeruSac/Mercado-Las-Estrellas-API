<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exportar Listado Cuotas</title>
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
  <h2>MERCADO LAS ESTRELLAS - LISTA DE CUOTAS</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Fecha de emision</th>
        <th>Fecha de vencimiento</th>
        <th>Importe</th>
      </tr>
    </thead>
    <tbody>
      @foreach($cuotas as $cuota)
        <tr>
          <td>{{ $cuota['id'] }}</td>
          <td>{{ $cuota['fecha_registro'] }}</td>
          <td>{{ $cuota['fecha_vencimiento'] }}</td>
          <td>{{ $cuota['importe'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>