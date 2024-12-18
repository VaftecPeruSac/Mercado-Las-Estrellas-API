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
        <th>Teléfono</th>
        <th>Correo</th>
        <th>Block</th>
        <th>Giro</th>
        <th>Puesto</th>
        <th>Inquilino</th>
        <th>Fecha Registro</th>
      </tr>
    </thead>
    <tbody>
      @foreach($socios as $socio)
      <tr>
        <td rowspan="{{ count($socio['puestos']) }}">{{ $socio['nombre'] }}</td>
        <td rowspan="{{ count($socio['puestos']) }}">{{ $socio['dni'] }}</td>
        <td rowspan="{{ count($socio['puestos']) }}">{{ $socio['telefono'] }}</td>
        <td rowspan="{{ count($socio['puestos']) }}">{{ $socio['correo'] }}</td>
        <td>{{ $socio['puestos'][0]['block'] }}</td>
        <td>{{ $socio['puestos'][0]['giro'] }}</td>
        <td>{{ $socio['puestos'][0]['numero'] }}</td>
        <td>{{ $socio['puestos'][0]['inquilino'] }}</td>
        <td rowspan="{{ count($socio['puestos']) }}">{{ $socio['fecha_registro'] }}</td>
      </tr>
      @for($i = 1; $i < count($socio['puestos']); $i++)
      <tr>
        <td>{{ $socio['puestos'][0]['block'] }}</td>
        <td>{{ $socio['puestos'][0]['giro'] }}</td>
        <td>{{ $socio['puestos'][0]['numero'] }}</td>
        <td>{{ $socio['puestos'][0]['inquilino'] }}</td>
      </tr>
      @endfor
      @endforeach
    </tbody>
  </table>
</body>

</html>