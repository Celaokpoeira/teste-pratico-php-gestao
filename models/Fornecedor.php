<?php
class Fornecedor {
    private $conn;
    private $table_name = "fornecedores";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function cadastrar($nome, $cnpj, $email, $telefone, $status) {
        $query = "INSERT INTO " . $this->table_name . " (nome, cnpj, email, telefone, status) VALUES (:nome, :cnpj, :email, :telefone, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpeza básica dos dados
        $nome = htmlspecialchars(strip_tags($nome));
        
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":cnpj", $cnpj);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":status", $status);
        
        return $stmt->execute();
    }
}
?>