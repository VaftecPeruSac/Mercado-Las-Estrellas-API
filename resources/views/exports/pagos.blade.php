<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exportar Listado Pagos</title>
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
  <h2>MERCADO LAS ESTRELLAS - LISTA DE PAGOS</h2>
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Nro. Puesto</th>
        <th>Socio</th>
        <th>DNI</th>
        <th>Fec. Pago</th>
        <th>Telefono</th>
        <th>Correo</th>
        <th>A cuenta</th>
        <th>Monto total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pagos as $pago)
        <tr>
          <td>{{ $pago['id'] }}</td>
          <td>{{ $pago['numero_puesto'] }}</td>
          <td>{{ $pago['socio'] }}</td>
          <td>{{ $pago['dni'] }}</td>
          <td>{{ $pago['fecha_registro'] }}</td>
          <td>{{ $pago['telefono'] }}</td>
          <td>{{ $pago['correo'] }}</td>
          <td>{{ $pago['a_cuenta'] }}</td>
          <td>{{ $pago['monto_total'] }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>