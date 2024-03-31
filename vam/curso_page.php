<?php
    session_start();

    // Verificar se o usuário tem acesso ao validador de voos (ou ao que você deseja permitir)
    $is_admin = ($_SESSION["access_administration_panel"] === '1' || $_SESSION["access_administration_panel"] === '1');

    // Verificar a conexão com o banco de dados
    if ($db->connect_errno > 0) {
        die('Unable to connect to the database [' . $db->connect_error . ']');
    }

    include('review_pilot_rank.php');

    // Consulta SQL para obter os dados da tabela courses
    $sql = "SELECT course_id, name, description, content, start_date, end_date, callsign FROM courses";

    // Executar a consulta SQL
    if (!$result = $db->query($sql)) {
        die('There was an error running the query [' . $db->error . ']');
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cursos</title>
    <!-- Seus estilos CSS e scripts adicionais podem ser incluídos aqui -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        h1 {
            background-color: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-edit, .btn-delete {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
            cursor: pointer;
        }

        .btn-edit {
            background-color: #4CAF50;
            color: white;
        }

        .btn-delete {
            background-color: #f44336;
            color: white;
        }

        .btn-edit:hover, .btn-delete:hover {
            background-color: #45a049;
        }

        .actions {
            text-align: center;
        }

        .add-course-form {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
        }

        .add-course-form label, .add-course-form input, .add-course-form textarea {
            display: block;
            width: 100%;
            margin-top: 10px;
        }

        .add-course-form input[type="submit"] {
            background-color: #008CBA;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .add-course-form input[type="submit"]:hover {
            background-color: #005F7E;
        }
    </style>
</head>
<body>
    <h1>Lista de Cursos</h1>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Descrição</th>
                    <th>Conteúdo</th>
                    <th>Data de Início</th>
                    <th>Data de Término</th>
                    <th>Participantes</th>
                    <th class="actions">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td><?php echo $row['content']; ?></td>
                    <td><?php echo $row['start_date']; ?></td>
                    <td><?php echo $row['end_date']; ?></td>
                    <td><?php echo $row['callsign']; ?></td>
                    <td class="actions">
                        <a href="./index_vam_op.php?page=register_course&course_id=<?php echo $row['course_id']; ?>" class="btn-register">Inscrever-se</a>
                        <?php if ($is_admin) { ?>
                        <a href="./index_vam_op.php?page=edit_course&course_id=<?php echo $row['course_id']; ?>" class="btn-edit">Editar</a>
                        <a href="./index_vam_op.php?page=delete_course&course_id=<?php echo $row['course_id']; ?>" class="btn-delete">Apagar</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <?php if ($is_admin) { ?>
    <div class="add-course-form">
        <h2>Adicionar Novo Curso</h2>
        <form method="POST" action="./index_vam_op.php?page=process_curso_form">
            <label for="name">Nome do Curso:</label>
            <input type="text" name="name" id="name" required>
            <label for="description">Descrição:</label>
            <textarea name="description" id="description" required></textarea>
            <label for="content">Conteúdo:</label>
            <textarea name="content" id="content" required></textarea>
            <label for="start_date">Data de Início:</label>
            <input type="date" name="start_date" id="start_date" required>
            <label for="end_date">Data de Término:</label>
            <input type="date" name="end_date" id="end_date" required>
            <input type="submit" value="Adicionar">
        </form>
    </div>
    <?php } ?>
</body>
</html>
