<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>InformeOTCorrectivass</title>
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
            background: rgb(236, 236, 236);
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
            background: rgb(236, 236, 236);
        }

        .header {
            background: #083b7a;
            color: white;
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

        .bold {
            font-weight: bold;
        }

        .text-md {
            font-size: 10.5pt;
        }

        .break_page {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
        $array_meses = ['01' => 'enero', '02' => 'febrero', '03' => 'marzo', '04' => 'abril', '05' => 'mayo', '06' => 'junio', '07' => 'julio', '08' => 'agosto', '09' => 'septiembre', '10' => 'octubre', '11' => 'noviembre', '12' => 'diciembre'];
    @endphp
    <div class="encabezado">
        <div class="logo">
            <img src="{{ asset('imgs/' . App\Models\Configuracion::first()->logo) }}">
        </div>
        <h2 class="titulo">
            {{ App\Models\Configuracion::first()->razon_social }}
        </h2>
        <h4 class="texto">RESUMEN DE ORDENES DE TRABAJO</h4>
        <h4 class="fecha">Expedido: {{ date('Y-m-d') }}</h4>
    </div>
    <br>
    @foreach ($tipo_mantenimientos as $tm)
        <table border="1" style="margin-top:20px;">
            <thead>
                <tr>
                    <th colspan="4" class="header">{{ $tm }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($anios as $anio)
                    @foreach ($meses as $mes)
                        <tr>
                            <td class="bold mayuscula header centreado" colspan="2">{{ $array_meses[$mes] }}</td>
                            <td class="bold mayuscula header centreado" colspan="2">{{ $anio }}</td>
                        </tr>
                        <tr>
                            <td class="bold mayuscula gray centreado" colspan="2">REALIZADAS</td>
                            <td class="bold mayuscula gray centreado" colspan="2">PENDIENTES</td>
                        </tr>
                        @if ($registros[$tm][$anio][$mes]['filas'] > 0)
                            @for ($i = 0; $i < $registros[$tm][$anio][$mes]['filas']; $i++)
                                <tr>
                                    @if (isset($registros[$tm][$anio][$mes]['realizadas'][$i]))
                                        <td>#OT</td>
                                        <td>{{ $registros[$tm][$anio][$mes]['realizadas'][$i]['id'] }}</td>
                                    @else
                                        <td></td>
                                        <td></td>
                                    @endif
                                    @if (isset($registros[$tm][$anio][$mes]['pendientes'][$i]))
                                        <td>#OT</td>
                                        <td>{{ $registros[$tm][$anio][$mes]['pendientes'][$i]['id'] }}</td>
                                    @else
                                        <td></td>
                                        <td></td>
                                    @endif
                                </tr>
                            @endfor
                        @endif
                        <tr>
                            <td class="bold gray">TIEMPO ESTIMADO TOTAL</td>
                            <td class="bold gray">{{ $registros[$tm][$anio][$mes]['estimado_total_realizadas'] }}</td>
                            <td class="bold gray">TIEMPO ESTIMADO TOTAL</td>
                            <td class="bold gray">{{ $registros[$tm][$anio][$mes]['estimado_total_pendientes'] }}</td>
                        </tr>
                        <tr>
                            <td class="bold gray">TIEMPO EJECUTADO TOTAL</td>
                            <td class="bold gray">{{ $registros[$tm][$anio][$mes]['ejecutado_total_realizadas'] }}</td>
                            <td class="bold gray"></td>
                            <td class="bold gray"></td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endforeach

    @foreach ($anios as $anio)
        <table border="1" style="margin-top:30px;">
            <thead>
                <tr>
                    <th colspan="6">EVOLUCIÓN EN EL AÑO {{ $anio }}<br>NÚMERO TOTAL POR MES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="bold">MES</td>
                    @foreach ($tipo_mantenimientos as $key => $tm)
                        <td class="bold mayuscula centreado">{{ $key }}</td>
                    @endforeach
                </tr>
                @foreach ($meses as $mes)
                    <tr>
                        <td class="bold mayuscula">{{ $array_meses[$mes] }}</td>
                        @foreach ($tipo_mantenimientos as $tm)
                            <td class="centreado">{{ $registros_anio_mes[$anio][$mes][$tm]['total'] }}</td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <td class="centreado" colspan="6">
                        @foreach ($tipo_mantenimientos as $key => $tm)
                            *{{ $key }} {{ $tm }} &nbsp;&nbsp;
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td colspan="6" class="bold gray">HORAS EJECUTADAS TOTAL POR MES</td>
                </tr>
                <tr>
                    <td class="bold">MES</td>
                    @foreach ($tipo_mantenimientos as $key => $tm)
                        <td class="bold mayuscula centreado">{{ $key }}</td>
                    @endforeach
                </tr>
                @foreach ($meses as $mes)
                    <tr>
                        <td class="bold mayuscula">{{ $array_meses[$mes] }}</td>
                        @foreach ($tipo_mantenimientos as $tm)
                            <td class="centreado">{{ $registros_anio_mes[$anio][$mes][$tm]['total_horas'] }}</td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <td class="centreado" colspan="6">
                        @foreach ($tipo_mantenimientos as $key => $tm)
                            *{{ $key }} {{ $tm }} &nbsp;&nbsp;
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach

    <table border="1">
        <thead>
            <tr>
                <th class="centreado" colspan="7">HORAS PERSONAL</th>
            </tr>
            <tr>
                <th width="20%">PERSONAL</th>
                <th>HORAS EJECUTADAS</th>
                @foreach ($tipo_mantenimientos as $key => $tm)
                    <th class="bold centreado">{{ $key }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($personals as $personal)
                <tr>
                    <td>{{ $personal->razon_social }}</td>
                    <td class="centreado">{{ $registros_personal[$personal->id]['horas_ejecutadas'] }}</td>
                    @foreach ($tipo_mantenimientos as $tm)
                        <td class="centreado">{{ $registros_personal[$personal->id]["tipos"][$tm] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
