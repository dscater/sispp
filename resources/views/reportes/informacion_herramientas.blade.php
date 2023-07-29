<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Información Herramientas</title>
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
    @foreach ($herramientas as $herramienta)
        <table>
            <tr>
                <td class="derecha bold text-red text-md">{{ $herramienta->codificacion }}</td>
            </tr>
        </table>
        <table border="1">
            <tbody>
                <tr>
                    <td class="centreado bold text-md" colspan="3">DOCUMENTO DE RELEVAMIENTO DE INFORMACIÓN DE
                        HERRAMIENTAS</td>
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
                    <td class="bold">Código:</td>
                    <td>{{ $herramienta->codigo }}</td>
                    <td class="bold">Clasificación:</td>
                    <td>{{ $herramienta->clasificacion }}</td>
                </tr>
                <tr>
                    <td class="bold">Nombre:</td>
                    <td>{{ $herramienta->nombre }}</td>
                    <td class="bold">Cod. Clasificación:</td>
                    <td>{{ $herramienta->cod_clasificacion }}</td>
                </tr>
                <tr>
                    <td class="bold">Descripción:</td>
                    <td>{{ $herramienta->descripcion }}</td>
                    <td class="bold">Almacén:</td>
                    <td>{{ $herramienta->almacen }}</td>
                </tr>
            </tbody>
        </table>
        <table border="1">
            <thead>
                <tr>
                    <th colspan="4">CARACTERISTICAS GENERALES</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="bold">Marca:</td>
                    <td>{{ $herramienta->marca }}</td>
                    <td class="bold">Fabricante:</td>
                    <td>{{ $herramienta->fabricante }}</td>
                </tr>
                <tr>
                    <td class="bold">Modelo:</td>
                    <td>{{ $herramienta->modelo }}</td>
                    <td class="bold">Proveedor:</td>
                    <td>{{ $herramienta->proveedor }}</td>
                </tr>
                <tr>
                    <td class="bold">Serie:</td>
                    <td>{{ $herramienta->serie }}</td>
                    <td class="bold">Terciario:</td>
                    <td>{{ $herramienta->terciarios }}</td>
                </tr>
                <tr>
                    <td class="bold">Especificaciones técnicas:</td>
                    <td>{{ $herramienta->e_tecnicas }}</td>
                    <td class="bold">Foto:</td>
                    <td><img src="{{ asset('imgs/herramientas/' . ($herramienta->foto ? $herramienta->foto : 'default.png')) }}"
                            alt="Foto" width="90px"></td>
                </tr>
            </tbody>
        </table>
        @php
            $cont++;
        @endphp
        @if ($cont < count($herramientas))
            <div class="break_page"></div>
        @endif
    @endforeach
</body>

</html>
