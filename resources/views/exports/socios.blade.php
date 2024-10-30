<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Exportar Listado Socios</title>
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
  <h2>MERCADO LAS ESTRELLAS - LISTA DE SOCIOS</h2>
  <table>
    <thead>
      <tr>
        <th>Nombre Completo</th>
        <th>DNI</th>
        <th>Block</th>
        <th>Puesto</th>
        <th>Giro Negocio</th>
        <th>Telefono</th>
        <th>Correo</th>
        <th>Inquilino</th>
        <th>Fecha Registro</th>
      </tr>
    </thead>
    <tbody>
      @foreach($socios as $socio)
      <tr>
        <td>{{ $socio['nombre_usuario'] }}</td>
        <td>{{ $socio['dni'] }}</td>
        <td>{{ $socio['bloque'] }}</td>
        <td>{{ $socio['puesto'] }}</td>
        <td>{{ $socio['giro'] }}</td>
        <td>{{ $socio['telefono'] }}</td>
        <td>{{ $socio['correo'] }}</td>
        <td>{{ $socio['inquilino'] }}</td>
        <td>{{ $socio['fecha_registro'] }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>