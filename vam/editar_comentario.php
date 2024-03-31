<?php
// Verifica se o parâmetro "id" foi passado na URL
if (isset($_GET['id'])) {
    // Obtém o ID do comentário a ser editado
    $id = $_GET['id'];

    // Verifica se o formulário foi enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Processa os dados do formulário e atualiza o comentário no banco de dados
        // Substitua os campos "localhost", "priva674_2023", "priva674_2023" e "OnlyPrivate2023@" pelas informações do seu banco de dados
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

        // Obtém os dados do formulário
        $novoComentario = $_POST['comments'];

        // Atualiza o comentário no banco de dados
        $sql = "UPDATE comentarios SET comments = '$novoComentario' WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Comentário atualizado com sucesso
            echo "Comentário atualizado com sucesso.";
        } else {
            echo "Erro ao atualizar o comentário: " . $conn->error;
        }

        // Fecha a conexão com o banco de dados
        $conn->close();
    }

    // Aqui você pode recuperar os dados do comentário do banco de dados com base no ID
    // Substitua os campos "localhost", "priva674_2023", "priva674_2023" e "OnlyPrivate2023@" pelas informações do seu banco de dados
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

    // Consulta o comentário com base no ID
    $sql = "SELECT comments FROM comentarios WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $comentario = $row['comments'];
    } else {
        echo "Comentário não encontrado.";
    }

    // Fecha a conexão com o banco de dados
    $conn->close();

    // Exemplo de exibição do formulário de edição
    echo '<form method="POST">';
    echo 'Editar Comentário:<br>';
    echo '<textarea name="comments">' . $comentario . '</textarea><br>';
    echo '<button type="submit">Salvar</button>';
echo '</form>';
}

?>

</body>
</html>
   
