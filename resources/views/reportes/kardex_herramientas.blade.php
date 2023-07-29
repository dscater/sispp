<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Kardex Herramientas</title>
    <style type="text/css">
        * {
            font-family: sans-serif;
        }

        @page {
            margin-top: 180px;
            margin-bottom: 1cm;
            margin-left: 2cm;
            margin-right: 1cm;
        }

        header {
            position: fixed;
            top: -165px;
            left: 0px;
            right: 0px;
            height: 180px;
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
            background: rgb(202, 202, 202);
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
    </style>
</head>

<body>
    @inject('configuracion', 'App\Models\Configuracion')
    <header>
        <table border="1">
            <tbody>
                <tr>
                    <td class="centreado bold text-md" colspan="3">DOCUMENTO DE KARDEX DE HERRAMIENTAS</td>
                    <td class="logo centreado" width="30%"><img
                            src="{{ asset('imgs/' . $configuracion->first()->logo) }}" alt=""></td>
                </tr>
                <tr>
                    <td class="bold">Realizado por:</td>
                    <td>{{ Auth::user()->usuario }}</td>
                    <td class="bold">Fecha:</td>
                    <td>{{ date('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td class="bold">Cargo:</td>
                    <td>{{ Auth::user()->tipo }}</td>
                    <td class="bold">Página:</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </header>
    <main>
        @php
            $total = 0;
        @endphp
        <table border="1">
            <thead>
                <tr>
                    <th class="centreado bold text-md" colspan="6">KARDEX DE HERRAMIENTAS</th>
                </tr>
                <tr>
                    <th>CÓDIGO</th>
                    <th>NOMBRE</th>
                    <th>CANTIDAD</th>
                    <th>UNIDAD DE MEDIDA</th>
                    <th>PRECIO UNITARIO</th>
                    <th>COSTO TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($herramientas as $herramienta)
                    <tr>
                        <td>{{ $herramienta->codigo }}</td>
                        <td>{{ $herramienta->nombre }}</td>
                        <td>1</td>
                        <td>{{ $herramienta->unidad_medida }}</td>
                        <td>{{ $herramienta->costo }}</td>
                        <td>{{ $herramienta->costo }}</td>
                    </tr>
                    @php
                        $total += $herramienta->costo;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="5" class="bold text-md">TOTAL</td>
                    <td class="bold text-md">{{ number_format($total, 2, '.', '') }}</td>
                </tr>
            </tbody>
        </table>
    </main>
</body>

</html>
