<?php
// Configurações do banco de dados
$db_host = 'localhost';
$db_database = 'priva674_2023';
$db_username = 'priva674_2023';
$db_password = 'OnlyPrivate2023@';

// Conexão com o banco de dados
$conn = new mysqli($db_host, $db_username, $db_password, $db_database);

// Verificando se a conexão foi bem-sucedida
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Dados do novo curso a ser adicionado
$course_id = "C001";
$name = "Introdução à programação";
$description = "Um curso introdutório sobre programação";
$content = "O conteúdo do curso aqui";
$start_date = "2023-06-01";
$end_date = "2023-07-31";

// Preparando e executando a consulta SQL para adicionar um novo curso à tabela "courses"
$stmt = $conn->prepare("INSERT INTO courses (course_id, name, description, content, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $course_id, $name, $description, $content, $start_date, $end_date);

if ($stmt->execute() === TRUE) {
    echo "Novo curso adicionado com sucesso!";
} else {
    echo "Erro ao adicionar curso: " . $conn->error;
}

// Fechando a conexão com o banco de dados
$conn->close();



?>