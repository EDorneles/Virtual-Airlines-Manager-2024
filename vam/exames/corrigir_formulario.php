<?php
// Array com as respostas corretas
$respostas_corretas = array(
    "pergunta1" => "b",
    "pergunta2" => "a",
    "pergunta3" => "b",
    "pergunta4" => "c",
    "pergunta5" => "b",
    "pergunta6" => "c",
    "pergunta7" => "a",
    "pergunta8" => "b",
    "pergunta9" => "b",
    "pergunta10" => "a"
);

// Inicializa o número de respostas corretas do usuário
$num_respostas_corretas = 0;

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
    echo "<p>Você acertou <h1>$num_respostas_corretas</h1>perguntas.</p>";
}
?>
<?php

if ($num_respostas_corretas >= 7) {
    $to = "recrutamento@privatevirtual.com.br";
    $cc = array("ceo@privatevirtual.com.br", "chro@privatevirtual.com.br", "cto@privatevirtual.com.br"); // Destinatários adicionais
    $subject = "Candidato aprovado no exame.";
    $message = "O candidato com $num_respostas_corretas respostas corretas passou no teste.";

    $headers = "From: staff@privatevirtual.com.br\r\n";
    $headers .= "Cc: " . implode(",", $cc); // Adicionando destinatários adicionais no header

    mail($to, $subject, $message, $headers);
}
?>


<?php

if ($num_respostas_corretas < 7) {
    echo "<script>alert('Infelizmente você não atingiu a nota mínima para passar no exame. Lamentamos pelo resultado.');</script>";
    echo "<button onclick='tentarNovamente()'>Tentar novamente</button>";
    echo "<script>function tentarNovamente() {history.back();}</script>";

}

?>


<?php


if ($num_respostas_corretas >= 7) {
    echo "<div style='text-align: center;'>";
    echo "<h1>Parabéns, você passou no exame!</h1>";
    echo "<p>Clique no botão abaixo para ser redirecionado para inscrição.</p>";
    echo "<button style='background-color: #4CAF50; border: none; color: white; padding: 15px 32px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; transition: background-color 0.3s ease;'><a href='http://www.privatevirtual.com.br/vam/index.php?page=pilot_register'>Inscreva-se</button>";
    echo "</div>";
}
?>
