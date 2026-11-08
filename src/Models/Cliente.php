<?php 

class Cliente 
{
    public function __construct( 
        public string $nombre, 
        public string $correo, 
        public ?int $id = null 
    ) {
    } 

    public function crear(PDO $db): void /
    {
        $consulta = $db->prepare('INSERT INTO clientes (nombre, correo) VALUES (?, ?)'); 
        $consulta->execute([$this->nombre, $this->correo]); 
        $this->id = (int) $db->lastInsertId(); 
    }

    public static function listar(PDO $db): array 
    {
        $consulta = $db->query('SELECT id, nombre, correo FROM clientes ORDER BY id'); 
        $clientes = []; 
        foreach ($consulta->fetchAll(PDO::FETCH_ASSOC) as $fila) { 
            $clientes[] = new Cliente($fila['nombre'], $fila['correo'], (int) $fila['id']); 
        }
        return $clientes; 
    }

    public function actualizar(PDO $db): void 
    {
        $consulta = $db->prepare('UPDATE clientes SET nombre = ?, correo = ? WHERE id = ?'); 
        $consulta->execute([$this->nombre, $this->correo, $this->id]); 
    }

    public function eliminar(PDO $db): void 
    {
        $consulta = $db->prepare('DELETE FROM clientes WHERE id = ?'); 
        $consulta->execute([$this->id]); 
    }
} 
