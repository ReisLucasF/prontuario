<?php
include '../conexao.php';
$conexao = obterConexao();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';

    if (empty($nome) || empty($email) || empty($telefone)) {
        header('Location: lista_clientes.php?erro=DadosInvalidos');
        exit();
    }

    $sql = "INSERT INTO clientes (nome, email, telefone) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);

    mysqli_stmt_bind_param($stmt, 'sss', $nome, $email, $telefone);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: clientes.php');
        exit();
    } else {
        header('Location: lista_clientes.php?erro=ErroAoInserir');
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
} else {
    header('Location: lista_clientes.php?erro=MetodoInvalido');
}
?>
