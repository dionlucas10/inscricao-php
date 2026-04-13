<?php
require_once '../config.php';
require_once 'auth.php';

verificarNivel('admin');

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nivel = $_POST['nivel'] ?? 'editor';

    if (!$usuario || !$senha || !$nome || !$email) {
        $mensagem = 'Preencha todos os campos para criar o usuário.';
        $tipo_mensagem = 'danger';
    } else {
        $check_user = $conn->prepare("SELECT COUNT(*) as total FROM usuarios WHERE usuario = ?");
        $check_user->bind_param('s', $usuario);
        $check_user->execute();
        $existing = $check_user->get_result()->fetch_assoc();
        $check_user->close();

        if ($existing['total'] > 0) {
            $mensagem = 'O usuário já existe. Escolha outro nome de usuário.';
            $tipo_mensagem = 'danger';
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (usuario, senha, nome, email, nivel) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssss', $usuario, $senha_hash, $nome, $email, $nivel);

            if ($stmt->execute()) {
                $mensagem = 'Usuário criado com sucesso!';
                $tipo_mensagem = 'success';
            } else {
                $mensagem = 'Erro ao criar usuário: ' . $stmt->error;
                $tipo_mensagem = 'danger';
            }
            $stmt->close();
        }
    }
}

$usuarios = $conn->query("SELECT id, usuario, nome, email, nivel, ativo, data_criacao FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários - TechConf 2026 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="bg-primary text-white py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0"><i class="bi bi-people"></i> Gerenciar Usuários</h1>
                <small>Painel administrativo - apenas admin</small>
            </div>
            <div>
                <a href="dashboard.php" class="btn btn-light btn-sm me-2"><i class="bi bi-house"></i> Dashboard</a>
                <a href="logout.php" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right"></i> Sair</a>
            </div>
        </div>
    </header>

    <div class="container my-4">
        <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
            <?php echo $mensagem; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Criar Novo Usuário</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuário</label>
                                <input type="text" name="usuario" id="usuario" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" name="senha" id="senha" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" name="nome" id="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="nivel" class="form-label">Nível</label>
                                <select name="nivel" id="nivel" class="form-select">
                                    <option value="editor">Editor</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Criar usuário</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Lista de Usuários</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($usuarios && $usuarios->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Usuário</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Nível</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($user = $usuarios->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo htmlspecialchars($user['usuario']); ?></td>
                                        <td><?php echo htmlspecialchars($user['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['nivel']); ?></td>
                                        <td><?php echo $user['ativo'] ? 'Ativo' : 'Inativo'; ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="text-muted">Nenhum usuário encontrado.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>