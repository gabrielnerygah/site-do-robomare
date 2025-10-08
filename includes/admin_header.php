<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
$auth = new Auth();
$auth->requireLogin();
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?><!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Painel Admin'; ?> - <?php echo SITE_NAME; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --primary-navy: #00204A;
            --accent-cyan: #00A896;
        }
        /* Garantindo o design FLAT */
        .btn-flat {
            border-radius: 0 !important;
        }
    </style>
</head>
<body class="bg-gray-100">
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-8">
                    <h1 class="text-xl font-bold" style="color: var(--primary-navy);">Painel Admin</h1>
                    <div class="hidden md:flex gap-6">
                        <a href="dashboard.php" class="<?php echo $currentPage === 'dashboard' ? 'text-[#00A896] font-semibold' : 'text-gray-600 hover:text-[#00A896]'; ?> transition-colors">Dashboard</a>
                        <a href="stats.php" class="<?php echo $currentPage === 'stats' ? 'text-[#00A896] font-semibold' : 'text-gray-600 hover:text-[#00A896]'; ?> transition-colors">Estatísticas</a>
                        <a href="robots.php" class="<?php echo $currentPage === 'robots' ? 'text-[#00A896] font-semibold' : 'text-gray-600 hover:text-[#00A896]'; ?> transition-colors">Robôs</a>
                        <a href="sponsorship.php" class="<?php echo $currentPage === 'sponsorship' ? 'text-[#00A896] font-semibold' : 'text-gray-600 hover:text-[#00A896]'; ?> transition-colors">Patrocínios</a>
                        <?php if ($auth->isAdmin()): ?>
                        <a href="users.php" class="<?php echo $currentPage === 'users' ? 'text-[#00A896] font-semibold' : 'text-gray-600 hover:text-[#00A896]'; ?> transition-colors">Usuários</a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <span class="text-sm text-gray-600">Olá, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
                    <a href="<?php echo SITE_URL; ?>" target="_blank" class="text-sm text-gray-600 hover:text-[#00A896]">Ver Site</a>
                    <a href="logout.php" class="px-4 py-2 bg-red-500 text-white text-sm font-semibold hover:bg-red-600 btn-flat">Sair</a>
                </div>
            </div>
        </div>
    </nav>
        
    <div class="container mx-auto px-6 py-8">
    <script>lucide.createIcons();</script>
