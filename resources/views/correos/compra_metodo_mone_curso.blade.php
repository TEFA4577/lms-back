<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Enlace de compra mediante MonePagos - Academia Co Marca</title>
</head>

<body>

    <p>Hola {{$nombre_usuario}}!. Ingresa en el siguiente enlace para concretar la compra del curso {{$nombre_curso}}</p>
    <p>Este es un enlace de compra emdiante código QR. Atención: Este enlace estará disponible por 24 horas. Haz el pago correspondiente lo
        más antes posible o tu solicitud será cancelada. Gracias!</p>
    <a href="{{$enlace}}">
        {{$enlace}}
    </a>

</body>

</html>
