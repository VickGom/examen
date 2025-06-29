Para levantar el proyecto*****
Yo levante el proyecto en laragon, meti todo el proyecto en la carpeta de laragon/www/
despues inicie laragon php 8.2

1.- git clone https://github.com/VickGom/examen.git
cd examen (nombre del la carpeta donde se encuentra el proyecto)

2.- cd back
composer install

3.- cp .env.example .env
(En el archivo .env mueve la conexion a base de datos)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=examen
DB_USERNAME=root
DB_PASSWORD=123456789

4.- ejecuta este comando en la carpeta back: php artisan key:generate

5.- php artisan migrate
    php artisan db:seed

6.- php artisan storage:link

7.- php artisan serve --host=0.0.0.0 --port=8000

--------------------------------------

8.- levantar el front abriendo una nueva terminal en laragon, te vas a la carpeta front con cd front y ejecutas el comando npm install

9.- inicias con el comando: npm run dev,
y copias la url que te genera el comando y la pegas en el navegador

10.- hacer pruebas 