<!DOCTYPE html>
<html>
<head>
    <title>Página de Administração</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .function-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .function-button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 180px;
            height: 180px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .function-button:hover {
            background-color: #555;
        }

        .function-link {
            text-decoration: none;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .function-icon {
            font-size: 60px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }
        
    </style>
</head>
<body>
    <h1>Página de Administração</h1>

    <div class="function-container">
        <div class="function-button">
            <a href="busca.php" target="_blank" class="function-link">
                <i class="fa fa-globe function-icon"></i>
                <p>Editar Voos</p>
            </a>
        </div>

        <div class="function-button">
            <a href="candidatos.php" target="_blank" class="function-link">
                <i class="fa fa-users fa-fw function-icon"></i>
                <p>Visualizar Candidatos</p>
            </a>
        </div>

        <div class="function-button">
            <a href="buscar_voo.php" target="_blank" class="function-link">
                <i class="fas fa-cogs function-icon"></i>
                <p>Editar Rotas</p>
            </a>
        </div>
        
        <div class="function-button">
            <a href="calculo_rota_aeronave.php" target="_blank" class="function-link">
                <i class="fas fa-cog function-icon"></i>
                <p>Calculos de Rota/Aeronave</p>
            </a>
        </div>
        
        <div class="function-button">
            <a href="#" target="_blank" class="function-link">
                <i class="fas fa-cog function-icon"></i>
                <p>Outra Função</p>
            </a>
        </div>
    </div>

</body>
</html>
