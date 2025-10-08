<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
$auth = new Auth();
$pageTitle = 'Login';

// Se já estiver logado, redireciona
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($auth->login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Usuário ou senha inválidos.';
    }
}
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Painel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary-navy: #00204A;
            --accent-cyan: #00A896;
        }
        .btn-flat { border-radius: 0 !important; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-10 shadow-lg max-w-md w-full btn-flat">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2" style="color: var(--primary-navy);">Painel Administrativo</h1>
            <p class="text-gray-600">Instituto Maré Futuro</p>
        </div>

        <?php if ($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 btn-flat">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold mb-2 text-gray-700">Usuário ou E-mail</label>
                <input type="text" name="username" required class="w-full px-4 py-3 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-gray-700">Senha</label>
                <input type="password" name="password" required class="w-full px-4 py-3 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            </div>

            <button type="submit" class="w-full py-4 font-bold text-white hover:opacity-90 transition-colors btn-flat" style="background-color: var(--accent-cyan);">
                ENTRAR
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="<?php echo SITE_URL; ?>" class="text-sm text-gray-600 hover:text-[#00A896]">← Voltar ao site</a>
        </div>
    </div>
</body>
</html>
