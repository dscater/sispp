<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitud Herramientas</title>
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
            margin-top: 0px;
            page-break-before: avoid;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
        }

        table thead {
            background: rgb(236, 236, 236)
        }

        table thead tr th {
            font-size: 0.7em;
        }

        table tbody tr td {
            font-size: 0.7em;
        }

        tr {
            page-break-inside: avoid !important;
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

        .centreado {
            padding-left: 0px;
            text-align: center;
        }

        .gray {
            background: rgb(202, 202, 202);
        }

        .img_celda img {
            width: 45px;
        }

        .bold {
            font-weight: bold;
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
            <h4 class="texto">INFORME DE SOLICITUDES DE HERRAMIENTAS</h4>
            <h4 class="fecha">{{ date('Y-m-d') }}</h4>
        </div>

        <table border="1" style="margin-top:25px;">
            <tbody>
                <tr>
                    <td class="bold">Número de OT:</td>
                    <td>{{ $ot->id }}</td>
                    <td class="bold">Fecha de OT:</td>
                    <td>{{ $ot->fecha_programada }} {{ $ot->hora_programada }}</td>
                </tr>
                <tr>
                    <td class="bold">Equipo:</td>
                    <td colspan="3">{{ $ot->gama->equipo->nombre }}</td>
                </tr>
            </tbody>
        </table>
        <table border="1">
            <tbody>
                <tr>
                    <td class="bold centreado" colspan="4">HERRAMIENTAS SOLICITADAS</td>
                </tr>
                <tr>
                    <td class="bold" width="5%">N°</td>
                    <td class="bold">Herramienta</td>
                    <td class="bold">Cantidad</td>
                    <td class="bold">Unidad de medida</td>
                </tr>
                @if ($ot->orden_generada)
                    @if (count($ot->orden_generada->detalle_herramientas) > 0)
                        @php
                            $nro = 1;
                        @endphp
                        @foreach ($ot->orden_generada->detalle_herramientas as $dh)
                            <tr>
                                <td>{{ $nro++ }}</td>
                                <td>{{ $dh->herramienta->nombre }}</td>
                                <td>{{ $dh->cantidad_solicitada }}</td>
                                <td>{{ $dh->herramienta->unidad_medida }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="bold centreado">Aún no se solicitó ninguna herramienta</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="4" class="bold centreado">Esta Orden no se genero aún</td>
                    </tr>
                @endif
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
