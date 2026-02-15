<?php 
require_once '../config/database.php';
require_once '../models/Produto.php';

$database = new Database();
$db = $database->getConnection();
$produto = new Produto($db);
$stmt = $produto->listar();

include 'layout_header.php'; 
?>

<h2>Gerenciar Produtos</h2>

<?php if(isset($_GET['sucesso'])): ?>
    <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px;">Produto cadastrado com sucesso!</div>
<?php elseif(isset($_GET['erro'])): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px;">Erro ao cadastrar. Verifique se o Código Interno já existe ou se preencheu tudo corretamente.</div>
<?php endif; ?>

<div style="background: #f8f9fa; padding: 15px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px;">
    <h3>Novo Produto</h3>
    <form action="../controllers/ProdutoController.php?action=cadastrar" method="POST">
        <div class="form-group">
            <label>Nome do Produto:</label>
            <input type="text" name="nome" required>
        </div>
        <div class="form-group">
            <label>Código Interno (SKU):</label>
            <input type="text" name="codigo_interno" required>
        </div>
        <div class="form-group">
            <label>Descrição:</label>
            <textarea name="descricao" rows="3" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;"></textarea>
        </div>
        <div class="form-group">
            <label>Status:</label>
            <select name="status">
                <option value="Ativo">Ativo</option>
                <option value="Inativo">Inativo</option>
            </select>
        </div>
        <button type="submit" class="btn">Salvar Produto</button>
    </form>
</div>

<h3>Lista de Produtos</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Código</th>
            <th>Nome</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['codigo_interno']); ?></td>
                <td><?php echo htmlspecialchars($row['nome']); ?></td>
                <td><?php echo $row['status']; ?></td>
                <td>
                    <a href="vinculos.php?produto_id=<?php echo $row['id']; ?>" class="btn" style="text-decoration: none; font-size: 14px;">Gerenciar Vínculos</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'layout_footer.php'; ?>