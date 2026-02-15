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

    // Busca um fornecedor específico para preencher o formulário de edição
    public function buscarPorId($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Atualiza os dados no banco
    public function editar($id, $nome, $cnpj, $email, $telefone, $status) {
        $query = "UPDATE " . $this->table_name . " 
                  SET nome = :nome, cnpj = :cnpj, email = :email, telefone = :telefone, status = :status 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        $nome = htmlspecialchars(strip_tags($nome));
        
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":cnpj", $cnpj);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":telefone", $telefone);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $id);
        
        return $stmt->execute();
    }
}
?>