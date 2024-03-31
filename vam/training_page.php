<?php
session_start();

// Configurações do banco de dados
$db_host = 'localhost';
$db_database = 'priva674_2023';
$db_username = 'priva674_2023';
$db_password = 'OnlyPrivate2023@';

// Conecte-se ao banco de dados
$db = new mysqli($db_host, $db_username, $db_password, $db_database);

if ($db->connect_error) {
    die('Erro na conexão com o banco de dados: ' . $db->connect_error);
}

// Verificar se o usuário tem acesso à administração de treinamentos
if ($_SESSION["access_administration_panel"] === '1') {
    // Função para marcar um treinamento como concluído
    if (isset($_POST['mark_completed'])) {
        $training_id = $_POST['training_id'];
        $update_query = "UPDATE trainings SET completed = 1 WHERE training_id = $training_id";
        if ($db->query($update_query)) {
            $message = "Treinamento marcado como concluído com sucesso!";
        } else {
            $error_message = "Erro ao marcar o treinamento como concluído: " . $db->error;
        }

        // Enviar o resultado da ação para o JavaScript
        echo "<script>showPopup('" . $message . "');</script>";
    }

    // Função para excluir um treinamento
    if (isset($_POST['delete'])) {
        $training_id = $_POST['training_id'];
        $delete_query = "DELETE FROM trainings WHERE training_id = $training_id";
        if ($db->query($delete_query)) {
            $message = "Treinamento excluído com sucesso!";
        } else {
            $error_message = "Erro ao excluir o treinamento: " . $db->error;
        }

        // Enviar o resultado da ação para o JavaScript
        echo "<script>showPopup('" . $message . "');</script>";
    }
}

// Agendamento de treinamento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pilot_id = $_POST['pilot_id'];
    $training_date = $_POST['training_date'];
    $training_time = $_POST['training_time'];
    $observations = $_POST['observations'];

    // Obter o endereço de e-mail do piloto
    $pilot_email_query = "SELECT email FROM gvausers WHERE gvauser_id = $pilot_id";
    $pilot_email_result = $db->query($pilot_email_query);

    $pilot_name_query = "SELECT name FROM gvausers WHERE gvauser_id = $pilot_id";
    $pilot_name_result = $db->query($pilot_name_query);

    if ($pilot_email_result->num_rows > 0) {
        $pilot_row = $pilot_email_result->fetch_assoc();
        $pilot_email = $pilot_row['email'];
        $pilot_name_row = $pilot_name_result->fetch_assoc();
        $pilot_name = $pilot_name_row['name'];

        // Montar o corpo do e-mail
        $to = $pilot_email;
        $subject = "Treinamento Private Virtual - PPT";
        $message = "Olá $pilot_name,\n\n";
        $message .= "Você tem um treinamento agendado com os seguintes detalhes:\n\n";
        $message .= "Data do Treinamento: $training_date\n";
        $message .= "Hora do Treinamento: $training_time\n";
        $message .= "Observações: $observations\n\n";
        $message .= "Para mais detalhes ou informações, nos procure em canais oficiais da PPT.\n\n";
        $message .= "Atenciosamente,\n";
        $message .= "Private Virtual - PPT\n";

        // Cabeçalhos para o e-mail
        $headers = "From: staff@privatevirtual.com.br"; // Substitua com o seu e-mail

        // Enviar o e-mail
        if (mail($to, $subject, $message, $headers)) {
            $message = "Treinamento agendado com sucesso! Um e-mail foi enviado para o piloto com os detalhes.";
        } else {
            $error_message = "Erro ao agendar o treinamento ou enviar o e-mail.";
        }
    } else {
        $error_message = "Piloto não encontrado.";
    }

    // Inserir o treinamento no banco de dados
    $insert_query = "INSERT INTO trainings (pilot_id, training_date, training_time, observations, completed) VALUES ('$pilot_id', '$training_date', '$training_time', '$observations', 0)";

    if ($db->query($insert_query)) {
        // A inserção no banco de dados foi bem-sucedida
        if (!isset($error_message)) {
            $message = "Treinamento agendado com sucesso! Um e-mail foi enviado para o piloto com os detalhes.";
        }
    } else {
        $error_message = "Erro ao agendar o treinamento ou enviar o e-mail: " . $db->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agendar Treinamento</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        select, input[type="date"], input[type="time"], textarea, input[type="submit"], button {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
        }

        button {
            background-color: #008CBA;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #005F7E;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        .actions {
            text-align: center;
        }

        .clock-icon {
            color: #ff6600; /* Cor laranja para o relógio */
        }

        .check-icon {
            color: #008000; /* Cor verde para a marca de verificação */
        }

        /* Estilos para o popup */
        #popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        #popup-message {
            font-size: 18px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    
<h1>Agendar Treinamento</h1>
<?php if (isset($message)) : ?>
    <p style="color: green;"><?php echo $message; ?></p>
<?php endif; ?>
<?php if (isset($error_message)) : ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>

<?php
if ($_SESSION["access_administration_panel"] === '1') {
    // Somente exibir o formulário para administradores
    ?>
    <form method="post" action="./index_vam_op.php?page=training_page">
        <label for="pilot_id">Piloto:</label>
        <select name="pilot_id" id="pilot_id">
            <?php
            $pilots_query = "SELECT gvauser_id, name, callsign FROM gvausers";
            $result = $db->query($pilots_query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['gvauser_id'] . "'>" . $row['name'] . " (" . $row['callsign'] . ")</option>";
                }
            }
            ?>
        </select>
        <label for="training_date">Data do Treinamento:</label>
        <input type="date" name="training_date" id="training_date">
        <label for="training_time">Hora do Treinamento:</label>
        <input type="time" name="training_time" id="training_time">
        <label for="observations">Observações:</label>
        <textarea name="observations" id="observations" rows="4"></textarea>
        <input type="submit" value="Agendar Treinamento">
    </form>
    <?php
} else {
    // Exibir o botão "Solicitar Treinamento" para pilotos sem acesso à administração
    ?>
    <a href="./index_vam_op.php?page=solicitar_treinamento" target="_blank"><i class="fa fa-graduation-cap fa-fw"></i> SOLICITAR TREINAMENTO</a>
    <?php
}
?>

<h2>Agendamentos Salvos</h2>
<table>
    <tr>
        <th>Status</th>
        <th>Piloto</th>
        <th>Data do Treinamento</th>
        <th>Hora do Treinamento</th>
        <th>Observações</th>
        <th>Ações</th>
    </tr>
    <?php
    $agendamentos_query = "SELECT t.*, u.name FROM trainings t JOIN gvausers u ON t.pilot_id = u.gvauser_id";
    $agendamentos_result = $db->query($agendamentos_query);

    if ($agendamentos_result->num_rows > 0) {
        while ($row = $agendamentos_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>";

            if (!$row['completed']) {
                echo "<i class='fas fa-clock clock-icon'></i>"; // Ícone de relógio
            } else {
                echo "<i class='fas fa-check check-icon'></i>"; // Ícone de marca de verificação
            }

            echo "</td>";
            echo "<td>" . $row['name'] .  "</td>";
            echo "<td>" . $row['training_date'] . "</td>";
            echo "<td>" . $row['training_time'] . "</td>";
            echo "<td>" . $row['observations'] . "</td>";
            echo "<td class='actions'>";

            if (!$row['completed']) {
                echo "<form method='post' action='./index_vam_op.php?page=training_page'>";
                echo "<input type='hidden' name='training_id' value='" . $row['training_id'] . "'>";
                echo "<button type='submit' name='mark_completed'>Concluído</button>";
                echo "</form>";
            }

            if ($_SESSION["access_administration_panel"] === '1') {
                echo "<form method='post' action='./index_vam_op.php?page=training_page'>";
                echo "<input type='hidden' name='training_id' value='" . $row['training_id'] . "'>";
                echo "<button type='submit' name='delete'>Apagar</button>";
                echo "</form>";
            }

            echo "</td>";
            echo "</tr>";
        }
    }
    ?>
</table>

<!-- Popup para exibir mensagens de ação -->
<div id="popup">
    <p id="popup-message"></p>
</div>

<!-- JavaScript para mostrar o popup -->
<script>
    function showPopup(message) {
        document.getElementById('popup-message').textContent = message;
        document.getElementById('popup').style.display = 'block';
        setTimeout(function () {
            document.getElementById('popup').style.display = 'none';
        }, 3000); // Fechar o popup após 3 segundos
    }
</script>
</body>
</html>
