<?php
class Produto {
    private $conn;
    private $table_name = "produtos";
    private $table_pivot = "produto_fornecedor";

    public function __construct($db) {
        $this->conn = $db;
    }

    // --- MÉTODOS DE CRUD DO PRODUTO ---

    public function listar() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function cadastrar($nome, $descricao, $codigo_interno, $status) {
        $query = "INSERT INTO " . $this->table_name . " (nome, descricao, codigo_interno, status) VALUES (:nome, :descricao, :codigo_interno, :status)";
        
        $stmt = $this->conn->prepare($query);
        
        $nome = htmlspecialchars(strip_tags($nome));
        $descricao = htmlspecialchars(strip_tags($descricao));
        $codigo_interno = htmlspecialchars(strip_tags($codigo_interno));
        
        $stmt->bindParam(":nome", $nome);
        $stmt->bindParam(":descricao", $descricao);
        $stmt->bindParam(":codigo_interno", $codigo_interno);
        $stmt->bindParam(":status", $status);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            // Caso o codigo_interno já exista (pois é UNIQUE no banco)
            return false; 
        }
    }

    // --- MÉTODOS DE RELACIONAMENTO (VÍNCULOS) ---

    public function getFornecedoresVinculados($produto_id) {
        $query = "SELECT f.id, f.nome, f.cnpj 
                  FROM fornecedores f
                  INNER JOIN " . $this->table_pivot . " pf ON f.id = pf.fornecedor_id
                  WHERE pf.produto_id = :produto_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function vincularFornecedor($produto_id, $fornecedor_id) {
        $check = "SELECT * FROM " . $this->table_pivot . " WHERE produto_id = ? AND fornecedor_id = ?";
        $stmtCheck = $this->conn->prepare($check);
        $stmtCheck->execute([$produto_id, $fornecedor_id]);
        
        if($stmtCheck->rowCount() > 0) return false;

        $query = "INSERT INTO " . $this->table_pivot . " (produto_id, fornecedor_id) VALUES (:produto_id, :fornecedor_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':produto_id', $produto_id, PDO::PARAM_INT);
        $stmt->bindParam(':fornecedor_id', $fornecedor_id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function removerVinculosEmMassa($produto_id, $fornecedores_ids) {
        $inQuery = implode(',', array_fill(0, count($fornecedores_ids), '?'));
        $query = "DELETE FROM " . $this->table_pivot . " WHERE produto_id = ? AND fornecedor_id IN (" . $inQuery . ")";
        $stmt = $this->conn->prepare($query);
        $params = array_merge([$produto_id], $fornecedores_ids);
        return $stmt->execute($params);
    }
}
?>