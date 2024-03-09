<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $conexao = obterConexao();
    $sql = "SELECT * FROM pacientes WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && $paciente = mysqli_fetch_assoc($result)) {
        echo json_encode($paciente);
    } else {
        http_response_code(404);
        echo json_encode(array("mensagem" => "Paciente não encontrado."));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
} else {
    http_response_code(400);
    echo json_encode(array("mensagem" => "ID do paciente não fornecido."));
}
?>
