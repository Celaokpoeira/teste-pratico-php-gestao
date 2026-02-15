<?php
require_once '../config/database.php';
require_once '../models/Produto.php';

$database = new Database();
$db = $database->getConnection();
$produto = new Produto($db);

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $action == 'cadastrar') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $codigo_interno = $_POST['codigo_interno'];
    $status = $_POST['status'];

    if(!empty($nome) && !empty($codigo_interno)) {
        if($produto->cadastrar($nome, $descricao, $codigo_interno, $status)) {
            header("Location: ../views/produtos.php?sucesso=1");
        } else {
            // Geralmente cai aqui se o código interno for duplicado
            header("Location: ../views/produtos.php?erro=1");
        }
    } else {
         header("Location: ../views/produtos.php?erro=campos_vazios");
    }
    exit();
}
?>