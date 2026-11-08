# CRUD de clientes con PHP, Visual Studio Code y XAMPP

Visual Studio Code es el editor. Apache, incluido en XAMPP, recibe las visitas del navegador y ejecuta PHP. MariaDB guarda los clientes. En el panel de XAMPP, el servicio de MariaDB puede aparecer como **MySQL Database** o **MySQL**. [Guía de XAMPP para macOS](https://www.apachefriends.org/es/faq_osx.html)

## Puesta en marcha

1. Abre XAMPP. En **Manage Servers**, inicia **Apache Web Server** y **MySQL Database**.
2. Abre [phpMyAdmin](http://localhost/phpmyadmin/), selecciona **Importar** y elige el archivo `database/base_de_datos.sql` del ejercicio. Pulsa **Importar**. Esto crea la base `crud_clientes` y su tabla `clientes`.
3. En Finder, copia la carpeta `crud-clientes` dentro de `/Applications/XAMPP/xamppfiles/htdocs`. En Windows la carpeta suele ser `C:\xampp\htdocs`. Apache sirve los archivos que están dentro de `htdocs`.
4. En Visual Studio Code, selecciona **Archivo → Abrir carpeta** y elige la copia de `crud-clientes` que quedó dentro de `htdocs`. Guarda ahí tus cambios.
5. Abre el [registro de clientes](http://localhost/crud-clientes/public/). Si configuraste una contraseña para `root` o cambiaste el puerto de MySQL, ajusta `password` o añade `port` en `config/database.local.php`.

En `database/base_de_datos.sql` cada instrucción tiene un comentario. Al guardar un cliente, MariaDB asigna el ID automáticamente. En `config/database.php`, el texto que empieza con `mysql:` le dice a PHP cómo llegar a la base. `new PDO(...)` crea la conexión que reciben los métodos de `Cliente`.

## Cómo se organiza MVC

MVC significa Modelo, Vista y Controlador. Separa el trabajo de la aplicación en tres responsabilidades.

- **Modelo**. `src/Models/Cliente.php` representa a cada cliente y contiene las consultas SQL para crear, leer, actualizar y eliminar registros.
- **Vista**. `src/Views/clientes/index.php` muestra el formulario, los mensajes y la lista. Utiliza las variables que preparó el controlador y convierte los datos en texto seguro para HTML.
- **Controlador**. `src/Controllers/ClienteController.php` recibe los datos del navegador, los valida, llama al modelo y carga la vista.

```text
crud-clientes/
├── config/
│   └── database.php
├── database/
│   └── base_de_datos.sql
├── public/
│   ├── index.php
│   └── css/
│       └── estilos.css
├── src/
│   ├── Controllers/
│   │   └── ClienteController.php
│   ├── Models/
│   │   └── Cliente.php
│   └── Views/
│       └── clientes/
│           └── index.php
└── README.md
```

Abre `public/index.php` primero. Carga las clases, obtiene la conexión, crea un controlador y le entrega la petición del navegador. `require` carga un archivo. Cuando ese archivo usa `return`, entrega un valor al archivo que lo llamó. Por eso la conexión termina guardada en `$db`.

Después lee `ClienteController.php`. `$this->db` es la conexión que el constructor guardó dentro del controlador. Su método `gestionar()` recibe tres argumentos. `$metodo` indica si estás abriendo la página o enviando un formulario. `$datos` contiene los campos enviados por POST. `$parametros` contiene valores de la dirección, como el ID que quieres editar.

Al guardar a Ana, el recorrido es navegador → punto de entrada → controlador → modelo → MariaDB. Después de guardar, el controlador redirige a la lista. En esa nueva visita, solicita los clientes al modelo y carga la vista para mostrarlos.

En la vista, `require` permite utilizar las variables locales del controlador, como `$clientes` y `$error`. La dirección del CSS se interpreta desde la dirección que abrió el navegador, por eso sigue siendo `css/estilos.css`.

Lee los comentarios junto a cada instrucción. Las llaves delimitan bloques. El punto y coma termina una instrucción PHP. Las etiquetas HTML que empiezan por `</` cierran los elementos.

| Letra | Operación | Método PHP | Instrucción SQL |
| --- | --- | --- | --- |
| C | Crear un cliente | `crear()` | `INSERT` |
| R | Leer los clientes | `listar()` | `SELECT` |
| U | Actualizar un cliente | `actualizar()` | `UPDATE` |
| D | Eliminar un cliente | `eliminar()` | `DELETE` |

Dentro de `crear()`, `$this->nombre` significa «el nombre de este cliente». Si el objeto es Ana, esa expresión devuelve `Ana`. En cambio, `Cliente::listar($db)` se llama desde la clase porque necesita consultar la tabla completa. Devuelve una lista en la que cada elemento es un objeto `Cliente`.

`prepare()` define la instrucción SQL y `execute()` entrega los valores para los signos `?`. Así, un nombre como `Ana O'Connor` se trata como un dato, incluida la comilla.

## Prueba el ciclo completo

1. Escribe **Ana O'Connor** y **ana@example.com**, y pulsa **Guardar**. Ana debe aparecer en la lista.
2. Recarga la página. Debe aparecer una sola vez.
3. Pulsa **Editar**, cambia el correo a **ana.nuevo@example.com** y guarda. Busca el correo nuevo en la lista.
4. Pulsa **Eliminar**. La fila de Ana debe desaparecer.


## Credenciales privadas

El usuario y la contraseña se configuran en `config/database.local.php`. Ese archivo está excluido de Git. La copia local preparada utiliza los valores anteriores de XAMPP. Para otra instalación, copia `config/database.example.php` como `config/database.local.php` y completa sus datos. Comparte el archivo de ejemplo, conservando el archivo local en tu equipo.

También puedes definir `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` y `DB_PASSWORD` en el entorno del servidor. Esos valores tienen prioridad sobre el archivo local.

Los archivos `.htaccess` bloquean por HTTP las carpetas internas y permiten el acceso a `public/`. Apache debe permitir estas directivas mediante `AllowOverride AuthConfig Options` o `AllowOverride All` en la configuración correspondiente. Si configuras un sitio virtual, apunta su `DocumentRoot` a `public/`.

Un error de conexión muestra un aviso general. El registro guarda únicamente el tipo de excepción. Las credenciales siguen siendo legibles por quien tenga acceso autorizado a los archivos locales del servidor.
