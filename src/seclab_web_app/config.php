<?php 
// Original var names to not change existing includes
$servername = "127.0.0.1";
$username       = "postgres";
$password       = "123456";
$dbname         = "Test_Lab";

// Proxy class to adapt MySQLi syntax to PostgreSQL implicitly.
// We are explicitly implementing this so we don't have to rewrite 
// any of the intentionally vulnerable security labs queries over the app!

class postgres_mysqli {
    public $connect_error = null;
    public $error = null;
    private $pdo;

    public function __construct($host, $user, $pass, $db) {
        try {
            $this->pdo = new PDO("pgsql:host=$host;dbname=$db", $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        } catch (PDOException $e) {
            $this->connect_error = $e->getMessage();
        }
    }

    public function query($sql) {
        // Keep the intended injection surface directly by executing the raw SQL string
        $stmt = $this->pdo->query($sql);
        if ($stmt === false) {
            $errorInfo = $this->pdo->errorInfo();
            $this->error = $errorInfo[2];
            return false;
        }
        return new postgres_mysqli_result_proxy($stmt);
    }

    public function real_escape_string($str) {
        // Simple escape string, note that PDO::quote adds surrounding quotes, so we strip them here to mimic real_escape_string
        $quoted = $this->pdo->quote($str);
        return substr($quoted, 1, -1);
    }
    
    public function close() {
        $this->pdo = null;
    }
}

class postgres_mysqli_result_proxy {
    private $stmt;
    public $num_rows;

    public function __construct($stmt) {
        $this->stmt = $stmt;
        $this->num_rows = $stmt->rowCount();
    }

    public function fetch_assoc() {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetch_all($mode = PDO::FETCH_ASSOC) {
        return $this->stmt->fetchAll($mode);
    }
}
?>
