<!DOCTYPE html>
<html>
<head>
    <title>Informações de Aeronaves e Rotas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h2 {
            margin-top: 20px;
        }

        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <?php
    // Inclua o arquivo de configuração com os dados de conexão com o banco de dados
    include('config.php');

    // Conecte-se ao banco de dados usando as informações do arquivo de configuração
    $conn = new mysqli($db_host, $db_username, $db_password, $db_database);

    // Verifique a conexão
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    // Consulta SQL para recuperar informações das aeronaves
    $aircraftQuery = "SELECT fleet_id, registry, hours, status, name FROM fleets";

    // Consulta SQL para recuperar informações das rotas
    $routeQuery = "SELECT flight, departure, arrival, etd, eta, flproute FROM routes";

    // Executar consultas SQL
    $aircraftResult = $conn->query($aircraftQuery);
    $routeResult = $conn->query($routeQuery);

    // Verificar se as consultas foram bem-sucedidas
    if (!$aircraftResult || !$routeResult) {
        die("Erro na consulta ao banco de dados: " . $conn->error);
    }
    ?>

    <h2>Informações das Aeronaves:</h2>
    <table>
        <tr>
            <th>Fleet ID</th>
            <th>Matrícula</th>
            <th>Horas Voadas</th>
            <th>Status</th>
            <th>Nome</th>
        </tr>
        <?php
        while ($row = $aircraftResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["fleet_id"] . "</td>";
            echo "<td>" . $row["registry"] . "</td>";
            echo "<td>" . $row["hours"] . "</td>";
            echo "<td>" . $row["status"] . "%</td>";
            echo "<td>" . $row["name"] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Informações das Rotas:</h2>
    <table>
        <tr>
            <th>Número do Voo</th>
            <th>Origem</th>
            <th>Destino</th>
            <th>Hora Estimada para Decolagem</th>
            <th>Hora Estimada do Pouso</th>
            <th>Rota</th>
        </tr>
        <?php
        while ($row = $routeResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["flight"] . "</td>";
            echo "<td>" . $row["departure"] . "</td>";
            echo "<td>" . $row["arrival"] . "</td>";
            echo "<td>" . $row["etd"] . "</td>";
            echo "<td>" . $row["eta"] . "</td>";
            echo "<td>" . $row["flproute"] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <?php
    // Fechar a conexão com o banco de dados
    $conn->close();
    ?>
</body>
</html>
