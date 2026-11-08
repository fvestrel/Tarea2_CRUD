<?php 
function escapar(string $texto): string 
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8'); 
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de clientes</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <header class="barra">
        <div class="contenedor encabezado">
            <h1>Gestión de clientes</h1>
            <p>Proyecto académico · Ingeniería de Software</p>
        </div>
    </header>
    <main class="contenedor">
        <div class="distribucion">
            <section class="panel formulario-panel" aria-labelledby="titulo-formulario">
                <h2 id="titulo-formulario"><?= $id ? 'Editar cliente' : 'Nuevo cliente' ?></h2>
                <p class="ayuda"><?= $id ? 'Actualiza la información y guarda los cambios.' : 'Completa ambos campos para añadir un contacto.' ?></p>
                <?php if ($error !== ''): // Muestra el aviso solo cuando hay un error. ?>
                    <p class="aviso" role="alert"><?= escapar($error) ?></p>
                <?php endif; ?>
                <form class="formulario" method="post" action="index.php">
                    <input type="hidden" name="id" value="<?= (int) $id ?>"><!-- Envía el ID sin mostrarlo como campo editable. -->
                    <label for="nombre">Nombre completo</label><!-- for asocia la etiqueta con su campo. -->
                    <input id="nombre" name="nombre" autocomplete="name" placeholder="Ej. Ana López" value="<?= escapar($nombre) ?>" required>
                    <label for="correo">Correo electrónico</label>
                    <input id="correo" type="email" name="correo" autocomplete="email" placeholder="ana@ejemplo.com" value="<?= escapar($correo) ?>" required>
                    <button class="boton principal" name="accion" value="<?= $id ? 'actualizar' : 'crear' ?>"><?= $id ? 'Guardar cambios' : 'Guardar cliente' ?></button>
                    <?php if ($id): // Ofrece una salida del modo edición. ?>
                        <a class="cancelar" href="index.php">Cancelar edición</a>
                    <?php endif; ?>
                </form>
            </section>
            <section class="panel directorio" aria-labelledby="titulo-directorio">
                <div class="encabezado-lista">
                    <div>
                        <h2 id="titulo-directorio">Consulta de clientes</h2>
                        <p class="descripcion-lista">Revisa los datos registrados y selecciona una acción.</p>
                    </div>
                    <span class="contador"><?= count($clientes) ?> en total</span><!-- count cuenta los objetos de la lista. -->
                </div>
                <?php if ($clientes === []): ?>
                    <div class="vacio">
                        <h3>No hay clientes registrados</h3>
                        <p>Usa el formulario para añadir un nombre y un correo.</p>
                    </div>
                <?php else: ?>
                    <div class="tabla-contenedor">
                        <table>
                            <caption class="solo-lectores">Clientes registrados y acciones disponibles</caption>
                            <thead><!-- Encabezados de las columnas. -->
                                <tr>
                                    <th scope="col">Nombre completo</th>
                                    <th scope="col">Correo electrónico</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clientes as $cliente): // Crea una fila por cliente. ?>
                                    <tr>
                                        <td class="nombre-cliente"><span class="icono-cliente" aria-hidden="true">&#9679;</span><?= escapar($cliente->nombre) ?></td><!-- Muestra el nombre de forma segura. -->
                                        <td><?= escapar($cliente->correo) ?></td>
                                        <td>
                                            <div class="acciones">
                                                <a class="boton secundario" href="index.php?editar=<?= $cliente->id ?>" aria-label="Editar a <?= escapar($cliente->nombre) ?>"><span aria-hidden="true">&#9998;</span> Editar</a>
                                                <form method="post" action="index.php">
                                                    <input type="hidden" name="id" value="<?= $cliente->id ?>"><!-- El ID identifica la fila que se debe eliminar. -->
                                                    <button class="boton eliminar" name="accion" value="eliminar" aria-label="Eliminar a <?= escapar($cliente->nombre) ?>"><span aria-hidden="true">&times;</span> Eliminar</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </section>
        </div>
        <footer>Proyecto de desarrollo web · CRUD de clientes</footer>
    </main>
</body>
</html>
