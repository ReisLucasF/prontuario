<?php
include '../partials/header.php';
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "http://localhost:3001/pacientes");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);

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


  </head>

  <style>
    body {
      height: 100vh;
    }
    section {
      display: grid;
      grid-template-rows: repeat(2, 1fr);
      grid-template-columns: repeat(2, 1fr);
      height: 80vh;
      margin: 0;
      gap: 15px;
    }

    main {
      margin: 20px 5%;
    }

    nav {
      margin-bottom: 25px;
      top: 0;
    }

    #calendar{
      margin: 20px 50px;
      height: 70%;
    }

    tbody tr{
      height: 25px;
    }
  </style>

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

    <!-- Modal de Edição de paciente -->
    <div class="modal fade" id="editarPacienteModal" tabindex="-1" role="dialog" aria-labelledby="editarPacienteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarpacienteModalLabel">Editar Paciente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editarPacienteForm" method="POST" action="atualizar_paciente.php">
                        <div class="form-group">
                            <label for="pacienteNome">Nome</label>
                            <input type="text" class="form-control" id="pacienteNome" name="nome">
                        </div>
                        <div class="form-group">
                            <label for="pacienteEmail">Email</label>
                            <input type="email" class="form-control" id="pacienteEmail" name="email">
                        </div>
                        <div class="form-group">
                            <label for="pacienteTelefone">Telefone</label>
                            <input type="text" class="form-control" id="pacienteTelefone" name="telefone">
                        </div>
                        <input type="hidden" id="pacienteId" name="id">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-danger" id="excluirpaciente">Excluir paciente</button>
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

    // $('#filtroTexto').on('input', function() {
    //     var filtroTexto = $(this).val().toLowerCase();
    //     $('#tabelaEstoque tr').each(function() {
    //         var nome = $(this).find('td:nth-child(1)').text().toLowerCase();
    //         var codigo = $(this).find('td:nth-child(2)').text().toLowerCase();
    //         if (nome.includes(filtroTexto) || codigo.includes(filtroTexto)) {
    //             $(this).show();
    //         } else {
    //             $(this).hide();
    //         }
    //     });
    // });


    </script>


  </body>
</html>
