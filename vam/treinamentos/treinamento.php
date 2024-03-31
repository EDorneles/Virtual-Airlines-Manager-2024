<?php /**
 * @Project: Virtual Airlines Manager (VAM)
 * @Author: Alejandro Garcia * @Web http://virtualairlinesmanager.net
 * Copyright (c) 2013 - 2016 Alejandro Garcia
 * VAM is licenced under the following license:
 * Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
 * View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/4.0/
 */
?>
<?php
  if (empty($_SESSION['id'])) {
  $sessionid = 'nosession';
} else {
  $sessionid = $_SESSION["id"];
}
  if (($pilot_public != 1) && ($sessionid == 'nosession'))
  {
    require('check_login.php');
  } else {
    $pilotID = $_GET['pilot_id'];
    include('get_pilotID_data.php');
    include('get_va_parameters.php');
    ?>

<!DOCTYPE html>
<html>
<head>
    <title>Página de Treinamento para Pilotos</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Página de Treinamento para Pilotos</h1>
    <table>
        <tr>
            <th>Training ID</th>
            <th>Course ID</th>
            <th>Título</th>
            <th>Descrição</th>
            <th>Conteúdo</th>
            <th>Data de Início</th>
            <th>Data de Término</th>
            <th>Hora de Início</th>
            <th>Duração do Treinamento</th>
        </tr>
        <?php
        // Loop através dos resultados da consulta e exibição dos dados
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $row['training_id'] . '</td>';
            echo '<td>' . $row['course_id'] . '</td>';
            echo '<td>' . $row['title'] . '</td>';
            echo '<td>' . $row['description'] . '</td>';
            echo '<td>' . $row['content'] . '</td>';
            echo '<td>' . $row['start_date'] . '</td>';
            echo '<td>' . $row['end_date'] . '</td>';
            echo '<td>' . $row['start_time'] . '</td>';
            echo '<td>' . $row['training_duration'] . '</td>';
            echo '</tr>';
        }
        ?>
    </table>
</body>
</html>

<?php
// Fechando a conexão com o banco de dados
mysqli_close($conn);
?>
