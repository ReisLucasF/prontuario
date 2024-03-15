<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Carrega as variáveis de ambiente
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Obtém a chave da API do ambiente
$apiKey = $_ENV['API_KEY'];

// Verifica se o parâmetro pacienteId está presente no URL
if (!isset($_GET['pacienteId'])) {
    // Se o pacienteId não estiver presente, redirecione o usuário para uma página de erro ou uma página padrão
    header('Location: erro.php');
    exit; // Encerre o script para evitar a execução adicional do código
}

// Extrai o ID do paciente do parâmetro pacienteId do URL
$pacienteId = $_GET['pacienteId'];

// Inicializa a sessão cURL
$ch = curl_init();

// Configura as opções da requisição cURL com o ID do paciente incluído na URL
curl_setopt($ch, CURLOPT_URL, "http://localhost:3001/atendimentos/paciente/$pacienteId");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["x-api-key: $apiKey"]);

// Executa a requisição e armazena a resposta
$resposta = curl_exec($ch);

// Fecha a sessão cURL
curl_close($ch);

// Decodifica a resposta JSON em um array associativo
$pacientes = json_decode($resposta, true);

// Inclui o cabeçalho da página
include '../partials/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Atendimentos</title>
  <link
  rel="stylesheet"
  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
  />
  <script src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</head>

<style>
  .modal-lg {
    max-width: 90%;
}
.descricao {
    text-align: center;
}

.descricao td {
    vertical-align: middle;
}
</style>

<body>
    <div class="container">
        <h1>Atendimentos do Paciente</h1>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Paciente</th>
                    <th scope="col">Médico</th>
                    <th scope="col">Data</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pacientes)): ?>
                    <?php foreach ($pacientes as $paciente): ?>
                        <tr>
                            <td><?php echo !empty($paciente['paciente']['nome']) ? htmlspecialchars($paciente['paciente']['nome']) : 'Não informado'; ?></td>
                            <td><?php echo !empty($paciente['medico']['nome']) ? htmlspecialchars($paciente['medico']['nome']) : 'Não informado'; ?></td>
                            <td><?php echo !empty($paciente['data']) ? htmlspecialchars(date('d/m/Y', strtotime($paciente['data']))) : 'Não informado'; ?></td>
                            <td><button class="btn btn-primary" onclick="visualizarAtendimento('<?php echo $paciente['_id']; ?>')">Visualizar</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">Nenhum atendimento encontrado para este paciente.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Adicione aqui seus scripts JavaScript, se necessário -->


    <!-- Modal de Visualização de Atendimento -->
<div class="modal fade" id="visualizarAtendimentoModal" tabindex="-1" role="dialog" aria-labelledby="visualizarAtendimentoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="visualizarAtendimentoModalLabel">Detalhes do Atendimento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                  <div class="col">
                      <table class="table">
                          <h5>Dados do médico</h5>
                          <tr>
                              <td><b>Nome:</b></td>
                              <td><?php echo !empty($paciente['medico']['nome']) ? htmlspecialchars($paciente['medico']['nome']) : 'Não informado'; ?></td>
                          </tr>
                          <tr>
                              <td><b>CRM:</b></td>
                              <td><?php echo !empty($paciente['medico']['crm']) ? htmlspecialchars($paciente['medico']['crm']) : 'Não informado'; ?></td>
                          </tr>
                      </table>
                  </div>
                  <div class="col">
                      <table class="table">
                          <h5>Dados do paciente</h5>
                          <tr>
                              <td><b>Nome:</b></td>
                              <td><?php echo !empty($paciente['paciente']['nome']) ? htmlspecialchars($paciente['paciente']['nome']) : 'Não informado'; ?></td>
                          </tr>
                          <tr>
                              <td><b>SUS:</b></td>
                              <td><?php echo !empty($paciente['paciente']['sus']) ? htmlspecialchars($paciente['paciente']['sus']) : 'Não informado'; ?></td>
                          </tr>
                          <tr>
                              <td><b>Sexo:</b></td>
                              <td><?php echo !empty($paciente['paciente']['sexo']) ? htmlspecialchars($paciente['paciente']['sexo']) : 'Não informado'; ?></td>
                          </tr>
                          <tr>
                              <td><b>Endereço:</b></td>
                              <td id="enderecoPaciente"></td>
                          </tr>                          
                      </table>
                  </div>
              </div>
              <div class="row descricao">
                <table class="table">
                  <tr>
                      <td colspan="2" style="text-align: center;"><b>Descrição do Atendimento</b></td>
                  </tr>
                  <tr>
                      <td><?php echo !empty($paciente['descricao']) ? htmlspecialchars($paciente['descricao']) : 'Não informado'; ?></td>
                  </tr>
                </table>
              </div>
          </div>
        </div>
    </div>
</div>

<script>

  function preencherEndereco() {
        var paciente = <?php echo json_encode($paciente['paciente']); ?>;
        var endereco = '';

        if (paciente['logradouro']) {
            endereco += paciente['logradouro'] + '<br>';
        }
        if (paciente['bairro']) {
            endereco += paciente['bairro'] + '<br>';
        }
        if (paciente['numero']) {
            endereco += paciente['numero'] + '<br>';
        }
        if (paciente['cidade']) {
            endereco += paciente['cidade'] + '<br>';
        }
        document.getElementById('enderecoPaciente').innerHTML = endereco;
    }

    // Chama a função quando a página carrega
    window.onload = preencherEndereco;

    function visualizarAtendimento(atendimentoId) {
    $.ajax({
        url: `http://localhost:3001/atendimentos/paciente/${atendimentoId}`,
        type: 'GET',
        headers: {
            'x-api-key': '<?php echo $apiKey; ?>'
        },
        success: function(data) {
            $('#detalhesAtendimento').html(data);
            console.log('Abrindo modal...');
            $('#visualizarAtendimentoModal').modal('show');
        },
        error: function(xhr, status, error) {
            $('#detalhesAtendimento').html(`<p>Erro ao carregar detalhes do atendimento: ${error}</p>`);
            console.log('Erro ao carregar detalhes do atendimento: ' + error); 
            $('#visualizarAtendimentoModal').modal('show');
        }
    });
}

</script>

</body>
</html>
