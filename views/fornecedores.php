<?php 
require_once '../config/database.php';
require_once '../models/Fornecedor.php';

$database = new Database();
$db = $database->getConnection();
$fornecedor = new Fornecedor($db);
$stmt = $fornecedor->listar();

include 'layout_header.php'; 
?>

<h2>Gerenciar Fornecedores</h2>

<?php if(isset($_GET['sucesso'])): ?>
    <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px;">Operação realizada com sucesso!</div>
<?php elseif(isset($_GET['erro'])): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px;">Ocorreu um erro na operação.</div>
<?php endif; ?>

<div style="background: #f8f9fa; padding: 15px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px;">
    <h3>Novo Fornecedor</h3>
    <form action="../controllers/FornecedorController.php?action=cadastrar" method="POST">
        <div class="form-group">
            <label>Nome:</label>
            <input type="text" name="nome" required>
        </div>
        <div class="form-group">
            <label>CNPJ:</label>
            <input type="text" name="cnpj" required>
        </div>
        <div class="form-group">
            <label>E-mail:</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Telefone:</label>
            <input type="text" name="telefone">
        </div>
        <div class="form-group">
            <label>Status:</label>
            <select name="status">
                <option value="Ativo">Ativo</option>
                <option value="Inativo">Inativo</option>
            </select>
        </div>
        <button type="submit" class="btn">Salvar Fornecedor</button>
    </form>
</div>

<h3>Lista de Fornecedores</h3>
input type="text" id="busca-tabela" placeholder="🔍 Buscar fornecedor por nome, CNPJ ou ID..." style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px;">
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>CNPJ</th>
            <th>Status</th>
            <th>Ações</th> </tr>
    </thead>
    <tbody>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                <td><?php echo htmlspecialchars($row['cnpj']); ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <a href="editar_fornecedor.php?id=<?php echo $row['id']; ?>" class="btn" style="text-decoration: none; font-size: 14px; background-color: #007bff;">Editar</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'layout_footer.php'; ?>