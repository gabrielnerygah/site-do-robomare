<?php
$pageTitle = 'Gerenciar Usuários';
require_once '../includes/admin_header.php';
require_once '../includes/db.php';
$auth->requireAdmin(); // APENAS ADMIN PODE ACESSAR

$db = Database::getInstance()->getConnection();
$message = '';
$isError = false;

// Processar formulário (Adicionar novo usuário)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = sanitize($_POST['username']);
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    $role = sanitize($_POST['role']);

    if (empty($password) || strlen($password) < 6) {
        $message = "A senha deve ter pelo menos 6 caracteres.";
        $isError = true;
    } else {
        if ($auth->register($username, $email, $password, $role)) {
            $message = "Usuário '{$username}' criado com sucesso!";
        } else {
            $message = "Erro ao criar usuário. O nome de usuário ou email já pode existir.";
            $isError = true;
        }
    }
}

// Processar exclusão
if (isset($_GET['delete'])) {
    $id = sanitize($_GET['delete']);
    try {
        if ((int)$id !== (int)$_SESSION['user_id']) { // Previne que o admin apague a própria conta
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $message = "Usuário excluído com sucesso!";
        } else {
            $message = "Você não pode excluir sua própria conta.";
            $isError = true;
        }
    } catch (Exception $e) {
        $message = "Erro ao excluir: " . $e->getMessage();
        $isError = true;
    }
}

// Listar todos os usuários
$users = $db->query("SELECT id, username, email, role, created_at, last_login FROM users ORDER BY role, username")->fetchAll();
?>

<div class="mb-8">
    <h2 class="text-3xl font-bold mb-2" style="color: var(--primary-navy);">Gerenciar Usuários Admin</h2>
    <p class="text-gray-600">Criar e remover contas de acesso ao painel.</p>
</div>

<?php if ($message): ?>
    <div class="p-4 mb-4 text-sm font-semibold btn-flat <?php echo $isError ? 'bg-red-100 text-red-700 border-l-4 border-red-500' : 'bg-green-100 text-green-700 border-l-4 border-green-500'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<!-- Formulário de Criação -->
<div class="bg-white shadow p-6 mb-8">
    <h3 class="text-xl font-bold mb-4" style="color: var(--primary-navy);">Adicionar Novo Usuário</h3>
    <form method="POST" class="space-y-4">
        <div class="grid md:grid-cols-4 gap-4">
            <input type="text" name="username" placeholder="Nome de Usuário" required class="px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            <input type="email" name="email" placeholder="E-mail" required class="px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            <input type="password" name="password" placeholder="Senha (mín. 6 caracteres)" required class="px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
            <select name="role" required class="w-full px-4 py-2 border-2 border-gray-300 focus:border-[#00A896] outline-none btn-flat">
                <option value="editor">Editor (Pode alterar dados)</option>
                <option value="admin">Administrador (Pode gerenciar usuários)</option>
            </select>
        </div>
        <button type="submit" name="add_user" class="px-6 py-3 font-bold text-white hover:opacity-90 transition-colors btn-flat" style="background-color: var(--accent-cyan);">
            CRIAR CONTA
        </button>
    </form>
</div>

<!-- Tabela de Usuários -->
<div class="bg-white shadow p-6">
    <h3 class="text-xl font-bold mb-4" style="color: var(--primary-navy);">Contas Registradas</h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Username</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Função</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Último Login</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php foreach ($users as $user): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold"><?php echo htmlspecialchars($user['username']); ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td class="px-4 py-3 text-sm uppercase"><?php echo htmlspecialchars($user['role']); ?></td>
                    <td class="px-4 py-3 text-sm"><?php echo $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Nunca'; ?></td>
                    <td class="px-4 py-3">
                        <?php if ((int)$user['id'] !== (int)$_SESSION['user_id']): ?>
                            <a href="users.php?delete=<?php echo $user['id']; ?>" onclick="return confirm('Tem certeza que deseja excluir o usuário <?php echo htmlspecialchars($user['username']); ?>?');" class="text-sm font-semibold text-red-600 hover:text-red-800">Excluir</a>
                        <?php else: ?>
                            <span class="text-sm text-gray-400">Você</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
