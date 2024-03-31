<?php
// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém os valores enviados pelo formulário
    
    $name = $_POST['name'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    

    // Realize as validações necessárias antes de inserir os dados no banco de dados

    // Realize a conexão com o banco de dados
    $db_host = 'localhost';
$db_database = 'priva674_2023';
$db_username = 'priva674_2023';
$db_password = 'OnlyPrivate2023@';

    $db = new mysqli($db_host, $db_username, $db_password, $db_database);
    $db->set_charset("utf8");

    if ($db->connect_errno > 0) {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    // Prepare a consulta SQL para inserir o novo treinamento
    $sql = "INSERT INTO courses (name, description, content, start_date, end_date)
            VALUES ('$name', '$description', '$content', '$startDate', '$endDate')";

    // Executa a consulta SQL
    if ($db->query($sql) === true) {
        echo "O treinamento foi inserido com sucesso.";
    } else {
        echo "Houve um erro ao inserir o treinamento: " . $db->error;
    }

    // Fecha a conexão com o banco de dados
    $db->close();
} else {
    echo "O formulário não foi enviado corretamente.";
}
