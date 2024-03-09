<?php
include '../conexao.php'; 
$conexao = obterConexao();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id'])) {
    $id = $_POST['id'];

    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: clientes.php');
        exit();
    } else {
        echo "Erro ao excluir o cliente.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
} else {
    header('Location: lista_clientes.php?erro=RequisicaoInvalida');
}
?>
