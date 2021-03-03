<p align="center"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1200px-Laravel.svg.png" width="200"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Comandos Iniciales

#### para iniciar el proyecto realizar los siguientes comandos:

-   `composer update`

#### ejecutar nuevo comando para configuracion:

-   `php artisan configurar`

#### en caso de que el comando anterior de errores ejecutar los siguientes comandos:

-   `php artisan migrate:refresh --seed`
-   `php artisan passport:install`
-   `php artisan passport:client --personal` (introducir "LMS-BACKEND" sin comillas)
-   `php artisan storage:link`



## Consultas Acerca del Proyecto

-   revisar el archivo NOMENCLATURA PROYECTO LMS BACKEND
-   contactarse con el responsable de proyecto

## V.1.0.0
@author: [@AlexAguilarP](https://github.com/AlexAguilarP).

- Base del Proyecto, instalación Laravel Framework 7.26.1
- Intalacion de Laravel/Passport

## V.1.1.0
@author: [@AlexAguilarP](https://github.com/AlexAguilarP).

- Controladores
    - ClaseController
    - CursoController
    - DocenteController 
    - EtiquetaController
    - ModuloController
    - RecursoController
    - RedSocialController
    - UsuarioController
- Nuevo comando de configuracion `php artisan configurar`

## V.1.1.1
@author: [@AlexAguilarP](https://github.com/AlexAguilarP).
- instalacion de dompdf
- creacion de certificado (base)
## Acerca de Laravel

Laravel es un marco de aplicación web con una sintaxis elegante y expresiva.
Creemos que el desarrollo debe ser una experiencia divertida y creativa para ser verdaderamente satisfactorio. Laravel elimina la molestia del desarrollo al facilitar las tareas comunes que se usan en muchos proyectos web, como:

-   [Motor de enrutamiento simple y rápido](https://laravel.com/docs/routing).
-   [Potente contenedor de inyección de dependencias](https://laravel.com/docs/container).
-   Múltiples back-end para el almacenamiento de [sesión](https://laravel.com/docs/session) y [caché](https://laravel.com/docs/cache).
-   Expresivo, intuitivo [base de datos ORM](https://laravel.com/docs/eloquent).
-   Agnóstico de base de datos [migraciones de esquemas](https://laravel.com/docs/migrations).
-   [Procesamiento robusto de trabajos en segundo plano](https://laravel.com/docs/queues).
-   [Transmisión de eventos en tiempo real](https://laravel.com/docs/broadcasting).

Laravel es accesible, potente y proporciona las herramientas necesarias para aplicaciones grandes y robustas.

## Aprendiendo Laravel

Laravel tiene la [documentación](https://laravel.com/docs) y la biblioteca de videotutoriales más extensa y completa de todos los marcos de aplicaciones web modernos,
lo que hace que sea muy fácil comenzar con el marco.

Si no tiene ganas de leer, [Laracasts](https://laracasts.com) puede ayudar. Laracasts contiene más de 1500 videos tutoriales sobre una variedad de temas que incluyen Laravel, PHP moderno, pruebas unitarias y JavaScript. Mejore sus habilidades buscando en nuestra completa biblioteca de videos.

## Patrocinadores de Laravel

Nos gustaría extender nuestro agradecimiento a los siguientes patrocinadores por financiar el desarrollo de Laravel. Si está interesado en convertirse en patrocinador, visite Laravel [página de Patreon](https://patreon.com/taylorotwell).

### Socios Premium

-   **[Vehikl](https://vehikl.com/)**
-   **[Apriete Co.](https://tighten.co)**
-   **[Grupo de desarrollo de Kirschbaum](https://kirschbaumdevelopment.com)**
-   **[64 robots](https://64robots.com)**
-   **[Cubet Techno Labs](https://cubettech.com)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[Muchos](https://www.many.co.uk)**
-   **[Webdock, alojamiento VPS rápido](https://www.webdock.io/en)**
-   **[DevSquad](https://devsquad.com)**
-   **[OP.GG](https://op.gg)**

## Contribuyendo

¡Gracias por considerar contribuir al marco de Laravel! La guía de contribución se puede encontrar en la [documentación de Laravel](https://laravel.com/docs/contributions).

## Código de Conducta

Para garantizar que la comunidad de Laravel sea acogedora para todos,
por favor revise y acate el [Código de conducta](https://laravel.com/docs/contributions#code-of-conduct).

## Vulnerabilidades de seguridad

Si descubre una vulnerabilidad de seguridad dentro de Laravel, envíe un correo electrónico a Taylor Otwell a través de [taylor@laravel.com](mailto:taylor@laravel.com). Todas las vulnerabilidades de seguridad se abordarán de inmediato.

## Licencia

El marco de Laravel es un software de código abierto con licencia de [licencia MIT](https://opensource.org/licenses/MIT).
