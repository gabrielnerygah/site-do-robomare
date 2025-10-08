<?php
$pageTitle = 'Gerenciar Robôs';
require_once '../includes/admin_header.php';
require_once '../includes/db.php';

$db = Database::getInstance()->getConnection();
$message = '';
$isError = false;
$editRobot = null;

// Processar exclusão
if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);
    try {
        $stmt = $db->prepare("DELETE FROM robots WHERE id = ?");
        $stmt->execute([$id]);
        $message = "Robô excluído com sucesso!";
    } catch (Exception $e) {
        $message = "Erro ao excluir: " . $e->getMessage();
        $isError = true;
    }
}

// Processar formulário (Adicionar/Editar)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_robot'])) {
    $id = sanitize($_POST['id'] ?? null);
    $nome = sanitize($_POST['nome']);
    $lat = sanitize($_POST['lat']);
    $lng = sanitize($_POST['lng']);
    $status = sanitize($_POST['status']);
    $km_limpos = sanitize($_POST['km_limpos']);
    $lixo_coletado = sanitize($_POST['lixo_coletado']);
    $patrocinador = sanitize($_POST['patrocinador']);

    try {
        if ($id) {
            // Edição
            $stmt = $db->prepare("UPDATE robots SET nome=?, lat=?, lng=?, status=?, km_limpos=?, lixo_coletado=?, patrocinador=? WHERE id=?");
            $stmt->execute([$nome, $lat, $lng, $status, $km_limpos, $lixo_coletado, $patrocinador, $id]);
            $message = "Robô '{$nome}' atualizado com sucesso!";
        } else {
            // Criação
            $stmt = $db->prepare("INSERT INTO robots (nome, lat, lng, status, km_limpos, lixo_coletado, patrocinador) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $lat, $lng, $status, $km_limpos, $lixo_coletado, $patrocinador]);
            $message = "Novo robô '{$nome}' adicionado com sucesso!";
        }
    } catch (Exception $e) {
        $message = "Erro ao salvar: " . $e->getMessage();
        $isError = true;
    }
}

// Carregar robô para edição
if (isset($_GET['edit'])) {
    $id = sanitize($_GET['edit']);
    $stmt = $db->prepare("SELECT * FROM robots WHERE id = ?");
    $stmt->execute([$id]);
    $editRobot = $stmt->fetch();
}

// Listar todos os robôs
$robots = $db->query("SELECT * FROM robots ORDER BY id DESC")->fetchAll();
?>

<div class="mb-8">
    <h2 class="text-3xl font-bold mb-2" style="color: var(--primary-navy);">Gerenciar Robôs</h2>
    <p class="text-gray-600">Controle a frota, coordenadas de rastreamento (simulação) e patrocínios.</p>
</div>

<?php if ($message): ?>
    <div class="p-4 mb-4 text-sm font-semibold btn-flat <?php echo $isError ? 'bg-red-100 text-red-700 border-l-4 border-red-500' : 'bg-green-100 text-green-700 border-l-4 border-green-500'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<!-- Formulário de Criação/Edição -->
<div class="bg-white shadow p-6 mb-8 btn-flat">
    <h3 class="text-xl font-bold mb-4" style="color: var(--primary-navy);"><?php echo $editRobot ? 'Editar Robô: ' . htmlspecialchars($editRobot['nome']) : 'Adicionar Novo Robô'; ?></h3>
    <form method="POST" class="space-y-4">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($editRobot['id'] ?? ''); ?>">
        
        <div class="grid md:grid-cols-2 gap-4">
            <input type="text" name="nome" placeholder="Nome do Robô (Ex: Guardião I)" value="<?php echo htmlspecialchars($editRobot['nome'] ?? ''); ?>" required class="px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            <input type="text" name="patrocinador" placeholder="Patrocinador (Opcional)" value="<?php echo htmlspecialchars($editRobot['patrocinador'] ?? ''); ?>" class="px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <input type="number" step="0.0001" name="lat" placeholder="Latitude (Para Rastreamento)" value="<?php echo htmlspecialchars($editRobot['lat'] ?? '-7.0833'); ?>" required class="px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            <input type="number" step="0.0001" name="lng" placeholder="Longitude (Para Rastreamento)" value="<?php echo htmlspecialchars($editRobot['lng'] ?? '-34.8333'); ?>" required class="px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            <select name="status" required class="w-full px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
                <option value="operacao" <?php echo ($editRobot['status'] ?? '') === 'operacao' ? 'selected' : ''; ?>>Em Operação</option>
                <option value="carregando" <?php echo ($editRobot['status'] ?? '') === 'carregando' ? 'selected' : ''; ?>>Carregando</option>
                <option value="manutencao" <?php echo ($editRobot['status'] ?? '') === 'manutencao' ? 'selected' : ''; ?>>Em Manutenção</option>
            </select>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <input type="number" step="0.1" name="km_limpos" placeholder="KM Limpos" value="<?php echo htmlspecialchars($editRobot['km_limpos'] ?? 0); ?>" required class="px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            <input type="number" name="lixo_coletado" placeholder="Lixo Coletado (kg)" value="<?php echo htmlspecialchars($editRobot['lixo_coletado'] ?? 0); ?>" required class="px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
        </div>

        <div class="flex gap-4">
            <button type="submit" name="submit_robot" class="px-6 py-3 font-bold text-white hover:opacity-90 transition-colors btn-flat" style="background-color: var(--accent-cyan);">
                <?php echo $editRobot ? 'SALVAR ALTERAÇÕES' : 'ADICIONAR ROBÔ'; ?>
            </button>
            <?php if ($editRobot): ?>
                <a href="robots.php" class="px-6 py-3 text-gray-700 border-2 border-gray-300 hover:bg-gray-200 transition-colors btn-flat">CANCELAR EDIÇÃO</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- Tabela de Robôs -->
<div class="bg-white shadow p-6 btn-flat">
    <h3 class="text-xl font-bold mb-4" style="color: var(--primary-navy);">Frota Atual</h3>
    <div class="overflow-x-auto">
        <table class="w-full whitespace-nowrap">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Robô</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Patrocinador</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Impacto (KG/KM)</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($robots as $robot): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($robot['nome']); ?></td>
                    <td class="px-4 py-3"><?php echo getStatusBadge($robot['status']); ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo htmlspecialchars($robot['patrocinador'] ?: 'N/A'); ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo formatNumber($robot['lixo_coletado']) . ' kg / ' . formatDecimal($robot['km_limpos']) . ' km'; ?></td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="robots.php?edit=<?php echo $robot['id']; ?>" class="text-sm font-semibold text-blue-600 hover:text-blue-800">Editar</a>
                        <a href="robots.php?delete=<?php echo $robot['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir o robô <?php echo htmlspecialchars($robot['nome']); ?>?');" class="text-sm font-semibold text-red-600 hover:text-red-800">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
