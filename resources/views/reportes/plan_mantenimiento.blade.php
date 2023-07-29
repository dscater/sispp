<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Plan Mantenimiento</title>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code&display=swap" rel="stylesheet">

    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 2cm;
            margin-right: 1cm;
        }


        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-before: avoid;
        }

        table thead tr th,
        tbody tr td {
            padding: 3px;
            word-wrap: break-word;
        }

        table thead tr th {
            font-size: 9pt;
        }

        table tbody tr td {
            padding: 5px;
            font-size: 8pt;
        }

        table {
            width: 100%;
        }

        table thead {
            background: rgb(236, 236, 236)
        }

        .text-md {
            font-size: 10.5pt;
        }

        .logo img {
            width: 200px;
        }

        tr {
            page-break-inside: avoid !important;
        }

        .centreado {
            padding-left: 0px;
            text-align: center;
        }

        .gray {
            background: rgb(236, 236, 236)
        }

        .green {
            background: #083b7a;
            color: white;
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

        .text-red {
            color: red;
        }

        .codificacion {
            font-size: 12pt;
        }

        .derecha {
            text-align: right;
        }
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    @php
        $cont = 0;
    @endphp
    @foreach ($plan_mantenimientos as $plan_mantenimiento)
        <table>
            <tr>
                <td class="derecha bold text-red text-md">{{ $plan_mantenimiento->codificacion }}</td>
            </tr>
        </table>
        <table border="1">
            <tbody>
                <tr>
                    <td class="centreado bold text-md" colspan="3">PLAN DE MANTENIMIENTO</td>
                    <td class="logo centreado" width="30%"><img
                            src="{{ asset('imgs/' . $configuracion->first()->logo) }}" alt=""></td>
                </tr>
                <tr>
                    <td class="bold">Realizado por:</td>
                    <td>{{ Auth::user()->usuario }}</td>
                    <td class="bold">Fecha:</td>
                    <td>{{ date('Y-m-d') }}</td>
                </tr>
            </tbody>
        </table>
        <table border="1" style="margin-top:10px">
            <tbody>
                <tr>
                    <td class="bold">Subunidad:</td>
                    <td>{{ $plan_mantenimiento->subunidad->nombre }}</td>
                    <td class="bold">Gama de Mantenimiento:</td>
                    <td>{{ $plan_mantenimiento->gama->codigo }}</td>
                </tr>
                <tr>
                    <td class="bold">PM:</td>
                    <td>{{ $plan_mantenimiento->pm }}</td>
                    <td class="bold">Prioridad:</td>
                    <td>{{ $plan_mantenimiento->prioridad }}</td>
                </tr>
                <tr>
                    <td class="bold">Tiempo estimado:</td>
                    <td>{{ $plan_mantenimiento->tiempo }}</td>
                    <td class="bold">Días estimados para terminar:</td>
                    <td>{{ $plan_mantenimiento->dias }}</td>
                </tr>
                <tr>
                    <td class="bold">Tipo Mantenimiento:</td>
                    <td>{{ $plan_mantenimiento->tipo_mantenimiento }}</td>
                    <td class="bold">Estado:</td>
                    <td>{{ $plan_mantenimiento->estado }}</td>
                </tr>
            </tbody>
        </table>
        <table border="1">
            <thead>
                <tr>
                    <th colspan="4">DATOS DE HISTORIAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="bold">Última fecha programada:</td>
                    <td>{{ $plan_mantenimiento->ultima_fecha_programada }}</td>
                    <td class="bold">Última fecha terminada:</td>
                    <td>{{ $plan_mantenimiento->ultima_fecha_terminada }}</td>
                </tr>
            </tbody>
        </table>
        <table border="1">
            <thead>
                <tr>
                    <th colspan="4">PROGRAMACIÓN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="bold">Programación:</td>
                    <td>{{ $plan_mantenimiento->programacion }}</td>
                    <td class="bold">Fecha final de programación:</td>
                    <td>{{ $plan_mantenimiento->fecha_final }}</td>
                </tr>
            </tbody>
        </table>
        <table border="1">
            <thead>
                <tr>
                    <th colspan="4">FRECUENCIA DE MANTENIMIENTO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="bold">Variable de control:</td>
                    <td>{{ $plan_mantenimiento->variable_control->nombre }}</td>
                    <td class="bold">Frecuencia:</td>
                    <td>{{ $plan_mantenimiento->frecuencia->nombre }}</td>
                </tr>
            </tbody>
        </table>
        <table border="1">
            <thead>
                <tr>
                    <th colspan="3">PLAN DE PROGRAMACIÓN</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="bold gray">N° Mantenimiento</td>
                    <td class="bold gray">Días</td>
                    <td class="bold gray">Fecha</td>
                </tr>
                @if (count($plan_mantenimiento->programacions) > 0)
                    @foreach ($plan_mantenimiento->programacions as $programacion)
                        <tr>
                            <td>{{ $programacion->numero }}</td>
                            <td>{{ $programacion->dias }}</td>
                            <td>{{ $programacion->fecha }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        @php
            $cont++;
        @endphp
        @if ($cont < count($plan_mantenimientos))
            <div class="break_page"></div>
        @endif
    @endforeach
</body>

</html>
