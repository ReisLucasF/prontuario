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
                              <td><?php echo !empty($atendimento['medico']['nome']) ? htmlspecialchars($atendimento['medico']['nome']) : 'Não informado'; ?></td>
                          </tr>
                          <tr>
                              <td><b>CRM:</b></td>
                              <td><?php echo !empty($atendimento['medico']['crm']) ? htmlspecialchars($atendimento['medico']['crm']) : 'Não informado'; ?></td>
                          </tr>
                      </table>
                  </div>
                  <div class="col">
                      <table class="table">
                          <h5>Dados do paciente</h5>
                          <tr>
                              <td><b>Nome:</b></td>
                              <td><?php echo !empty($atendimento['paciente']['nome']) ? htmlspecialchars($atendimento['paciente']['nome']) : 'Não informado'; ?></td>
                          </tr>
                          <tr>
                              <td><b>SUS:</b></td>
                              <td><?php echo !empty($atendimento['paciente']['sus']) ? htmlspecialchars($atendimento['paciente']['sus']) : 'Não informado'; ?></td>
                          </tr>
                          <tr>
                              <td><b>Sexo:</b></td>
                              <td><?php echo !empty($atendimento['paciente']['sexo']) ? htmlspecialchars($atendimento['paciente']['sexo']) : 'Não informado'; ?></td>
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
                      <td><?php echo !empty($atendimento['descricao']) ? htmlspecialchars($atendimento['descricao']) : 'Não informado'; ?></td>
                  </tr>
                </table>
              </div>
          </div>
        </div>
    </div>
</div>

<script>
    var logradouro = '<?php echo htmlspecialchars($atendimento['paciente']['logradouro']); ?>';
    var numero = '<?php echo htmlspecialchars($atendimento['paciente']['numero']); ?>';
    var bairro = '<?php echo htmlspecialchars($atendimento['paciente']['bairro']); ?>';
    var cidade = '<?php echo htmlspecialchars($atendimento['paciente']['cidade']); ?>';
    var estado = '<?php echo htmlspecialchars($atendimento['paciente']['estado']); ?>';

    var enderecoFormatado = logradouro + ', ' + numero + '<br>' + bairro + '<br>' + cidade + ' / ' + estado;

    document.getElementById('enderecoPaciente').innerHTML = enderecoFormatado;
</script>
