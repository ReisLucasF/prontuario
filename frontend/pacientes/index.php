<?php
require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Obtém a chave da API do ambiente
$apiKey = $_ENV['API_KEY'];

include '../partials/header.php';
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://localhost:3001/pacientes");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "x-api-key: $apiKey"
));

$resposta = curl_exec($ch);

curl_close($ch);

$pacientes = json_decode($resposta, true);

if (!$pacientes || curl_errno($ch)) {
    // die('Erro ao acessar a API: ' . curl_error($ch));
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
    />
    <script src="https://code.jquery.com/jquery-4.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <link rel="stylesheet" href="../style.css">
  </head>
  <body>
   
    <?php
    require './modals/tabelaModal.php'
    ?>

    <?php
    require './modals/visualizarPacienteModal.php'
    ?>

    <?php
    require './modals/editarPacienteModal.php'
    ?>

    <?php
    require './modals/adicionarPacienteModal.php'
    ?>

    <script>
     $(document).ready(function() {
      $('.editarPacienteBtn').on('click', function() {
          var nome = $(this).data('nome');
          var id = $(this).data('id');
          var email = $(this).data('email');
          var telefone = $(this).data('telefone');
          var nomeMae = $(this).data('nome-mae');
          var dataNascimento = $(this).data('data-nascimento');
          var sexo = $(this).data('sexo');
          var sus = $(this).data('sus');
          var pec = $(this).data('pec');
          var logradouro = $(this).data('logradouro');
          var bairro = $(this).data('bairro');
          var numero = $(this).data('numero');
          var cidade = $(this).data('cidade');
          var estado = $(this).data('estado');
          var acs = $(this).data('acs');
          var alergias = $(this).data('alergias');
          var comorbidades = $(this).data('comorbidades');
          var etnia = $(this).data('etnia');
          var estadoCivil = $(this).data('estado-civil');
          var nacionalidade = $(this).data('nacionalidade');
          var profissao = $(this).data('profissao');

          $('#pacienteNome').val(nome);
          $('#pacienteEmail').val(email);
          $('#pacienteTelefone').val(telefone);
          $('#pacienteId').val(id);
          $('#pacienteNomeMae').val(nomeMae);
          $('#pacienteDataNascimento').val(dataNascimento);
          $('#pacienteSexo').val(sexo);
          $('#pacienteSUS').val(sus);
          $('#pacientePEC').val(pec);
          $('#pacienteLogradouro').val(logradouro);
          $('#pacienteBairro').val(bairro);
          $('#pacienteNumero').val(numero);
          $('#pacienteCidade').val(cidade);
          $('#pacienteEstado').val(estado);
          $('#pacienteACS').val(acs);
          $('#pacienteAlergias').val(alergias);
          $('#pacienteComorbidades').val(comorbidades);
          $('#pacienteEtnia').val(etnia);
          $('#pacienteEstadoCivil').val(estadoCivil);
          $('#pacienteNacionalidade').val(nacionalidade);
          $('#pacienteProfissao').val(profissao);
      });

      $('#adicionarPacienteForm').submit(function(event) {
            var formData = $(this).serializeArray(); // Obtém os dados do formulário como um array

            var jsonData = {};
            // Converta o array de dados em um objeto JavaScript
            $.each(formData, function() {
                jsonData[this.name] = this.value;
            });

            console.log('Dados enviados para a API:', jsonData); // Registra os dados enviados no console

            $.ajax({
                type: 'POST',
                url: 'http://localhost:3001/pacientes/criar',
                headers: {
                    'x-api-key': '<?php echo $apiKey; ?>'
                },
                contentType: 'application/json', // Define o tipo de conteúdo como JSON
                data: JSON.stringify(jsonData), // Converte os dados para JSON
                success: function(response) {
                    console.log('Paciente criado com sucesso:', response);
                    // Limpar o formulário ou fazer outras ações, se necessário
                    $('#adicionarPacienteModal').modal('hide'); // Fecha o modal após o sucesso
                    location.reload(); // Recarrega a página para exibir o novo paciente adicionado
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao criar paciente:', error);
                    // Exibir mensagem de erro ao usuário ou outras ações, se necessário
                }
            });
        });



      function excluirPaciente() {
          var pacienteId = $('#pacienteId').val();

          $.ajax({
              url: `http://localhost:3001/pacientes/excluir/${pacienteId}`,
              type: 'DELETE',
              success: function(result) {
                  location.reload();
              },
              error: function(error) {
                  console.error('Erro ao excluir o paciente:', error);
              }
          });
      }

      $('#excluirpaciente').on('click', function() {
              excluirPaciente();
      });



function atualizarPaciente(id, dados) {
    console.log('JSON enviado pelo frontend:', dados); 
    $.ajax({
        url: 'http://localhost:3001/pacientes/' + id,
        type: 'PUT',
        contentType: 'application/json',
        data: JSON.stringify(dados),
        success: function(result) {
            console.log('Paciente atualizado com sucesso.', result);
            location.reload();
        },
        error: function(error) {
            console.error('Erro ao atualizar o paciente:', error);
        }
    });
}

$('#editarPacienteForm').on('submit', function(event) {
    event.preventDefault();
    var pacienteId = $('#pacienteId').val(); 
    var dados = $(this).serializeArray(); // Obtenha os dados serializados como array
    var jsonData = {};
    // Converta o array de dados em um objeto JavaScript
    $.each(dados, function() {
        jsonData[this.name] = this.value;
    });
    atualizarPaciente(pacienteId, jsonData); // Chama a função para atualizar o paciente
});

$(document).ready(function() {
    $('.visualizarPacienteBtn').click(function() {
        var id = $(this).data('id');
        var nome = $(this).data('nome');
        var dataNascimento = $(this).data('data-nascimento');
        var sexo = $(this).data('sexo');
        var sus = $(this).data('sus');
        var pec = $(this).data('pec');
        var logradouro = $(this).data('logradouro');
        var bairro = $(this).data('bairro');
        var numero = $(this).data('numero');
        var cidade = $(this).data('cidade');
        var estado = $(this).data('estado');
        var acs = $(this).data('acs');
        var nomeMae = $(this).data('nome-mae');
        var alergias = $(this).data('alergias');
        var comorbidades = $(this).data('comorbidades');
        var telefone = $(this).data('telefone');
        var email = $(this).data('email');
        var etnia = $(this).data('etnia');
        var estadoCivil = $(this).data('estado-civil');
        var nacionalidade = $(this).data('nacionalidade');
        var profissao = $(this).data('profissao');

        $('#id').text(id);
        $('#visualizarNome').text(nome);
        $('#visualizarDataNascimento').text(dataNascimento);
        $('#visualizarSexo').text(sexo);
        $('#visualizarSUS').text(sus);
        $('#visualizarPEC').text(pec);
        $('#visualizarLogradouro').text(logradouro);
        $('#visualizarBairro').text(bairro);
        $('#visualizarNumero').text(numero);
        $('#visualizarCidade').text(cidade);
        $('#visualizarEstado').text(estado);
        $('#visualizarACS').text(acs);
        $('#visualizarNomeMae').text(nomeMae);
        $('#visualizarAlergias').text(alergias);
        $('#visualizarComorbidades').text(comorbidades);
        $('#visualizarTelefone').text(telefone);
        $('#visualizarEmail').text(email);
        $('#visualizarEtnia').text(etnia);
        $('#visualizarEstadoCivil').text(estadoCivil);
        $('#visualizarNacionalidade').text(nacionalidade);
        $('#visualizarProfissao').text(profissao);
    });
});


 $(document).ready(function() {
        $('#filtroNome').on('input', function() {
            var filtroNome = $(this).val().toLowerCase();
            $('tbody tr').each(function() {
                var nomepaciente = $(this).find('td:nth-child(1)').text().toLowerCase();
                var sus = $(this).find('td:nth-child(2)').text().toLowerCase();
                if (nomepaciente.includes(filtroNome) || sus.includes(filtroNome)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
 
    });
    </script>


  </body>
</html>
