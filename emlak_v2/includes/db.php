<?php
// Database Connection
class Database {
    private $conn;
    
    public function __construct($host, $user, $pass, $db) {
        $this->conn = new mysqli($host, $user, $pass, $db);
    }
}
?>