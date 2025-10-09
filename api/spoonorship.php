<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome = sanitize($_POST['nome'] ?? '');
        $cargo = sanitize($_POST['cargo'] ?? '');
        $empresa = sanitize($_POST['empresa'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $telefone = sanitize($_POST['telefone'] ?? '');
        $orcamento = sanitize($_POST['orcamento'] ?? '');
        $mensagem = sanitize($_POST['mensagem'] ?? '');
        
        if (empty($nome) || empty($email) || empty($empresa) || empty($mensagem)) {
            throw new Exception('Campos obrigatórios não preenchidos.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('E-mail inválido.');
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO sponsorship_requests (nome, cargo, empresa, email, telefone, orcamento, mensagem) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nome, $cargo, $empresa, $email, $telefone, $orcamento, $mensagem]);

        echo json_encode(['success' => true, 'message' => 'Proposta enviada com sucesso.']);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
}
?>
