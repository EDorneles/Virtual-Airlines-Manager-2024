<!DOCTYPE html>
<html>
<head>
    <title>Lista de Candidatos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Lista de Candidatos</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Sobrenome</th>
            <th>Email</th>
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

        // Consulta os candidatos
        $sql = 'SELECT id_candidato, nome, sobrenome, email FROM candidatos';
        $result = $conn->query($sql);

        // Verifica se houve resultados
        if ($result->num_rows > 0) {
            // Exibe os candidatos em uma tabela
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['id_candidato'] . '</td>';
                echo '<td>' . $row['nome'] . '</td>';
                echo '<td>' . $row['sobrenome'] . '</td>';
                echo '<td>' . $row['email'] . '</td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="4">Nenhum candidato encontrado.</td></tr>';
        }

        // Fecha a conexão com o banco de dados
        $conn->close();
        ?>
    </table>

</body>
</html>
