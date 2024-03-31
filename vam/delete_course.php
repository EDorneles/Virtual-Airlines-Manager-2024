<?php
session_start();

// Verificar se o usuário tem acesso à exclusão de cursos (ou ao que você deseja permitir)
$is_admin = ($_SESSION["access_administration_panel"] === '1' || $_SESSION["access_administration_panel"] === '1');

// Verificar a conexão com o banco de dados
include('config.php'); // Certifique-se de que o arquivo de configuração esteja incluído aqui

if ($db->connect_errno > 0) {
    die('Erro na conexão com o banco de dados [' . $db->connect_error . ']');
}

// Verificar se o parâmetro 'course_id' foi fornecido na URL
if (isset($_GET['course_id']) && $is_admin) {
    // Obter o ID do curso a ser excluído
    $course_id = $_GET['course_id'];

    // Preparar a consulta SQL para excluir o curso
    $delete_query = "DELETE FROM courses WHERE course_id = $course_id";

    // Executar a consulta SQL
    if ($db->query($delete_query)) {
        $message = "Curso excluído com sucesso!";
    } else {
        $error_message = "Erro ao excluir o curso: " . $db->error;
    }
} else {
    $error_message = "Você não tem permissão para excluir este curso ou o parâmetro 'course_id' não foi fornecido na URL.";
}

// Redirecionar de volta à página de cursos (curso_page) com uma mensagem
$_SESSION['message'] = isset($message) ? $message : $error_message;
header("Location: curso_page.php");
exit;
?>
