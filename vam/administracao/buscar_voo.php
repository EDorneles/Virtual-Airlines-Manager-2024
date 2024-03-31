<!DOCTYPE html>
<html>
<head>
    <title>Editar Voos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1 {
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td a {
            color: #337ab7;
            text-decoration: none;
        }

        td a:hover {
            text-decoration: underline;
        }

        .no-results {
            text-align: center;
            font-weight: bold;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>ROTAS</h1>
    

    <form method="GET" action="">
        <label for="flight">PPT:</label>
        <input type="text" name="flight" id="flight" oninput="search()">
        <label for="departure">Origem:</label>
        <input type="text" name="departure" id="departure" oninput="search()">
        <label for="arrival">Destino:</label>
        <input type="text" name="arrival" id="arrival" oninput="search()">
        <input type="submit" value="Filtrar">
        <div class="function-button">
            <a href="nova_rota.php" target="_blank" class="function-link">
                <i class="fa fa-users fa-fw function-icon"></i>
                <button>NOVA ROTA</button>
            </a>
        </div>
    </form>

    <table id="flightsTable">
        <tr>
            <th>Route ID</th>
            <th>Flight</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Alternative</th>
            <th>ETD</th>
            <th>ETA</th>
            <th>Fleettype ID</th>
            <th>Pax Price</th>
            <th>FLP Route</th>
            <th>Comments</th>
            <th>Duration</th>
            <th>Cargo Price</th>
            <th>HUB ID</th>
            <th>Flight Level</th>
            <th>Tipo de Aeronave</th>
            <th>Ações</th>
            <th></th>
        </tr>
        <?php
        // Configurações do banco de dados
        $db_host = 'localhost';
        $db_database = 'priva674_2023';
        $db_username = 'priva674_2023';
        $db_password = 'OnlyPrivate2023@';

        // Conexão com o banco de dados
        $conn = new mysqli($db_host, $db_username, $db_password, $db_database);

        // Verifica se houve erro na conexão
        if ($conn->connect_error) {
            die('Erro na conexão com o banco de dados: ' . $conn->connect_error);
        }

        // Função para buscar os voos
        function searchFlights($flight, $departure, $arrival) {
            global $conn;
            $sql = "SELECT * FROM routes WHERE flight LIKE '%$flight%' AND departure LIKE '%$departure%' AND arrival LIKE '%$arrival%'";
            $result = $conn->query($sql);
            return $result;
        }

        // Parâmetros de filtro
        $flight = isset($_GET['flight']) ? $_GET['flight'] : '';
        $departure = isset($_GET['departure']) ? $_GET['departure'] : '';
        $arrival = isset($_GET['arrival']) ? $_GET['arrival'] : '';

        // Busca os voos com base nos parâmetros de filtro
        $result = searchFlights($flight, $departure, $arrival);

        // Verifica se houve resultados
        if ($result->num_rows > 0) {
    // Exibe os voos em uma tabela
    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . $row['route_id'] . '</td>';
        echo '<td>' . $row['flight'] . '</td>';
        echo '<td>' . $row['departure'] . '</td>';
        echo '<td>' . $row['arrival'] . '</td>';
        echo '<td>' . $row['alternative'] . '</td>';
        echo '<td>' . $row['etd'] . '</td>';
        echo '<td>' . $row['eta'] . '</td>';
        echo '<td>' . $row['fleettype_id'] . '</td>';
        echo '<td>' . $row['pax_price'] . '</td>';
        echo '<td>' . $row['flproute'] . '</td>';
        echo '<td>' . $row['comments'] . '</td>';
        echo '<td>' . $row['duration'] . '</td>';
        echo '<td>' . $row['cargo_price'] . '</td>';
        echo '<td>' . $row['hub_id'] . '</td>';
        echo '<td>' . $row['flight_level'] . '</td>';
        echo '<td>' . $row['fleettype_id'] . '</td>';
        echo '<td><a href="editar_rota.php?route_id=' . $row['route_id'] . '"><button>Editar Rota</button></a></td>';
        echo '</tr>';

        }

        echo '</table>';
    } else {
        echo 'Nenhum voo encontrado.';
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
    ?>

</body>
</html>
