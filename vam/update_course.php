<?php
	// Verificar a conexão com o banco de dados
	if ($db->connect_errno > 0) {
		die('Unable to connect to the database [' . $db->connect_error . ']');
	}

	session_start('check_login.php');

	// Verificar se o usuário tem acesso ao validador de voos
	if ($_SESSION["access_administration_panel"] === '0' || $_SESSION["access_administration_panel"] === '0') {

		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			// Obtém os valores enviados pelo formulário de edição
			$course_id = $_POST["course_id"];
			$name = $_POST["name"];
			$description = $_POST["description"];
			$content = $_POST["content"];
			$start_date = $_POST["start_date"];
			$end_date = $_POST["end_date"];

			// Atualiza os dados do curso no banco de dados
			$sql = "UPDATE courses SET name='$name', description='$description', content='$content', start_date='$start_date', end_date='$end_date' WHERE course_id='$course_id'";

			if ($db->query($sql)) {
				// Redireciona para a página de visualização de cursos após a atualização
				header("Location: curso_page.php");
				exit();
			} else {
				// Exibe uma mensagem de erro caso ocorra um problema na atualização
				echo "Ocorreu um erro ao atualizar o curso. Por favor, tente novamente.";
			}
		} else {
			// Redireciona para a página de visualização de cursos caso não seja uma requisição POST
			header("Location: curso_page.php");
			exit();
		}
	} else {
		echo "Você não tem permissão para acessar esta página.";
	}
?>
