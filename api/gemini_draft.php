<?php
header('Content-Type: application/json');
require_once '../includes/config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método não permitido']);
    exit;
}

if (!defined('GEMINI_API_KEY') || GEMINI_API_KEY === 'SUA_CHAVE_AQUI') {
    http_response_code(500);
    echo json_encode(['error' => 'Chave de API do Gemini não configurada.']);
    exit;
}

$empresa = sanitize($_POST['empresa'] ?? '');
$llmInput = sanitize($_POST['llm_input'] ?? '');

if (empty($empresa) || empty($llmInput)) {
    http_response_code(400);
    echo json_encode(['error' => 'Nome da empresa e focos ESG são obrigatórios.']);
    exit;
}

// Lógica de chamada da API Gemini (usando cURL, pois o PHP não tem fetch nativo)
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-05-20:generateContent?key=" . GEMINI_API_KEY;

$systemPrompt = "Aja como um especialista em Sustentabilidade e ESG. Crie um parágrafo conciso e impactante (máximo 150 palavras) que sirva como rascunho de uma mensagem de patrocínio corporativo. O rascunho deve conectar os objetivos de sustentabilidade da empresa ({$empresa}) com a missão do Instituto Maré Futuro (robótica autônoma para limpeza de oceanos e geração de dados científicos). Use os seguintes focos chave fornecidos: \"{$llmInput}\". O tom deve ser profissional e focado no retorno de investimento e ESG.";
$userQuery = "Gerar rascunho de proposta de patrocínio para a empresa {$empresa} com foco em: {$llmInput}. Use dados e tendências atuais sobre poluição marinha e ESG.";

$payload = [
    'contents' => [['parts' => [['text' => $userQuery]]]],
    'tools' => [['google_search' => new stdClass()]],
    'systemInstruction' => ['parts' => [['text' => $systemPrompt]]],
];

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
// Adiciona uma opção para ignorar a verificação SSL/TLS se estiver enfrentando problemas no InfinityFree
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || !$response) {
    http_response_code(500);
    error_log("Gemini API Error: HTTP Code {$httpCode}, Response: {$response}");
    echo json_encode(['error' => 'Falha na comunicação com a API Gemini. Código HTTP: ' . $httpCode]);
    exit;
}

$result = json_decode($response, true);

$generatedText = $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Falha ao gerar texto.';
$citations = [];

// Extração de fontes
$groundingMetadata = $result['candidates'][0]['groundingMetadata'] ?? null;
if (isset($groundingMetadata['groundingAttributions'])) {
    $citations = array_map(function($attr) {
        // Certifica-se de que a estrutura web existe antes de acessar uri e title
        return [
            'uri' => $attr['web']['uri'] ?? '#', 
            'title' => $attr['web']['title'] ?? 'Fonte sem título'
        ];
    }, $groundingMetadata['groundingAttributions']);
}

echo json_encode([
    'success' => true,
    'draft' => $generatedText,
    'citations' => $citations,
]);
?>
