<?php
require_once "conexion.php";
require_once "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Servidor {

    private $db;
    private $claveSecreta = "mi_clave_secreta"; // Clave secreta para JWT

    public function __construct() {
        $this->db = (new Conexion())->getConexion();
    }

    // Autenticar el token JWT
    public function autenticar($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->claveSecreta, 'HS256'));
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // Obtener un producto por ID
    public function obtenerProducto($id, $token) {
        if (!$this->autenticar($token)) {
            return [];
        }

        $stmt = $this->db->prepare("SELECT id, nombre, precio, stock FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            return [];
        }

        return [
            'id' => (int)$producto['id'],
            'nombre' => $producto['nombre'],
            'precio' => (float)$producto['precio'],
            'stock' => (int)$producto['stock']
        ];
    }

    // Agregar un nuevo producto
    public function agregarProducto($token, $nombre, $precio, $stock) {
        if (!$this->autenticar($token)) {
            return false;
        }

        $stmt = $this->db->prepare("INSERT INTO productos (nombre, precio, stock) VALUES (?, ?, ?)");
        return $stmt->execute([$nombre, $precio, $stock]);
    }

    // Actualizar un producto existente
    public function actualizarProducto($token, $id, $nombre, $precio, $stock) {
        if (!$this->autenticar($token)) {
            return false;
        }

        $stmt = $this->db->prepare("UPDATE productos SET nombre = ?, precio = ?, stock = ? WHERE id = ?");
        return $stmt->execute([$nombre, $precio, $stock, $id]);
    }

    // Eliminar un producto
    public function eliminarProducto($token, $id) {
        if (!$this->autenticar($token)) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>