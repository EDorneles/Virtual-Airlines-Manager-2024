<?php
session_start();

require('check_login.php');

// Verifique se o usuário está logado
if (is_logged()) {
    $db_host = 'localhost';
    $db_database = 'priva674_2023';
    $db_username = 'priva674_2023';
    $db_password = 'OnlyPrivate2023@';

    $db = new mysqli($db_host, $db_username, $db_password, $db_database);
    $db->set_charset("utf8");
    $route = '';

    if ($db->connect_errno > 0) {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    // Obtenha o gvauser_id do usuário logado
    $id = $_SESSION["gvauser_id"];

    // Verifique se $callsign está definido
    if (isset($callsign)) {
        // Use consultas preparadas para evitar problemas de segurança
        $sql = "SELECT * FROM gvausers WHERE callsign = ?";
        if ($stmt = $db->prepare($sql)) {
            $stmt->bind_param("s", $callsign); // "s" indica uma string
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $callsign = $row['callsign'];
            } else {
                // O callsign não foi encontrado na tabela gvausers
                echo "Callsign não encontrado na tabela gvausers.";
                exit();
            }
            
            $stmt->close();
        } else {
            // Erro na preparação da consulta
            echo "Erro na consulta preparada: " . $db->error;
            exit();
        }
    } else {
        // callsign não está definido
        echo "Callsign não definido.";
        exit();
    }

    // Verifique se o curso_id foi fornecido na URL
    if (isset($_GET["course_id"])) {
        $course_id = $_GET["course_id"];

        // Verifique se $id e $course_id estão definidos
        if (isset($id) && isset($course_id)) {
            // Verifique se o piloto já está inscrito no curso
            $check_query = "SELECT * FROM courses WHERE course_id = ? AND user_id = ?";
            if ($stmt = $db->prepare($check_query)) {
                $stmt->bind_param("ss", $course_id, $id);
                $stmt->execute();
                $check_result = $stmt->get_result();

                if ($check_result->num_rows == 0) {
                    // O piloto não está inscrito no curso, então inscreva-o
                    $insert_query = "INSERT INTO courses (course_id, user_id) VALUES (?, ?)";
                    if ($stmt = $db->prepare($insert_query)) {
                        $stmt->bind_param("ss", $course_id, $id);
                        if ($stmt->execute()) {
                            // Inserção bem-sucedida
                            header("Location: curso_page.php"); // Redirecione de volta para a página de cursos
                            exit();
                        } else {
                            // Erro na inserção
                            echo "Erro ao inscrever o piloto no curso: " . $stmt->error;
                        }
                    } else {
                        // Erro na preparação da consulta de inserção
                        echo "Erro na consulta preparada de inserção: " . $db->error;
                        exit();
                    }
                } else {
                    // O piloto já está inscrito no curso
                    echo "Você já está inscrito neste curso.";
                }
            } else {
                // Erro na preparação da consulta de verificação
                echo "Erro na consulta preparada de verificação: " . $db->error;
                exit();
            }
        } else {
            // $id ou $course_id não estão definidos
            echo "ID do usuário ou do curso não definido.";
            exit();
        }
    } else {
        // course_id não foi fornecido na URL
        echo "ID do curso não fornecido.";
        exit();
    }

    // Feche a conexão com o banco de dados
    $db->close();
} else {
    // O usuário não está logado, redirecione para a página de login
    header("Location: login.php");
    exit();
}
?>
