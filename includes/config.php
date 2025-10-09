<?php
// ===============================================
// CONFIGURAÇÕES GLOBAIS E CONEXÃO COM O BANCO (PDO)
// ===============================================

// --- 1. Credenciais do Banco de Dados (InfinityFree) ---
// Use os detalhes exatos do seu painel
define('DB_HOST', 'sql213.infinityfree.com'); // Tente 'localhost' se o Hostname falhar!
define('DB_NAME', 'if0_40111328_robomare');
define('DB_USER', 'if0_40111328');
define('DB_PASS', 'xanIwrnAs5'); // COLOQUE SUA SENHA REAL AQUI!!!

// --- 2. Configurações de Site e Segurança ---
define('SITE_URL', 'http://localhost/mare-futuro'); // Mude para o endereço real do seu site em produção
define('SITE_NAME', 'Instituto Maré Futuro');
define('GEMINI_API_KEY', 'SUA_CHAVE_AQUI'); // Chave para a IA

ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0);
session_start();

// --- 3. Estabelecendo a Conexão (PDO) ---
$pdo_conn = null;
try {
    $pdo_conn = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    // DIAGNÓSTICO AGRESSIVO DE FALHA: Mostra o erro exato na tela
    die("
        <div style='background-color: #fee; border: 1px solid #f00; padding: 15px; margin: 20px; font-family: monospace;'>
            <h2>ERRO FATAL DE CONEXÃO</h2>
            <p><strong>CÓDIGO DE ERRO:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
            <p><strong>Ação:</strong> Verifique se DB_HOST, DB_USER, DB_PASS e DB_NAME estão CORRETOS e se você executou o SQL para criar o banco <code>if0_40111328_robomare</code>.</p>
        </div>
    ");
}

// Globaliza a conexão PDO para ser usada por Auth e outras classes
define('PDO_CONN', $pdo_conn); 

// Adicione aqui a função para obter a conexão PDO
function getDbConnection() {
    return defined('PDO_CONN') ? PDO_CONN : null;
}
?>
