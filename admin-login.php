<?php
session_start();
require_once 'config.php';

// Redirecionar se já estiver logado
if (isset($_SESSION['usuario_id'])) {
    header('Location: admin/dashboard.php');
    exit;
}

$mensagem = '';
$tipo_mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($usuario) || empty($senha)) {
        $mensagem = 'Por favor, preencha usuário e senha!';
        $tipo_mensagem = 'danger';
    } else {
        $sql = "SELECT id, usuario, senha, nome, nivel FROM usuarios WHERE usuario = ? AND ativo = TRUE";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            if (password_verify($senha, $user['senha'])) {
                // Login bem-sucedido
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['usuario_nome'] = $user['nome'];
                $_SESSION['usuario_nivel'] = $user['nivel'];

                header('Location: admin/dashboard.php');
                exit;
            } else {
                $mensagem = 'Senha incorreta!';
                $tipo_mensagem = 'danger';
            }
        } else {
            $mensagem = 'Usuário não encontrado!';
            $tipo_mensagem = 'danger';
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TechConf 2026 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }
        .login-header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h2 class="mb-2">
                    <i class="bi bi-shield-lock"></i> TechConf 2026
                </h2>
                <p class="mb-0">Painel Administrativo</p>
            </div>

            <div class="login-body">
                <?php if ($mensagem): ?>
                <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
                    <?php echo $mensagem; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="usuario" class="form-label fw-semibold">
                            <i class="bi bi-person"></i> Usuário
                        </label>
                        <input type="text" class="form-control" id="usuario" name="usuario"
                               placeholder="Digite seu usuário" required>
                    </div>

                    <div class="mb-4">
                        <label for="senha" class="form-label fw-semibold">
                            <i class="bi bi-key"></i> Senha
                        </label>
                        <input type="password" class="form-control" id="senha" name="senha"
                               placeholder="Digite sua senha" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Entrar
                        </button>
                    </div>
                </form>

                <hr class="my-4">

                <div class="text-center">
                    <small class="text-muted">
                        <strong>Usuário padrão:</strong><br>
                        Usuário: admin<br>
                        Senha: admin123
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>