<?php
// Configurações do banco de dados
$db_host = 'localhost';
$db_database = 'priva674_2023';
$db_username = 'priva674_2023';
$db_password = 'OnlyPrivate2023@';

// Conexão com o banco de dados
$db = new PDO("mysql:host=$db_host;dbname=$db_database;charset=utf8mb4", $db_username, $db_password);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Função para calcular a distância entre dois pontos usando suas coordenadas
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // Raio médio da Terra em km

    $dlat = deg2rad($lat2 - $lat1);
    $dlon = deg2rad($lon2 - $lon1);

    $a = sin($dlat/2) * sin($dlat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlon/2) * sin($dlon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));

    $distance = $earth_radius * $c;
    return $distance;
}

try {
    // Consulta para obter todas as rotas
    $routes_query = $db->query("SELECT * FROM routes");

    // Loop através das rotas
    while ($route = $routes_query->fetch(PDO::FETCH_ASSOC)) {
        $route_id = $route['route_id'];
        $departure = $route['departure'];
        $arrival = $route['arrival'];

        // Consulta para obter as coordenadas da origem
        $departure_query = $db->prepare("SELECT latitude_deg, longitude_deg FROM airports WHERE ident = ?");
        $departure_query->execute([$departure]);
        $departure_coords = $departure_query->fetch(PDO::FETCH_ASSOC);

        $departure_lat = $departure_coords['latitude_deg'];
        $departure_lon = $departure_coords['longitude_deg'];

        // Consulta para obter as coordenadas do destino
        $arrival_query = $db->prepare("SELECT latitude_deg, longitude_deg FROM airports WHERE ident = ?");
        $arrival_query->execute([$arrival]);
        $arrival_coords = $arrival_query->fetch(PDO::FETCH_ASSOC);

        $arrival_lat = $arrival_coords['latitude_deg'];
        $arrival_lon = $arrival_coords['longitude_deg'];

        // Consulta para obter as aeronaves com autonomia suficiente para a rota
        $fleet_query = $db->prepare("SELECT fleettype_id FROM fleettypes WHERE maximum_range >= ?");
        $distance = calculateDistance($departure_lat, $departure_lon, $arrival_lat, $arrival_lon);
        $fleet_query->execute([$distance]);

        // Loop através das aeronaves encontradas
        while ($fleet = $fleet_query->fetch(PDO::FETCH_ASSOC)) {
            $fleettype_id = $fleet['fleettype_id'];

            // Inserir o resultado na tabela fleettypes_routes
            $insert_query = $db->prepare("INSERT INTO fleettypes_routes (route_id, fleettype_id) VALUES (?, ?)");
            $insert_query->execute([$route_id, $fleettype_id]);
        }
    }

    echo "Atribuição de aeronaves concluída com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao atribuir aeronaves: " . $e->getMessage();
}
