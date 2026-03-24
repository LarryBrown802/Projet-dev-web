<?php
namespace App\Models;

use App\Database;

class ExempleModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getConnection();
    }

    public function getAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM ma_table");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}