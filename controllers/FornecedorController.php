<?php
require_once '../config/database.php';
require_once '../models/Fornecedor.php';

$database = new Database();
$db = $database->getConnection();
$fornecedor = new Fornecedor($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == 'cadastrar') {
    $nome = $_POST['nome'];
    $cnpj = $_POST['cnpj'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $status = $_POST['status'];

    // Validação básica
    if(!empty($nome) && !empty($cnpj) && !empty($email)) {
        if($fornecedor->cadastrar($nome, $cnpj, $email, $telefone, $status)) {
            header("Location: ../views/fornecedores.php?sucesso=1");
        } else {
            header("Location: ../views/fornecedores.php?erro=1");
        }
    } else {
         header("Location: ../views/fornecedores.php?erro=campos_vazios");
    }
    exit();
}
?>