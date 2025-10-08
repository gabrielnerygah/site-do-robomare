<?php
header('Content-Type: application/json');
require_once '../includes/db.php';

try {
    $db = Database::getInstance()->getConnection();
    $stmt = $db->query("SELECT * FROM robots ORDER BY nome ASC");
    $robots = $stmt->fetchAll();
    
    echo json_encode($robots);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro ao buscar robôs']);
}
?>
