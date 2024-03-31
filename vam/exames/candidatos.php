<?php
$db_host = 'localhost';
$db_database = 'priva674_2023';
$db_username = 'priva674_2023';
$db_password = 'OnlyPrivate2023@';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $sobrenome = $_POST['sobrenome'];
    $email = $_POST['email'];

    // Cria conexão com o banco de dados
    $conn = new mysqli($db_host, $db_username, $db_password, $db_database);

    // Checa conexão
    if ($conn->connect_error) {
      die("Conexão falhou: " . $conn->connect_error);
    }
if (empty($nome) || empty($sobrenome) || empty($email)) {
  echo "Por favor, preencha todos os campos.";
}
    // Prepara SQL statement para inserir dados na tabela "candidatos"
    $sql = "INSERT INTO candidatos (nome, sobrenome, email)
    VALUES ('$nome', '$sobrenome', '$email')";

    // Executa SQL statement e checa se foi bem-sucedido
    if ($conn->query($sql) === TRUE) {
      // Redireciona para a página desejada após inserção dos dados
      header("Location: http://www.privatevirtual.com.br/vam/exames/questionario.php");
      exit();
    } else {
      echo "Erro ao inserir dados: " . $conn->error;
    }

    // Fecha conexão
    $conn->close();
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dados do candidato</title>
    <style>
        @import url(https://fonts.googleapis.com/css?family=Open+Sans);

.popup {
	position: fixed;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	background-color: #f8f8f8;
	border: 1px solid #ccc;
	box-shadow: 0px 0px 10px #ccc;
	padding: 20px;
	text-align: center;
	max-width: 400px;
	width: 100%;
	box-sizing: border-box;
	font-family: 'Open Sans', sans-serif;
}

h2 {
	margin-top: 0;
}

form {
	display: flex;
	flex-direction: column;
	align-items: center;
}

form label {
	display: block;
	margin-bottom: 5px;
}

form input {
	width: 100%;
	max-width: 300px;
	margin-bottom: 10px;
	padding: 5px;
	box-sizing: border-box;
}

form input[type="submit"] {
	background-color: #007bff;
	color: #fff;
	border: none;
	cursor: pointer;
	transition: all 0.3s ease;
	padding: 10px 20px;
	margin-top: 10px;
	width: auto;
	max-width: 100%;
	text-transform: uppercase;
	letter-spacing: 1px;
	font-family: 'Open Sans', sans-serif;
}

.btn {
	display: inline-block;
	*display: inline;
	*zoom: 1;
	padding: 4px 10px 4px;
	margin-bottom: 0;
	font-size: 13px;
	line-height: 18px;
	color: #333333;
	text-align: center;
	text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
	vertical-align: middle;
	background-color: #f5f5f5;
	background-image: -moz-linear-gradient(top, #ffffff, #e6e6e6);
	background-image: -ms-linear-gradient(top, #ffffff, #e6e6e6);
	background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6));
	background-image: -webkit-linear-gradient(top, #ffffff, #e6e6e6);
	background-image: -o-linear-gradient(top, #ffffff, #e6e6e6);
	background-image: linear-gradient(top, #ffffff, #e6e6e6);
	background-repeat: repeat-x;
	filter: progid:dximagetransform.microsoft.gradient(startColorstr=#ffffff, endColorstr=#e6e6e6, GradientType=0);
	border-color: #e6e6e6 #e6e6e6 #e6e6e6;
	border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	border: 1px solid #e6e6e6;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border-radius: 4px;
	-webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
	-moz-box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2),

        }
    </style>
</head>
<body>

<div class="popup">
    <h2>Antes de iniciar o seu exame, vamos precisar de algumas informações.</h2>
    <form method="post">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome"><br>
        <label for="sobrenome">Sobrenome:</label><br>
        <input type="text" id="sobrenome" name="sobrenome"><br>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email"><br><br>
        <input type="submit" value="Enviar">
    </form>
</div>

</body>
</html>
