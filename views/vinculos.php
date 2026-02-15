<?php 
require_once '../config/database.php';
require_once '../models/Produto.php';

// Pega o ID do produto que veio pela URL
$produto_id = isset($_GET['produto_id']) ? intval($_GET['produto_id']) : 0;

include 'layout_header.php'; 
?>

<h2>Gerenciar Vínculos do Produto</h2>

<?php if($produto_id == 0): ?>
    <div style="background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; border: 1px solid #ffeeba;">
        <strong>Atenção:</strong> Você precisa selecionar um produto primeiro. 
        <br><br>
        <a href="produtos.php" class="btn" style="text-decoration: none;">Ir para Lista de Produtos</a>
    </div>
<?php else: ?>

    <input type="hidden" id="produto_id_atual" value="<?php echo $produto_id; ?>">

    <div class="feedback" id="msg-feedback" style="display:none; padding: 10px; margin-bottom: 15px; border-radius: 4px;"></div>

    <div style="background: #f8f9fa; padding: 15px; border: 1px solid #ddd; border-radius: 5px; margin-bottom: 20px;">
        <h3>Vincular Novo Fornecedor</h3>
        <p style="font-size: 14px; color: #666; margin-bottom: 10px;">Digite o ID do Fornecedor que deseja vincular a este produto.</p>
        
        <div style="display: flex; gap: 10px;">
            <input type="number" id="novo_fornecedor_id" placeholder="ID do Fornecedor" style="width: 150px; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            <button id="btn-vincular" class="btn" style="background-color: #007bff;">Adicionar Vínculo</button>
        </div>
    </div>

    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h3>Fornecedores Vinculados</h3>
        <button id="btn-remover-massa" class="btn btn-danger">Remover Selecionados em Massa</button>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;"><input type="checkbox" id="check-todos"></th>
                <th>ID</th>
                <th>Nome do Fornecedor</th>
                <th>CNPJ</th>
            </tr>
        </thead>
        <tbody id="tabela-vinculos-body">
            </tbody>
    </table>

<?php endif; ?>

<?php include 'layout_footer.php'; ?>