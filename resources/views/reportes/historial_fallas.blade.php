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

        .bold {
            font-weight: bold;
        }

        .text-md {
            font-size: 10.5pt;
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
        <h4 class="texto">HISTORIAL DE TIEMPO DE FALLAS</h4>
        <h4 class="fecha">Expedido: {{ date('Y-m-d') }}</h4>
    </div>
    <br>
    <table border="1">
        <thead>
            <tr>
                <th width="6%">N° OT</th>
                <th>Equipo</th>
                <th>Fecha</th>
                <th>Tipo Mantto</th>
                <th>Tiempo de falla</th>
            </tr>
        </thead>
        <tbody>
            @php
                $suma_anio_mes = [];
            @endphp
            @foreach ($historial_fallas as $hf)
                @php
                    $anio = date('Y', strtotime($hf->fecha_falla));
                    $mes = date('m', strtotime($hf->fecha_falla));
                    if (!isset($suma_anio_mes[$anio][$mes])) {
                        $suma_anio_mes[$anio][$mes] = (float) $hf->tiempo_falla;
                    } else {
                        $suma_anio_mes[$anio][$mes] += (float) $hf->tiempo_falla;
                    }
                @endphp
                <tr>
                    <td class="centreado">{{ $hf->orden_id }}</td>
                    <td>{{ $hf->equipo->nombre }}</td>
                    <td>{{ $hf->fecha_falla }}</td>
                    <td>{{ $hf->orden_trabajo->tipo_mantenimiento }}</td>
                    <td>{{ $hf->tiempo_falla }}</td>
                </tr>
            @endforeach
            @foreach ($suma_anio_mes as $anio => $meses)
                @foreach ($meses as $mes => $valor)
                    <tr>
                        <td colspan="4" class="mayuscula text-md bold">TIEMPO MUERTO MES {{ $array_meses[$mes] }}
                        </td>
                        <td class="text-md bold">{{ $valor }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>

</html>
