<?php
session_start();

$db_host = 'localhost';
$db_database = 'priva674_2023';
$db_username = 'priva674_2023';
$db_password = 'OnlyPrivate2023@';

// Verifica se o usuário possui permissão de administrador
if ($_SESSION["access_administration_panel"] !== '1') {
    // Redireciona para uma página de erro ou exibe uma mensagem informando a falta de permissão
    echo "Acesso negado. Você não tem permissão para visualizar esta página.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $voo_id = $_GET["id"];

    try {
        $db_conn = new PDO("mysql:host=$db_host;dbname=$db_database", $db_username, $db_password);
        $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "SELECT * FROM vampireps WHERE id = :id";
        $statement = $db_conn->prepare($query);
        $statement->bindParam(':id', $voo_id);
        $statement->execute();
        $voo = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$voo) {
            echo "Voo não encontrado.";
            exit;
        }
    } catch (PDOException $e) {
        echo "Erro de conexão com o banco de dados: " . $e->getMessage();
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["voo_id"])) {
    $voo_id = $_POST["voo_id"];
    $callsign = $_POST["callsign"];
    $pax = $_POST["pax"];
    $route = $_POST["route"];
    $remarks = $_POST["remarks"];
    $flight_type = $_POST["flight_type"];
    $landing_vs = $_POST["landing_vs"];
    $virtual_airline = $_POST["virtual_airline"];
    $va_url = $_POST["va_url"];
    $validator_comments = $_POST["validator_comments"];

    try {
        $db_conn = new PDO("mysql:host=$db_host;dbname=$db_database", $db_username, $db_password);
        $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = "UPDATE vampireps SET callsign = :callsign, pax = :pax, route = :route, remarks = :remarks, flight_type = :flight_type, landing_vs = :landing_vs, virtual_airline = :virtual_airline, va_url = :va_url, validator_comments = :validator_comments WHERE id = :id";
        $statement = $db_conn->prepare($query);
        $statement->bindParam(':callsign', $callsign);
        $statement->bindParam(':pax', $pax);
        $statement->bindParam(':route', $route);
        $statement->bindParam(':remarks', $remarks);
        $statement->bindParam(':flight_type', $flight_type);
        $statement->bindParam(':landing_vs', $landing_vs);
        $statement->bindParam(':virtual_airline', $virtual_airline);
        $statement->bindParam(':va_url', $va_url);
        $statement->bindParam(':validator_comments', $validator_comments);
        $statement->bindParam(':id', $voo_id);
        $statement->execute();

        echo "Voo atualizado com sucesso!";
        header("Location: ./busca.php"); // Redireciona para a página busca.php
            exit;
    } catch (PDOException $e) {
            echo "Erro de conexão com o banco de dados: " . $e->getMessage();
            exit;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Voo</title>
        <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            display: inline-block;
            width: 120px;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 300px;
            padding: 5px;
        }

        input[type="submit"] {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        .success-message {
            color: green;
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Editar Voo</h1>
    <form method="POST" action="">
        <input type="hidden" name="voo_id" value="<?php echo $voo_id; ?>">
        <label for="callsign">Callsign:</label>
        <input type="text" name="callsign" value="<?php echo $voo['callsign']; ?>"><br>
        <label for="pax">Pax:</label>
        <input type="text" name="pax" value="<?php echo $voo['pax']; ?>"><br>
        <label for="route">Route:</label>
        <input type="text" name="route" value="<?php echo $voo['route']; ?>"><br>
        <label for="remarks">Remarks:</label>
        <input type="text" name="remarks" value="<?php echo $voo['remarks']; ?>"><br>
        <label for="flight_type">Flight Type:</label>
        <input type="text" name="flight_type" value="<?php echo $voo['flight_type']; ?>"><br>
        <label for="landing_vs">Landing VS:</label>
        <input type="text" name="landing_vs" value="<?php echo $voo['landing_vs']; ?>"><br>
        <label for="virtual_airline">Virtual Airline:</label>
        <input type="text" name="virtual_airline" value="<?php echo $voo['virtual_airline']; ?>"><br>
        <label for="va_url">VA URL:</label>
        <input type="text" name="va_url" value="<?php echo $voo['va_url']; ?>"><br>
        <label for="validator_comments">Validator Comments:</label>
        <input type="text" name="validator_comments" value="<?php echo $voo['validator_comments']; ?>"><br>
        <input type="submit" value="Atualizar">
    </form>
    
</body>
</html>

