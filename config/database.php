<?php
class Database {
    private $host = "my-postgres"; // service name in docker-compose
    private $db_name = "php_test";
    private $username = "postgres";
    private $password = "7cc01d92c8dce52eaf441dbae694cb34858e55ebeac43afbf100cb71de287b6c";
    private $port = "5432";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("pgsql:host=$this->host;port=$this->port;dbname=$this->db_name", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>