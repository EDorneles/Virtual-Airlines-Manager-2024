<!DOCTYPE html>
<html>
<head>
    <title>Editar Rota</title>
    <style>
        form {
            width: 50%;
            margin: 0 auto;
        }

        label {
            display: inline-block;
            width: 150px;
            margin-bottom: 10px;
        }

        input[type="text"],
        textarea {
            width: 300px;
            padding: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Editar Rota</h1>

    <?php
    // Verificar se o formulário foi enviado
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obter os dados do formulário
        $routeId = $_POST["route_id"];
        $flight = $_POST["flight"];
        $departure = $_POST["departure"];
        $arrival = $_POST["arrival"];
        $alternative = $_POST["alternative"];
        $etd = $_POST["etd"];
        $eta = $_POST["eta"];
        $fleettypeId = $_POST["fleettype_id"];
        $paxPrice = $_POST["pax_price"];
        $flpRoute = $_POST["flproute"];
        $comments = $_POST["comments"];
        $duration = $_POST["duration"];
        $cargoPrice = $_POST["cargo_price"];
        $hubId = $_POST["hub_id"];
        $flightLevel = $_POST["flight_level"];

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

        // Processa os dados do formulário e atualiza a rota no banco de dados
        $sql = "UPDATE routes SET flight = '$flight', departure = '$departure', arrival = '$arrival', alternative = '$alternative', etd = '$etd', eta = '$eta', fleettype_id = '$fleettypeId', pax_price = '$paxPrice', flproute = '$flpRoute', comments = '$comments', duration = '$duration', cargo_price = '$cargoPrice', hub_id = '$hubId', flight_level = '$flightLevel' WHERE route_id = '$routeId'";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Rota atualizada com sucesso!</p>";
        } else {
            echo "Erro ao atualizar a rota: " . $conn->error;
        }

        // Fecha a conexão com o banco de dados
        $conn->close();
    } else {
        // Aqui você pode recuperar os dados da rota do banco de dados com base no ID
        // Coloque aqui o código para obter os detalhes da rota do banco de dados

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

        // Obtém os detalhes da rota do banco de dados com base no ID
        $routeId = $_GET['route_id']; // Recupere o ID da rota a ser editada da URL (ex: editar_rota.php?id=1)
        $sql = "SELECT * FROM routes WHERE route_id = '$routeId'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $flight = $row['flight'];
            $departure = $row['departure'];
            $arrival = $row['arrival'];
            $alternative = $row['alternative'];
            $etd = $row['etd'];
            $eta = $row['eta'];
            $fleettypeId = $row['fleettype_id'];
            $paxPrice = $row['pax_price'];
            $flpRoute = $row['flproute'];
            $comments = $row['comments'];
            $duration = $row['duration'];
            $cargoPrice = $row['cargo_price'];
            $hubId = $row['hub_id'];
            $flightLevel = $row['flight_level'];

            // Exibir o formulário preenchido com os dados da rota
            ?>
            <form method="POST" action="">
                <input type="hidden" name="route_id" value="<?php echo $routeId; ?>">

                <label for="flight">Voo:</label>
                <input type="text" name="flight" value="<?php echo $flight; ?>" required><br>

                <label for="departure">Partida:</label>
                <input type="text" name="departure" value="<?php echo $departure; ?>" required><br>

                <label for="arrival">Chegada:</label>
                <input type="text" name="arrival" value="<?php echo $arrival; ?>" required><br>

                <label for="alternative">Alternativo:</label>
                <input type="text" name="alternative" value="<?php echo $alternative; ?>"><br>

                <label for="etd">ETD:</label>
                <input type="text" name="etd" value="<?php echo $etd; ?>"><br>

                <label for="eta">ETA:</label>
                <input type="text" name="eta" value="<?php echo $eta; ?>"><br>

                <label for="fleettype_id">Tipo de Aeronave:</label>
                <input type="text" name="fleettype_id" value="<?php echo $fleettypeId; ?>"><br>

                                <label for="pax_price">Preço (Passageiro):</label>
                <input type="text" name="pax_price" value="<?php echo $paxPrice; ?>"><br>

                <label for="flproute">Rota FL:</label>
                <input type="text" name="flproute" value="<?php echo $flpRoute; ?>"><br>

                <label for="comments">Comentários:</label>
                <textarea name="comments"><?php echo $comments; ?></textarea><br>

                <label for="duration">Duração:</label>
                <input type="text" name="duration" value="<?php echo $duration; ?>"><br>

                <label for="cargo_price">Preço (Carga):</label>
                <input type="text" name="cargo_price" value="<?php echo $cargoPrice; ?>"><br>

                <label for="hub_id">ID do Hub:</label>
                <input type="text" name="hub_id" value="<?php echo $hubId; ?>"><br>

                <label for="flight_level">Nível de Voo:</label>
                <input type="text" name="flight_level" value="<?php echo $flightLevel; ?>"><br>

                <button type="submit">Atualizar Rota</button>
            </form>
        <?php
        } else {
            echo "Nenhuma rota encontrada.";
        }

        // Fecha a conexão com o banco de dados
        $conn->close();
    }
    ?>

</body>
</html>


