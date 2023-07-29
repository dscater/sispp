<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Maquinaria</title>
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
    <table>
        <tr>
            <td class="derecha bold text-red text-md">{{ $maquinaria->codificacion }}</td>
        </tr>
    </table>
    <table border="1">
        <tbody>
            <tr>
                <td class="centreado bold text-md" colspan="3">DOCUMENTO DE RELEVAMIENTO DE INFORMACIÓN DE MAQUINARIA/EQUIPO</td>
                <td class="logo centreado" width="30%"><img src="{{ asset('imgs/' . $configuracion->first()->logo) }}"
                        alt=""></td>
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
                <td class="bold" width="15%">Código:</td>
                <td>{{ $maquinaria->equipo->codigo }}</td>
                <td class="bold"  width="15%">Sistema:</td>
                <td>{{ $maquinaria->equipo->sistema->nombre }}</td>
            </tr>
            <tr>
                <td class="bold">Maquina/Equipo:</td>
                <td>{{ $maquinaria->equipo->nombre }}</td>
                <td class="bold">Ubicación:</td>
                <td>{{ $maquinaria->ubicacion }}</td>
            </tr>
            <tr>
                <td rowspan="2" class="bold">Descripción:</td>
                <td rowspan="2">{{ $maquinaria->descripcion }}</td>
                <td class="bold">Tipo:</td>
                <td>{{ $maquinaria->tipo }}</td>
            </tr>
            <tr>
                <td class="bold">Prioridad:</td>
                <td>{{ $maquinaria->prioridad }}</td>
            </tr>
            <tr>
                <td class="bold text-md centreado" colspan="4">CARACTERISTICAS GENERALES</td>
            </tr>
            <tr>
                <td class="bold">Marca:</td>
                <td>{{ $maquinaria->marca }}</td>
                <td class="bold">Fabricante:</td>
                <td>{{ $maquinaria->fabricante }}</td>
            </tr>
            <tr>
                <td class="bold">Modelo:</td>
                <td>{{ $maquinaria->modelo }}</td>
                <td class="bold">Proveedor:</td>
                <td>{{ $maquinaria->proveedor }}</td>
            </tr>
            <tr>
                <td class="bold">Serie:</td>
                <td>{{ $maquinaria->serie }}</td>
                <td class="bold">Terciario:</td>
                <td>{{ $maquinaria->terciarios }}</td>
            </tr>
        </tbody>
    </table>
    <table border="1">
        <tbody>
            <tr>
                <td colspan="10">CARACTERISTICAS TECNICAS</td>
            </tr>
            <tr>
                <td class="bold">Peso:</td>
                <td>{{ $maquinaria->peso }}</td>
                <td class="bold">Altura:</td>
                <td>{{ $maquinaria->altura }}</td>
                <td class="bold">Largo:</td>
                <td>{{ $maquinaria->largo }}</td>
                <td class="bold">Ancho:</td>
                <td>{{ $maquinaria->ancho }}</td>
                <td class="bold">Capacidad:</td>
                <td>{{ $maquinaria->capacidad }}</td>
            </tr>
            <tr>
                <td class="bold" colspan="2">Especificaciones técnicas:</td>
                <td colspan="5">{{ $maquinaria->e_tecnicas }}</td>
                <td class="bold">Foto:</td>
                <td colspan="2"><img src="{{ asset('imgs/maquinarias/' . ($maquinaria->foto ? $maquinaria->foto : 'default.png')) }}"
                        alt="Foto" width="90px"></td>
            </tr>
            <tr>
                <td class="bold" colspan="2">Fecha de instalación:</td>
                <td colspan="2">{{ $maquinaria->fecha_instalacion }}</td>
                <td class="bold">Estado:</td>
                <td colspan="5">{{ $maquinaria->estado }}</td>
            </tr>
        </tbody>
    </table>
</body>

</html>
