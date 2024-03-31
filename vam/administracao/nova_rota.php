<!DOCTYPE html>
<html>
<head>
    <title>Inserir Nova Rota</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Inserir Nova Rota</h2>
    <form method="post" action="inserir_rota.php">
        <label for="flight">Voo:</label>
        <input type="text" name="flight" id="flight" required>

        <label for="departure">Partida:</label>
        <input type="text" name="departure" id="departure" required>

        <label for="arrival">Chegada:</label>
        <input type="text" name="arrival" id="arrival" required>

        <label for="alternative">Alternativa:</label>
        <input type="text" name="alternative" id="alternative" required>

        <label for="etd">ETD:</label>
        <input type="text" name="etd" id="etd" required>

        <label for="eta">ETA:</label>
        <input type="text" name="eta" id="eta" required>

        <label for="fleettype_id">ID do Tipo de Frota:</label>
        <input type="text" name="fleettype_id" id="fleettype_id" required>

        <label for="pax_price">Preço para Passageiros:</label>
        <input type="text" name="pax_price" id="pax_price" required>

        <label for="flproute">Flproute:</label>
        <input type="text" name="flproute" id="flproute" required>

        <label for="comments">Comentários:</label>
        <input type="text" name="comments" id="comments" required>

        <label for="duration">Duração:</label>
        <input type="text" name="duration" id="duration" required>

        <label for="cargo_price">Preço para Carga:</label>
        <input type="text" name="cargo_price" id="cargo_price" required>

        <label for="hub_id">ID do Hub:</label>
        <input type="text" name="hub_id" id="hub_id" required>

        <label for="flight_level">Nível do Voo:</label>
        <input type="text" name="flight_level" id="flight_level" required>

        <input type="submit" value="Inserir Rota">
    </form>
</body>
</html>
