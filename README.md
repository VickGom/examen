Para levantar el proyecto*****
Yo levante el proyecto en laragon, meti todo el proyecto en la carpeta de laragon/www/
despues inicie laragon

1.- git clone <URL_DEL_REPOSITORIO>
cd examen (nombre del la carpeta donde se encuentra el proyecto)

2.- cd back
composer install

3.- cp .env.example .env
(En el archivo .env mueve la conexion a base de datos)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=examen
DB_USERNAME=root
DB_PASSWORD=

4.- ejecuta este comando en la carpeta back: php artisan key:generate

5.- php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

6.- php artisan migrate

7.- php artisan storage:link

8.- php artisan serve --host=0.0.0.0 --port=8000

--------------------------------------

9.- levantar el front, te vas a la carpeta front con cd front y ejecutas el comando npm install

10.- inicias con npm run dev y copias la url que te genera el comando y la pegas en el navegador

11.- hacer pruebas 