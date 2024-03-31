<?php
    session_start();

    // Verificar se o usuário tem acesso ao validador de voos (ou ao que você deseja permitir)
    $is_admin = ($_SESSION["access_administration_panel"] === '1' || $_SESSION["access_administration_panel"] === '1');

    // Verificar a conexão com o banco de dados
    $db_host = 'localhost';
	$db_database = 'priva674_2023';
	$db_username = 'priva674_2023';
	$db_password = 'OnlyPrivate2023@';

    $db = new mysqli($db_host, $db_username, $db_password, $db_database);

    if ($db->connect_errno > 0) {
        die('Unable to connect to the database [' . $db->connect_error . ']');
    }

    // Inicialize as variáveis para armazenar os detalhes do curso
    $course_id = $course_name = $course_description = $course_content = $course_start_date = $course_end_date = '';

    if (isset($_GET['course_id']) && is_numeric($_GET['course_id'])) {
        // Obtenha o ID do curso da URL
        $course_id = $_GET['course_id'];

        // Consulta SQL para obter os detalhes do curso selecionado
        $sql = "SELECT course_id, name, description, content, start_date, end_date FROM courses WHERE course_id = $course_id";

        // Executar a consulta SQL
        if ($result = $db->query($sql)) {
            // Se o curso for encontrado, carregue seus detalhes
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $course_name = $row['name'];
                $course_description = $row['description'];
                $course_content = $row['content'];
                $course_start_date = $row['start_date'];
                $course_end_date = $row['end_date'];
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Curso</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            background-color: #ffffff;
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-submit {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-submit:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .success-message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Editar Curso</h1>

    <div class="container">
        <?php if ($is_admin && !empty($course_name)) { ?>
        <h2>Detalhes do Curso</h2>

        <form method="POST" action="./index_vam_op.php?page=process_edit_course">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <label for="name">Nome do Curso:</label>
            <input type="text" name="name" id="name" value="<?php echo $course_name; ?>" required>
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" required><?php echo $course_description; ?></textarea>
            <label for="content">Conteúdo:</label>
            <textarea name="content" id="content" required><?php echo $course_content; ?></textarea>
            <label for="start_date">Data de início:</label>
            <input type="date" name="start_date" id="start_date" value="<?php echo $course_start_date; ?>" required>
            <label for="end_date">Data de término:</label>
            <input type="date" name="end_date" id="end_date" value="<?php echo $course_end_date; ?>" required>
            <input type="submit" class="btn-submit" value="Salvar Alterações">
        </form>
        <?php } else { ?>
            <p class="error-message">Você não tem permissão para acessar esta página ou o curso selecionado não existe.</p>
        <?php } ?>
    </div>
</body>
</html>
