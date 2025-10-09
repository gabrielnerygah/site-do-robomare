<?php
$pageTitle = 'Dashboard';
require_once '../includes/admin_header.php';
require_once '../includes/config.php';
$db = Database::getInstance()->getConnection();

// Buscar dados
$stats = $db->query("SELECT * FROM stats ORDER BY id DESC LIMIT 1")->fetch();
$totalRobots = $db->query("SELECT COUNT(*) as total FROM robots")->fetch()['total'];
$pendingRequests = $db->query("SELECT COUNT(*) as total FROM sponsorship_requests WHERE status = 'pendente'")->fetch()['total'];
$totalUsers = $db->query("SELECT COUNT(*) as total FROM users")->fetch()['total'];
$recentRequests = $db->query("SELECT * FROM sponsorship_requests ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<div class="mb-8">
    <h2 class="text-3xl font-bold mb-2" style="color: var(--primary-navy);">Dashboard</h2>
    <p class="text-gray-600">Visão geral do sistema</p>
</div>

<!-- Cards de Resumo -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 shadow btn-flat">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Lixo Coletado</p>
                <p class="text-3xl font-bold" style="color: var(--primary-navy);"><?php echo formatNumber($stats['lixo_coletado']); ?> kg</p>
            </div>
            <i data-lucide="droplet" class="w-12 h-12" style="color: var(--accent-cyan);"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 shadow btn-flat">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total de Robôs</p>
                <p class="text-3xl font-bold" style="color: var(--primary-navy);"><?php echo $totalRobots; ?></p>
            </div>
            <i data-lucide="anchor" class="w-12 h-12" style="color: var(--accent-cyan);"></i>
        </div>
    </div>

    <div class="bg-white p-6 shadow btn-flat">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Propostas Pendentes</p>
                <p class="text-3xl font-bold" style="color: var(--primary-navy);"><?php echo $pendingRequests; ?></p>
            </div>
            <i data-lucide="mail" class="w-12 h-12" style="color: var(--accent-cyan);"></i>
        </div>
    </div>
    
    <div class="bg-white p-6 shadow btn-flat">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total de Usuários Admin</p>
                <p class="text-3xl font-bold" style="color: var(--primary-navy);"><?php echo $totalUsers; ?></p>
            </div>
            <i data-lucide="users" class="w-12 h-12" style="color: var(--accent-cyan);"></i>
        </div>
    </div>
</div>

<!-- Propostas Recentes -->
<div class="bg-white shadow p-6 btn-flat">
    <h3 class="text-xl font-bold mb-4" style="color: var(--primary-navy);">Propostas de Patrocínio Recentes</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Contato</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Orçamento</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Data</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($recentRequests as $request): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($request['empresa']); ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo htmlspecialchars($request['nome']); ?><br><span class="text-gray-500"><?php echo htmlspecialchars($request['email']); ?></span></td>
                    <td class="px-4 py-3 text-sm"><?php echo htmlspecialchars($request['orcamento']); ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo date('d/m/Y H:i', strtotime($request['created_at'])); ?></td>
                    <td class="px-4 py-3"><?php echo getStatusBadge($request['status']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        <a href="sponsorship.php" class="text-sm font-semibold hover:underline" style="color: var(--accent-cyan);">Ver todas as propostas →</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
