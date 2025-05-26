<?php
header('Content-Type: application/json');
require_once '../Includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $mensajeUsuario = strtolower(trim($input['mensaje'] ?? ''));

    try {
        $stmt = $conexion->query("SELECT nombreProducto, precio, stock FROM Productos");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo json_encode(['respuesta' => 'Error en la base de datos: ' . $e->getMessage()]);
        exit;
    }

    $infoProductos = "Lista de productos disponibles:\n";
    foreach ($productos as $p) {
        $infoProductos .= "- {$p['nombreProducto']} (Precio: {$p['precio']} MXN, Stock: {$p['stock']})\n";
    }

    $apiKey = ''; //aqui va la api here
    $url = 'https://openrouter.ai/api/v1/chat/completions';

    $data = [
        'model' => 'mistralai/mistral-7b-instruct',
        'messages' => [
            ['role' => 'system', 'content' => 'Eres un asistente en una papelería. Ayuda a los clientes a encontrar productos adecuados.'],
            ['role' => 'user', 'content' => "Pregunta del cliente: '$mensajeUsuario'\n\nEstos son los productos disponibles, solo elije maximo 2 productos :\n$infoProductos, solo responde por los nombres y el precio de los productos, si es que no te preguntan por stock"]
        ]
    ];

    $opciones = [
        'http' => [
            'method' => 'POST',
            'header' => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $apiKey,
                'HTTP-Referer: http://localhost',
                'X-Title: Papelo-Chat'
            ],
            'content' => json_encode($data)
        ]
    ];

    $contexto = stream_context_create($opciones);
    $respuesta = file_get_contents($url, false, $contexto);

    if ($respuesta === false) {
        echo json_encode(['respuesta' => 'No se pudo contactar al modelo de IA']);
        exit;
    }

    $resultado = json_decode($respuesta, true);
    echo json_encode([
        'respuesta' => $resultado['choices'][0]['message']['content'] ?? 'Respuesta no disponible',
        
    ]);
}
?>
