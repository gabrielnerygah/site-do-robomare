<?php
// Configurações Globais
define('DB_HOST', 'sql213.infinityfree.com');
define('DB_NAME', 'if0_40111328_robomare');
define('DB_USER', 'if0_40111328');
define('DB_PASS', 'xanNwrnAs5');
define('SITE_URL', 'https://institutomarefuturo.42web.io');
define('SITE_NAME', 'Instituto Maré Futuro');

// Chave de API do Gemini
define('GEMINI_API_KEY', 'SUA_CHAVE_AQUI'); // **Mude esta chave**

// Configurações de sessão
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Mude para 1 em produção com HTTPS

session_start();
?>
