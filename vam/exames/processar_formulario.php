<?php
// primeiro, verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // verifique se todas as perguntas foram respondidas
  $perguntas = ['pergunta1', 'pergunta2', 'pergunta3', 'pergunta4', 'pergunta5',
                'pergunta6', 'pergunta7', 'pergunta8', 'pergunta9', 'pergunta10'];
  $faltando_respostas = false;
  foreach ($perguntas as $pergunta) {
    if (empty($_POST[$pergunta])) {
      $faltando_respostas = true;
      break;
    }
  }

  // se faltar alguma resposta, exiba uma mensagem de erro
  if ($faltando_respostas) {
    echo "<script>alert('Por favor, responda a todas as perguntas.');</script>";
  } else {
    // se todas as perguntas foram respondidas, processe o formulário
    // ...

    // corrija o formulário usando o arquivo "corrigir_formulario.php"
    include 'corrigir_formulario.php';
    // Verifica se o formulário foi enviado
if(isset($_POST)) {
    // Percorre todas as respostas enviadas pelo formulário
    foreach($_POST as $pergunta => $resposta) {
        // Verifica se a resposta está correta
        if(isset($respostas_corretas[$pergunta]) && $respostas_corretas[$pergunta] === $resposta) {
            $num_respostas_corretas++;
        }
    }

    // Mostra o número de respostas corretas
    echo "<div style='text-align: center;'>";
    echo "<h1>Resultado da avaliação:</h1>";
    echo "<p>Você acertou $num_respostas_corretas de 10 perguntas.</p>";
}
?>