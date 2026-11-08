<?php 
$rutaLocal = __DIR__ . '/database.local.php'; // Este archivo queda excluido del repositorio.
$local = is_file($rutaLocal) ? require $rutaLocal : [];
$host = getenv('DB_HOST') !== false ? getenv('DB_HOST') : ($local['host'] ?? '127.0.0.1'); 
$puerto = getenv('DB_PORT') !== false ? getenv('DB_PORT') : ($local['port'] ?? '3306'); 
$base = getenv('DB_NAME') !== false ? getenv('DB_NAME') : ($local['database'] ?? 'crud_clientes'); 
$usuario = getenv('DB_USER') !== false ? getenv('DB_USER') : ($local['username'] ?? null); 
$contrasena = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : ($local['password'] ?? null); 
if ($usuario === null || $contrasena === null) {
    throw new RuntimeException('Falta la configuración privada de la base de datos.'); 
}
$direccion = "mysql:host={$host};port={$puerto};dbname={$base};charset=utf8mb4"; 
return new PDO($direccion, $usuario, $contrasena, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
]);
