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

// Processamento do formulário de solicitação de treinamento
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pilot_id = $_SESSION["id"]; // ID do piloto logado
    $training_date = $_POST['training_date'];
    $training_time = $_POST['training_time'];
    $observations = $_POST['observations'];

    // Inserir a solicitação de treinamento no banco de dados
    $insert_query = "INSERT INTO trainings (pilot_id, training_date, training_time, observations, completed) VALUES ('$pilot_id', '$training_date', '$training_time', '$observations', 0)";

    if ($db->query($insert_query)) {
        $message = "Solicitação de treinamento enviada com sucesso!";
        
        $pilot_name_query = "SELECT name FROM gvausers WHERE gvauser_id = $pilot_id";
    $pilot_name_result = $db->query($pilot_name_query);

    $pilot_name_row = $pilot_name_result->fetch_assoc();
        $pilot_name = $pilot_name_row['name'];
        
        // Enviar e-mail ao staff
        $staff_email = "staff@privatevirtual.com.br"; // Endereço de e-mail do staff
        $subject = "Nova Solicitação de Treinamento";
        $message = "Uma nova solicitação de treinamento foi enviada:\n\n";
        $message = "O piloto $pilot_name, está solicitando um treinamento:\n";
        $message .= "Data do Treinamento: $training_date\n";
        $message .= "Hora do Treinamento: $training_time\n";
        $message .= "Observações: $observations\n";
        $headers = "From: $staff_email";

        // Envie o e-mail ao staff
        mail($staff_email, $subject, $message, $headers);
    } else {
        $error_message = "Erro ao enviar a solicitação de treinamento: " . $db->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Solicitar Treinamento</title>
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

        input[type="date"], input[type="time"], textarea, input[type="submit"] {
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
    </style>
</head>
<body>
<h1>Solicitar Treinamento</h1>
<?php if (isset($message)) : ?>
    <p style="color: green;"><?php echo $message; ?></p>
<?php endif; ?>
<?php if (isset($error_message)) : ?>
    <p style="color: red;"><?php echo $error_message; ?></p>
<?php endif; ?>

<form method="post" action="./index_vam_op.php?page=solicitar_treinamento">
    <label for="training_date">Data do Treinamento:</label>
    <input type="date" name="training_date" id="training_date">
    <label for="training_time">Hora do Treinamento:</label>
    <input type="time" name="training_time" id="training_time">
    <label for="observations">Observações:</label>
    <textarea name="observations" id="observations" rows="4"></textarea>
    <input type="submit" value="Enviar Solicitação">
</form>
</body>
</html>
