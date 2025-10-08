<?php
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Instituto Maré Futuro - Robótica autônoma para limpeza de oceanos">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');
        body { font-family: 'Inter', sans-serif; }
        :root {
            --primary-navy: #00204A;
            --accent-cyan: #00A896;
            --neutral-white: #FFFFFF;
            --neutral-black: #000000;
        }
        /* Garantindo o design FLAT */
        .btn-flat {
            border-radius: 0 !important;
        }
    </style>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="min-h-screen bg-white">
    <header class="sticky top-0 z-50 border-b-2 shadow-lg" style="background-color: var(--primary-navy); color: var(--neutral-white); border-color: var(--accent-cyan);">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-3">
                    <i data-lucide="waves" class="w-8 h-8" style="color: var(--accent-cyan);"></i>
                    <div class="text-xl font-bold tracking-wider">INSTITUTO MARÉ FUTURO</div>
                </div>
                
                <nav class="hidden lg:flex items-center gap-10">
                    <a href="<?php echo SITE_URL; ?>/#home" class="text-sm font-semibold tracking-wide hover:text-[#00A896] transition-colors">HOME</a>
                    <a href="<?php echo SITE_URL; ?>/#impacto" class="text-sm font-semibold tracking-wide hover:text-[#00A896] transition-colors">IMPACTO</a>
                    <a href="<?php echo SITE_URL; ?>/#rastreamento" class="text-sm font-semibold tracking-wide hover:text-[#00A896] transition-colors">RASTREAMENTO</a>
                    <a href="<?php echo SITE_URL; ?>/#pesquisa" class="text-sm font-semibold tracking-wide hover:text-[#00A896] transition-colors">PESQUISA</a>
                    <a href="<?php echo SITE_URL; ?>/#patrocinio" class="text-sm font-semibold tracking-wide hover:text-[#00A896] transition-colors">PATROCÍNIO</a>
                </nav>

                <button id="menu-button" class="lg:hidden" aria-label="Menu">
                    <i data-lucide="menu" class="w-7 h-7"></i>
                </button>
            </div>

            <nav id="mobile-menu" class="lg:hidden hidden pb-6 space-y-4 border-t pt-6 mt-2" style="border-color: var(--accent-cyan);">
                <a href="<?php echo SITE_URL; ?>/#home" class="block text-base font-semibold tracking-wide">HOME</a>
                <a href="<?php echo SITE_URL; ?>/#impacto" class="block text-base font-semibold tracking-wide">IMPACTO</a>
                <a href="<?php echo SITE_URL; ?>/#rastreamento" class="block text-base font-semibold tracking-wide">RASTREAMENTO</a>
                <a href="<?php echo SITE_URL; ?>/#pesquisa" class="block text-base font-semibold tracking-wide">PESQUISA</a>
                <a href="<?php echo SITE_URL; ?>/#patrocinio" class="block text-base font-semibold tracking-wide">PATROCÍNIO</a>
            </nav>
        </div>
    </header>
    
    <script>
        document.getElementById('menu-button')?.addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        document.getElementById('mobile-menu')?.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });
    </script>
