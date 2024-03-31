<!DOCTYPE html>
<html>
<head>
    <title>Editar Reporte de Voo</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Editar Reporte de Voo</h1>

    <!-- Formulário de busca -->
    <form method="POST" action="">
        <label for="callsign">Callsign do Piloto:</label>
        <input type="text" name="callsign" id="callsign">
        <input type="submit" value="Pesquisar">
    </form>

    <?php
    session_start();

    $db_host = 'localhost';
    $db_database = 'priva674_2023';
    $db_username = 'priva674_2023';
    $db_password = 'OnlyPrivate2023@';

    
    

    // Função para pesquisar o callsign na tabela gvausers
    function pesquisarCallsign($callsign, $db_conn) {
        $query = "SELECT * FROM gvausers WHERE callsign = :callsign";
        $statement = $db_conn->prepare($query);
        $statement->bindParam(':callsign', $callsign);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    // Função para importar os voos do piloto com base no gvauser_id
    function importarVoos($gvauser_id, $db_conn) {
        $query = "SELECT * FROM vampireps WHERE gvauser_id = :gvauser_id";
        $statement = $db_conn->prepare($query);
        $statement->bindParam(':gvauser_id', $gvauser_id);
        $statement->execute();
        $voos = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $voos;
    }

    try {
        $db_conn = new PDO("mysql:host=$db_host;dbname=$db_database", $db_username, $db_password);
        $db_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verifica se o formulário foi enviado
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Obtém o callsign digitado no formulário
            $callsign = $_POST["callsign"];

            // Pesquisa o callsign na tabela gvausers
            $result = pesquisarCallsign($callsign, $db_conn);

            if ($result) {
                $gvauser_id = $result["gvauser_id"];
     
                     // Importa os voos do piloto com o gvauser_id especificado e exibe a tabela
                $voos = importarVoos($gvauser_id, $db_conn);

                if (!empty($voos)) {
                    echo "<h2>Voos do Piloto: ".$callsign."</h2>";
                    echo "<table>";
                    echo "<tr><th>Callsign</th><th>Pax</th><th>Route</th><th>Remarks</th><th>Flight Type</th><th>Landing VS</th><th>Virtual Airline</th><th>VA URL</th><th>Validator Comments</th><th>Editar</th></tr>";
                    foreach ($voos as $voo) {
                        echo "<tr>";
                        echo "<td>".$voo["callsign"]."</td>";
                        echo "<td>".$voo["pax"]."</td>";
                        echo "<td>".$voo["route"]."</td>";
                        echo "<td>".$voo["remarks"]."</td>";
                        echo "<td>".$voo["flight_type"]."</td>";
                        echo "<td>".$voo["landing_vs"]."</td>";
                        echo "<td>".$voo["virtual_airline"]."</td>";
                        echo "<td>".$voo["va_url"]."</td>";
                        echo "<td>".$voo["validator_comments"]."</td>";
                        echo "<td><a href='editar_voo.php?id=".$voo["id"]."'>Editar</a></td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p class='error-message'>Nenhum voo encontrado para o callsign ".$callsign.".</p>";
                }
            } else {
                echo "<p class='error-message'>Callsign não encontrado na tabela gvausers.</p>";
            }
        }
    } catch (PDOException $e) {
        echo "<p class='error-message'>Erro de conexão com o banco de dados: ".$e->getMessage()."</p>";
    }
    ?>
</body>
</html>
           
