<?php
header('Content-Type: application/json'); // Garante que a resposta seja JSON
require_once '../config/database.php';
require_once '../models/Produto.php';

$database = new Database();
$db = $database->getConnection();
$produto = new Produto($db);

// Captura a ação vinda do AJAX (GET ou POST)
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

switch($action) {
    case 'listar':
        $produto_id = $_GET['produto_id'];
        $dados = $produto->getFornecedoresVinculados($produto_id);
        echo json_encode(['sucesso' => true, 'dados' => $dados]);
        break;

    case 'vincular':
        $produto_id = $_POST['produto_id'];
        $fornecedor_id = $_POST['fornecedor_id'];
        
        if($produto->vincularFornecedor($produto_id, $fornecedor_id)) {
            echo json_encode(['sucesso' => true, 'mensagem' => 'Vínculo criado!']);
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao vincular ou vínculo já existe.']);
        }
        break;

    case 'remover_massa':
        $produto_id = $_POST['produto_id'];
        // Recebe o array de IDs enviados pelo jQuery
        $fornecedores_ids = isset($_POST['fornecedores_ids']) ? $_POST['fornecedores_ids'] : [];
        
        if(!empty($fornecedores_ids)) {
            if($produto->removerVinculosEmMassa($produto_id, $fornecedores_ids)) {
                echo json_encode(['sucesso' => true, 'mensagem' => 'Vínculos removidos!']);
            } else {
                echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao remover no banco.']);
            }
        } else {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhum fornecedor selecionado.']);
        }
        break;

    default:
        echo json_encode(['sucesso' => false, 'mensagem' => 'Ação inválida.']);
}
?>