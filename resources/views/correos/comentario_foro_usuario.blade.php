<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0">
    <title>Notificacíón de interacción en foro- Academia Co Marca</title>
</head>

<body>
    <p>¡Hola, alguien hizo un comentario en tu curso {{$nombre_curso}}!.</p>
    <p>Detalle del comentario:</p>

    <ul>
        <li>Curso: {{$nombre_curso}}</li>
        <li>Clase: {{$titulo_clase}}</li>
        <li>Usuario: {{$nombre_usuario}}</li>
        <li>Comentario:{{$texto_comentario}}</li>
    </ul>
</body>

</html>
