<?php
$pageTitle = 'Editar Estatísticas';
require_once '../includes/admin_header.php';
require_once '../includes/db.php';

$db = Database::getInstance()->getConnection();
$message = '';
$isError = false;

// Processar formulário de atualização
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stats'])) {
    $lixo = sanitize($_POST['lixo_coletado'] ?? 0);
    $km = sanitize($_POST['km_limpos'] ?? 0);
    $ativos = sanitize($_POST['robos_ativos'] ?? 0);
    $pesquisas = sanitize($_POST['pesquisas_ativas'] ?? 0);

    try {
        // Assume que só há uma linha na tabela stats para o total global (id = 1)
        $stmt = $db->prepare("UPDATE stats SET lixo_coletado = ?, km_limpos = ?, robos_ativos = ?, pesquisas_ativas = ? WHERE id = 1");
        $stmt->execute([$lixo, $km, $ativos, $pesquisas]);
        $message = "Estatísticas atualizadas com sucesso!";
    } catch (Exception $e) {
        $message = "Erro ao atualizar: " . $e->getMessage();
        $isError = true;
    }
}

// Buscar estatísticas atuais
$stats = $db->query("SELECT * FROM stats ORDER BY id DESC LIMIT 1")->fetch();

if (!$stats) {
    // Se não houver estatísticas (banco de dados vazio), usa o valor 0
    $stats = [
        'lixo_coletado' => 0,
        'km_limpos' => 0,
        'robos_ativos' => 0,
        'pesquisas_ativas' => 0,
    ];
}
?>

<div class="mb-8">
    <h2 class="text-3xl font-bold mb-2" style="color: var(--primary-navy);">Estatísticas Globais</h2>
    <p class="text-gray-600">Atualize os números de impacto exibidos na página inicial.</p>
</div>

<?php if ($message): ?>
    <div class="p-4 mb-4 text-sm font-semibold btn-flat <?php echo $isError ? 'bg-red-100 text-red-700 border-l-4 border-red-500' : 'bg-green-100 text-green-700 border-l-4 border-green-500'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="bg-white shadow p-6 btn-flat">
    <form method="POST" class="space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="lixo_coletado" class="block text-sm font-semibold mb-2 text-gray-700">Lixo Coletado (kg)</label>
                <input type="number" id="lixo_coletado" name="lixo_coletado" value="<?php echo htmlspecialchars($stats['lixo_coletado']); ?>" required class="w-full px-4 py-3 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            </div>
            <div>
                <label for="km_limpos" class="block text-sm font-semibold mb-2 text-gray-700">KM Limpos (Decimal)</label>
                <input type="number" step="0.1" id="km_limpos" name="km_limpos" value="<?php echo htmlspecialchars($stats['km_limpos']); ?>" required class="w-full px-4 py-3 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            </div>
            <div>
                <label for="robos_ativos" class="block text-sm font-semibold mb-2 text-gray-700">Robôs Ativos Agora</label>
                <input type="number" id="robos_ativos" name="robos_ativos" value="<?php echo htmlspecialchars($stats['robos_ativos']); ?>" required class="w-full px-4 py-3 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            </div>
            <div>
                <label for="pesquisas_ativas" class="block text-sm font-semibold mb-2 text-gray-700">Pesquisas em Curso</label>
                <input type="number" id="pesquisas_ativas" name="pesquisas_ativas" value="<?php echo htmlspecialchars($stats['pesquisas_ativas']); ?>" required class="w-full px-4 py-3 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            </div>
        </div>
        
        <button type="submit" name="update_stats" class="px-6 py-3 font-bold text-white hover:opacity-90 transition-colors btn-flat" style="background-color: var(--accent-cyan);">
            ATUALIZAR ESTATÍSTICAS
        </button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>
