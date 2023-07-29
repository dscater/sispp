<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>SeguimientoCostos</title>
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

        .break_page {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
        $cont = 0;
    @endphp
    @foreach ($orden_trabajos as $ot)
        <div class="encabezado">
            <div class="logo">
                <img src="{{ asset('imgs/' . App\Models\Configuracion::first()->logo) }}">
            </div>
            <h2 class="titulo">
                {{ App\Models\Configuracion::first()->razon_social }}
            </h2>
            <h4 class="texto">SEGUIMIENTO DE COSTOS</h4>
            <h4 class="fecha">Expedido: {{ date('Y-m-d') }}</h4>
        </div>
        <br>
        <table>
            <tbody>
                <tr>
                    <td class="bold text-md" width="12%">Nro. OT:</td>
                    <td class="text-md">{{ $ot->id }}</td>
                </tr>
            </tbody>
        </table>
        <table border="1">
            <thead>
                <tr>
                    <th colspan="3">COSTO DE PERSONAL</th>
                </tr>
                <tr>
                    <th width="6%">N°</th>
                    <th>Personal</th>
                    <th width="17%">Costo/Hora</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $suma_costo = 0;
                @endphp
                @if ($ot->orden_generada)
                    @if (count($ot->orden_generada->detalle_personals) > 0)
                        @php
                            $numero = 1;
                        @endphp
                        @foreach ($ot->orden_generada->detalle_personals as $dp)
                            <tr>
                                <td>{{ $numero++ }}</td>
                                <td>{{ $dp->personal->razon_social }}</td>
                                <td>{{ $dp->costo }}</td>
                            </tr>
                            @php
                                $suma_costo += (float) $dp->costo;
                            @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="centreado">ESTA ORDEN NO CUENTA CON PERSONAL ASIGNADO</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="3" class="centreado">ESTA ORDEN NO SE ENCUENTRA GENERADA</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="2" class="bold text-md derecha">TOTAL</td>
                    <td class="bold text-md">{{ number_format($suma_costo, 2, '.', '') }}</td>
                </tr>
            </tbody>
        </table>
        <table border="1">
            <thead>
                <tr>
                    <th colspan="5">REPUESTOS</th>
                </tr>
                <tr>
                    <th width="6%">N°</th>
                    <th>Repuesto</th>
                    <th>Cantidad Requerida</th>
                    <th>Costo</th>
                    <th width="17%">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $suma_total = 0;
                @endphp
                @if ($ot->orden_generada)
                    @if (count($ot->orden_generada->detalle_repuestos) > 0)
                        @php
                            $numero = 1;
                        @endphp
                        @foreach ($ot->orden_generada->detalle_repuestos as $dr)
                            <tr>
                                <td>{{ $numero++ }}</td>
                                <td>{{ $dr->repuesto->nombre }}</td>
                                <td>{{ $dr->cantidad_requerida }}</td>
                                <td>{{ $dr->costo }}</td>
                                <td>{{ $dr->total }}</td>
                            </tr>
                            @php
                                $suma_total += (float) $dr->total;
                            @endphp
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="centreado">ESTA ORDEN NO CUENTA CON PERSONAL ASIGNADO</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="5" class="centreado">ESTA ORDEN NO SE ENCUENTRA GENERADA</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="4" class="bold text-md derecha">TOTAL</td>
                    <td class="bold text-md">{{ number_format($suma_total, 2, '.', '') }}</td>
                </tr>
            </tbody>
        </table>
        @php
            $cont++;
        @endphp
        @if ($cont < count($orden_trabajos))
            <div class="break_page"></div>
        @endif
    @endforeach
</body>

</html>
