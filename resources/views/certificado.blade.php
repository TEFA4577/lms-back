<!DOCTYPE html>
<html lang="es">

<head>
    <title>Certificado</title>
</head>

<body>
    <style>
        @page {
            size: A4 landscape;
            margin-top: 0;
            margin-bottom: 0;
            margin-left: 0;
            margin-right: 0;
            padding: 0;
        }

        * {
            text-transform: capitalize
        }

        body {
            font-family: helvetica !important;
            font-size: 10pt;
            position: relative;
        }

        .nombreEstudiante {
            font-style: italic;
        }

        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-position: center top;
            background-repeat: no-repeat;
            /*background-color: wheat;*/
            background-image: url('./img/fondo-certificado.png');;
            z-index: -1;
        }

        #content {
            padding: 3.5cm 0.50cm 1.00cm 0.50cm;
        }

        #postal-address {
            margin: 0cm;
            margin-left: 1.50cm;
            margin-top: 0.00cm;
            margin-bottom: 1.00cm;
            font-size: 10pt;
        }

        #date {
            font-weight: bold;
        }
    </style>

    <div id="page-body">
        <div id="overlay">
            <div id="content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <div class="card">
                                <h1 style="text-align: center">{{$nombre_curso}}</h1>

                                <div class="card-body">
                                    <br><br><br><br><br><br><br><br>
                                    <p style="font-size:36px; padding-left:300px;">
                                        Entregado a:
                                        <span class="nombreEstudiante">{{$nombre_usuario}}</span>
                                    </p>
                                    <br>
                                    <p style="text-transform: lowercase; text-align: center; font-size:26px">
                                        Por su diligencia, fidelidad y perseverancia al aprender del curso <span
                                            class="nombreEstudiante">{{$nombre_curso}}</span> se le otorga el
                                        certificado que da constancia del esfuerzo realizado.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br><br>
                    <div style="font-size:12px; padding-left:100px;"> El presente certificado tiene validez de 1 año
                        calendario desde: <strong>{{$fecha_fin}} </strong></div>
                </div>
            </div>
        </div>
    </div>
</body>
