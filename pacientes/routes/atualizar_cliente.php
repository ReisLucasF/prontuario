<?php
include '../conexao.php';
$conexao = obterConexao();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? null;
    $email = $_POST['email'] ?? null;
    $telefone = $_POST['telefone'] ?? null;

    if ($id && $nome && $email && $telefone) {
        $sql = "UPDATE clientes SET nome = ?, email = ?, telefone = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        
        mysqli_stmt_bind_param($stmt, 'sssi', $nome, $email, $telefone, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            header('Location: clientes.php');
            exit();
        } else {
            echo "Erro ao atualizar o cliente.";
        }
    } else {
        echo "Dados inválidos.";
    }
}
?>
