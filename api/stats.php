<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM stats ORDER BY id DESC LIMIT 1");
    $stats = $stmt->fetch();
    
    if ($stats) {
        echo json_encode($stats);
    } else {
        echo json_encode(['error' => 'Dados não encontrados']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar dados']);
}
?>
