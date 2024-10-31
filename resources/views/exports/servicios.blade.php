<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exportar Listado Servicios</title>
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
  <h2>MERCADO LAS ESTRELLAS - LISTA DE SERVICIOS</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nombre del servicio</th>
        <th>Costo unitario</th>
        <th>Tipo de Servicio</th>
        <th>Fecha Registro</th>
      </tr>
    </thead>
    <tbody>
      @foreach($servicios as $servicio)
        <tr>
          <td>{{ $servicio['id'] }}</td>
          <td>{{ $servicio['descripcion'] }}</td>
          <td>{{ $servicio['costo_unitario'] }}</td>
          <td>{{ $servicio['tipo_servicio'] }}</td>
          <td>{{ $servicio['fecha_registro'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>