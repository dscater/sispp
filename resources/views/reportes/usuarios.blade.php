<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 2cm;
            margin-bottom: 1cm;
            margin-left: 1.5cm;
            margin-right: 1cm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 20px;
            page-break-before: avoid;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            font-size: 10pt;
            word-wrap: break-word;
        }

        table thead tr th {
            font-size: 9pt;
        }

        table tbody tr td {
            font-size: 8pt;
        }


        .encabezado {
            width: 100%;
        }

        .logo img {
            position: absolute;
            width: 200px;
            height: 90px;
            top: -20px;
            left: -20px;
        }

        h2.titulo {
            width: 450px;
            margin: auto;
            margin-top: 15px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14pt;
        }

        .texto {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .fecha {
            width: 250px;
            text-align: center;
            margin: auto;
            margin-top: 15px;
            font-weight: normal;
            font-size: 0.85em;
        }

        .total {
            text-align: right;
            padding-right: 15px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        table thead {
            background: rgb(236, 236, 236)
        }

        tr {
            page-break-inside: avoid !important;
        }

        .centreado {
            padding-left: 0px;
            text-align: center;
        }

        .datos {
            margin-left: 15px;
            border-top: solid 1px;
            border-collapse: collapse;
            width: 250px;
        }

        .txt {
            font-weight: bold;
            text-align: right;
            padding-right: 5px;
        }

        .txt_center {
            font-weight: bold;
            text-align: center;
        }

        .cumplimiento {
            position: absolute;
            width: 150px;
            right: 0px;
            top: 86px;
        }

        .p_cump {
            color: red;
            font-size: 1.2em;
        }

        .b_top {
            border-top: solid 1px black;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .green {
            background: #083b7a;
            color: white;
        }

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    <div class="encabezado">
        <div class="logo">
            <img src="{{ asset('imgs/' . $configuracion->first()->logo) }}">
            {{ $configuracion->first()->logo }}
        </div>
        <h2 class="titulo">
            {{ $configuracion->first()->razon_social }}
        </h2>
        <h4 class="texto">LISTA DE USUARIOS</h4>
        <h4 class="fecha">Expedido: {{ date('d-m-Y') }}</h4>
    </div>
    <table border="1">
        <thead class="green">
            <tr>
                <th width="3%">N°</th>
                <th width="5%">FOTO</th>
                <th>USUARIO</th>
                <th>PATERNO</th>
                <th>MATERNO</th>
                <th>NOMBRE(S)</th>
                <th>C.I.</th>
                <th>DIRECCIÓN</th>
                <th>CORREO</th>
                <th>TELEFÓNO(S)</th>
                <th>TIPO DE USUARIO</th>
                <th width="9%">FECHA DE REGISTRO</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cont = 1;
            @endphp
            @foreach ($usuarios as $user)
                <tr>
                    <td class="centreado">{{ $cont++ }}</td>
                    <td class="img_celda"><img src="{{ asset('imgs/users/' . $user->foto) }}" alt="Foto"></td>
                    <td>{{ $user->usuario }}</td>
                    <td class="centreado">{{ $user->paterno }}</td>
                    <td class="centreado">{{ $user->materno }}</td>
                    <td class="centreado">{{ $user->nombre }}</td>
                    <td class="centreado">{{ $user->full_ci }}</td>
                    <td class="centreado">{{ $user->dir }}</td>
                    <td class="centreado">{{ $user->correo }}</td>
                    <td class="centreado">{{ $user->fono }}</td>
                    <td class="centreado">{{ $user->tipo }}</td>
                    <td class="centreado">{{ $user->fecha_registro }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
