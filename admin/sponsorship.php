<?php
$pageTitle = 'Propostas de Patrocínio';
require_once '../includes/admin_header.php';
require_once '../includes/config.php';

$db = Database::getInstance()->getConnection();
$message = '';
$isError = false;

// Processar atualização de status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = sanitize($_POST['request_id']);
    $newStatus = sanitize($_POST['new_status']);

    try {
        $stmt = $db->prepare("UPDATE sponsorship_requests SET status = ? WHERE id = ?");
        $stmt->execute([$newStatus, $id]);
        $message = "Status da proposta #{$id} atualizado para '{$newStatus}'.";
    } catch (Exception $e) {
        $message = "Erro ao atualizar status: " . $e->getMessage();
        $isError = true;
    }
}

// Buscar todas as propostas
$requests = $db->query("SELECT * FROM sponsorship_requests ORDER BY created_at DESC")->fetchAll();
?>

<div class="mb-8">
    <h2 class="text-3xl font-bold mb-2" style="color: var(--primary-navy);">Gerenciar Propostas</h2>
    <p class="text-gray-600">Visualizar e gerenciar as solicitações de patrocínio recebidas.</p>
</div>

<?php if ($message): ?>
    <div class="p-4 mb-4 text-sm font-semibold btn-flat <?php echo $isError ? 'bg-red-100 text-red-700 border-l-4 border-red-500' : 'bg-green-100 text-green-700 border-l-4 border-green-500'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="bg-white shadow p-6 btn-flat">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Empresa</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Contato</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Orçamento</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Mensagem</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Data</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Ação</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($requests as $request): ?>
                <tr class="hover:bg-gray-50 align-top">
                    <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($request['empresa']); ?></td>
                    <td class="px-4 py-3 text-sm">
                        <?php echo htmlspecialchars($request['nome']); ?><br>
                        <span class="text-gray-500"><?php echo htmlspecialchars($request['email']); ?></span>
                    </td>
                    <td class="px-4 py-3 text-sm"><?php echo htmlspecialchars($request['orcamento']); ?></td>
                    <td class="px-4 py-3 text-sm max-w-xs overflow-hidden text-ellipsis"><?php echo nl2br(htmlspecialchars(substr($request['mensagem'], 0, 150))) . '...'; ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo date('d/m/Y', strtotime($request['created_at'])); ?></td>
                    <td class="px-4 py-3"><?php echo getStatusBadge($request['status']); ?></td>
                    <td class="px-4 py-3">
                        <form method="POST" class="space-y-1">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <select name="new_status" class="text-xs border border-gray-300 p-1 btn-flat">
                                <option value="pendente" <?php echo $request['status'] === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                                <option value="em_analise" <?php echo $request['status'] === 'em_analise' ? 'selected' : ''; ?>>Em Análise</option>
                                <option value="aprovado" <?php echo $request['status'] === 'aprovado' ? 'selected' : ''; ?>>Aprovado</option>
                                <option value="rejeitado" <?php echo $request['status'] === 'rejeitado' ? 'selected' : ''; ?>>Rejeitado</option>
                            </select>
                            <button type="submit" name="update_status" class="w-full text-xs bg-gray-200 hover:bg-gray-300 py-1 px-2 mt-1 btn-flat">Salvar</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
