<?php
session_start();

// Verificar se o usuário tem acesso à edição de cursos (ou ao que você deseja permitir)
$is_admin = ($_SESSION["access_administration_panel"] === '1' || $_SESSION["access_administration_panel"] === '1');

// Verificar a conexão com o banco de dados
include('config.php'); // Certifique-se de que o arquivo de configuração esteja incluído aqui

if ($db->connect_errno > 0) {
    die('Erro na conexão com o banco de dados [' . $db->connect_error . ']');
}

// Verificar se o parâmetro 'course_id' foi fornecido na URL e se o usuário tem permissão de admin
if (isset($_POST['course_id'], $_POST['name'], $_POST['description'], $_POST['content'], $_POST['start_date'], $_POST['end_date']) && $is_admin) {
    // Obter os dados do formulário
    $course_id = $_POST['course_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Preparar a consulta SQL para editar o curso
    $update_query = "UPDATE courses SET name = '$name', description = '$description', content = '$content', start_date = '$start_date', end_date = '$end_date' WHERE course_id = $course_id";

    // Executar a consulta SQL
    if ($db->query($update_query)) {
        $message = "Curso atualizado com sucesso!";
    } else {
        $error_message = "Erro ao atualizar o curso: " . $db->error;
    }
} else {
    $error_message = "Você não tem permissão para editar este curso ou os parâmetros necessários não foram fornecidos.";
}

// Redirecionar de volta à página de cursos (curso_page) com uma mensagem
$_SESSION['message'] = isset($message) ? $message : $error_message;
header("Location: curso_page.php");
exit;
?>
