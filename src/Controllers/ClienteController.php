<?php 

class ClienteController
{
    public function __construct(private PDO $db) 
    {
    }

    public function gestionar(string $metodo, array $datos, array $parametros): void 
    {
        $error = ''; 
        $nombre = ''; 
        $correo = ''; 
        $id = null; 

        if ($metodo === 'POST') { 
            $id = filter_var($datos['id'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]); 
            $nombre = trim(is_string($datos['nombre'] ?? null) ? $datos['nombre'] : ''); 
            $correo = trim(is_string($datos['correo'] ?? null) ? $datos['correo'] : ''); 
            $accion = $datos['accion'] ?? ''; 
            $id = $accion === 'crear' ? null : $id; 

            if ($accion === 'eliminar' && $id) { 
                $cliente = new Cliente('', '', $id); 
                $cliente->eliminar($this->db); 
            } elseif ($accion === 'crear' || ($accion === 'actualizar' && $id)) { 
                if ($nombre === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) { 
                    $error = 'Escribe un nombre y un correo válido.'; 
                } else {
                    $cliente = new Cliente($nombre, $correo, $id ?: null); 
                    if ($accion === 'crear') {
                        $cliente->crear($this->db); 
                    } else {
                        $cliente->actualizar($this->db); 
                    }
                }
            } else {
                $error = 'La acción o el ID del cliente no son válidos.'; 
            }

            if ($error === '') {
                header('Location: index.php', true, 303); 
                exit; 
            }
        }

        $clientes = Cliente::listar($this->db); 
        $editar = filter_var($parametros['editar'] ?? '', FILTER_VALIDATE_INT); 
        if ($metodo === 'GET' && $editar) { 
            foreach ($clientes as $cliente) { 
                if ($cliente->id === $editar) { 
                    $id = $cliente->id; 
                    $nombre = $cliente->nombre; 
                    $correo = $cliente->correo; 
                    break; 
                }
            }
        }

        require __DIR__ . '/../Views/clientes/index.php'; 
    }
}
