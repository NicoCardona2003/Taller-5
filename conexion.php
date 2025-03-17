<?php
	class conexion {
        private $host = "localhost";
        private $dbname = "webservice";
        private $user = "root";
        private $pass = "";
        private $pdo;

        public function __construct() {
            try { 
                $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->user, $this->pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Error en la conexion: " . $e->getMessage());
            }
        }

        public function getConexion() {
        return $this->pdo;
    }
}    
?>