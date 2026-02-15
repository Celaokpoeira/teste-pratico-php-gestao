<?php 
require_once '../config/database.php';
require_once '../models/Fornecedor.php';

$database = new Database();
$db = $database->getConnection();
$fornecedor = new Fornecedor($db);

// Pega o ID da URL e busca os dados
$id = isset($_GET['id']) ? $_GET['id'] : die('ID não informado.');
$dados = $fornecedor->buscarPorId($id);

include 'layout_header.php'; 
?>

<h2>Editar Fornecedor</h2>

<div style="background: #f8f9fa; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
    <form action="../controllers/FornecedorController.php?action=editar" method="POST">
        <input type="hidden" name="id" value="<?php echo $dados['id']; ?>">
        
        <div class="form-group">
            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($dados['nome']); ?>" required>
        </div>
        <div class="form-group">
            <label>CNPJ:</label>
            <input type="text" name="cnpj" value="<?php echo htmlspecialchars($dados['cnpj']); ?>" required>
        </div>
        <div class="form-group">
            <label>E-mail:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($dados['email']); ?>" required>
        </div>
        <div class="form-group">
            <label>Telefone:</label>
            <input type="text" name="telefone" value="<?php echo htmlspecialchars($dados['telefone']); ?>">
        </div>
        <div class="form-group">
            <label>Status:</label>
            <select name="status">
                <option value="Ativo" <?php if($dados['status'] == 'Ativo') echo 'selected'; ?>>Ativo</option>
                <option value="Inativo" <?php if($dados['status'] == 'Inativo') echo 'selected'; ?>>Inativo</option>
            </select>
        </div>
        <button type="submit" class="btn">Atualizar Fornecedor</button>
        <a href="fornecedores.php" class="btn btn-danger" style="text-decoration:none;">Cancelar</a>
    </form>
</div>

<?php include 'layout_footer.php'; ?>