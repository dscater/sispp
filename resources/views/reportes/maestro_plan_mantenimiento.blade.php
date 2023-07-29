<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>MaestroPlanMantenimiento</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 2cm;
            margin-bottom: 1cm;
            margin-left: 1.5cm;
            margin-right: 1cm;
            border: 5px solid blue;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-top: 0px;
            page-break-before: avoid;
        }

        table thead {
            page-break-before: always;
        }

        table thead tr th,
        tbody tr td {
            font-size: 0.63em;
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

        table thead tr th {
            padding: 3px;
            font-size: 0.7em;
        }

        table tbody tr td {
            padding: 3px;
            font-size: 0.7em;
            word-wrap: break-word;
        }

        tr {
            page-break-inside: avoid !important;
        }

        table tbody tr td.franco {
            background: red;
            color: white;
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

        .txt_rojo {}

        .img_celda img {
            width: 45px;
        }

        .repuesto {
            margin-bottom: -2px;
        }

        .repuesto tbody tr td {
            font-size: 0.8em;
            font-weight: bold;
            background: rgb(228, 228, 228);
        }

        .mayuscula {
            text-transform: uppercase;
        }

        .registros {
            padding: 4px;
        }

        .registros div {
            display: block;
            text-align: center;
            width: 100%;
            margin-top: 1px;
        }

        .registros div.programado {
            background: green;
            color: white;
        }

        .registros div.cancelado,
        .registros div.pendiente_cancelado {
            background: brown;
            color: white;
        }

        .registros div.iniciado {
            background: cyan;
            color: black;
        }

        .registros div.pendiente {
            background: red;
            color: white;
        }

        .registros div.terminado,
        .registros div.pendiente_terminado {
            background: blue;
            color: white;
        }
    </style>
</head>

<body>
    @php
        $array_dias = ['0' => 'Domingo', '1' => 'Lunes', '2' => 'Martes', '3' => 'Miércoles', '4' => 'Jueves', '5' => 'Viernes', '6' => 'Sábado'];
        $array_meses = ['01' => 'enero', '02' => 'febrero', '03' => 'marzo', '04' => 'abril', '05' => 'mayo', '06' => 'junio', '07' => 'julio', '08' => 'agosto', '09' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'];
    @endphp
    <div class="encabezado">
        <div class="logo">
            <img src="{{ asset('imgs/' . App\Models\Configuracion::first()->logo) }}">
        </div>
        <h2 class="titulo">
            {{ App\Models\Configuracion::first()->razon_social }}
        </h2>
        <h4 class="texto">MAESTRO PLAN DE MANTENIMIENTO</h4>
        <h4 class="fecha">Expedido: {{ date('Y-m-d') }}</h4>
    </div>
    <br>
    @foreach ($anios as $anio)
        @foreach ($meses as $mes)
            <table border="1">
                <thead>
                    <tr>
                        <th class="mayuscula" colspan="{{ count($index_dias_mes[$anio][$mes]) }}">
                            {{ $array_meses[$mes] }} -
                            {{ $anio }}</th>
                    </tr>
                    <tr>
                        @foreach ($index_dias_mes[$anio][$mes] as $dm)
                            <th>{{ $dm }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        @foreach ($index_dias_mes[$anio][$mes] as $dm)
                            <td class="registros">
                                @foreach ($registros[$anio][$mes][$dm] as $r)
                                    <div class="{{ str_replace(' ', '_', mb_strtolower($r['estado'])) }}">
                                        {{ $r['id'] }}</div>
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endforeach
</body>

</html>
