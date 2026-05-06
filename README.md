função nem todas estão sendo chamadas e nem o javascript

controllers:
- mudarSenha()
-repositorio($id, $tipo)
-MedicamentoUso()
-mostrarMedicamentoUso($id)

models:
-getinformacaoUsuario($pdo, $cpf)
-updateUsuario($pdo, $id, $senha)
-getRepositorio($pdo, $id, $tipo)
-getMedicamentoMedico($pdo, $idPaciente)
- setMedicamentoUso($pdo, $nome, $dosagem, $frequencia,$dataInicio, $dataFim, $observacao, $medicoId, $pacienteId)
-getInformacaoMedicamentoUso($pdo, $id)

componentes:
-mensagemSucesso()
-showRepositorio()
-showInformacaoMedicamentoUso()



getReceitasMedicas($pdo, $id) foi alterada 
getMedicamentoPaciente($pdo, $idPaciente) foi alterada
medicamento($id, $tipo) foi alterada
medicamento_uso.php foi alterada
showMedicamento() foi alterada
getPacienteMedicos($pdo, $idMedico) foi alterada
getBusca($pdo, $item) foi alterada

pagina medicamento uso.php 
pagina busca.php e pgMedico.php foi alterada


busca() falta estilo para médio o sintomas e a teg <a>


{
    criar_novo: o js que tinha não funciona, estilização não ta bom
    paciente: faltou o medio
}