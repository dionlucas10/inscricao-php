<?php
require_once '../config.php';
require_once 'auth.php';

verificarNivel('editor');

$mensagem = '';
$tipo_mensagem = '';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: inscricoes.php');
    exit;
}

// Buscar inscrição
$sql = "SELECT * FROM inscricoes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: inscricoes.php');
    exit;
}

$inscricao = $result->fetch_assoc();
$stmt->close();

// Processar edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $tipo_participante = trim($_POST['tipo_participante'] ?? '');
    $interesses = isset($_POST['interesses']) ? json_encode($_POST['interesses']) : '[]';
    $mensagem_texto = trim($_POST['mensagem'] ?? '');

    if (empty($nome) || empty($email) || empty($telefone) || empty($cidade) || empty($tipo_participante)) {
        $mensagem = 'Por favor, preencha todos os campos obrigatórios!';
        $tipo_mensagem = 'danger';
    } else {
        $sql_update = "UPDATE inscricoes SET nome = ?, email = ?, telefone = ?, cidade = ?, tipo_participante = ?, interesses = ?, mensagem = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param('sssssssi', $nome, $email, $telefone, $cidade, $tipo_participante, $interesses, $mensagem_texto, $id);

        if ($stmt->execute()) {
            $mensagem = 'Inscrição atualizada com sucesso!';
            $tipo_mensagem = 'success';

            // Recarregar dados
            $stmt->close();
            $stmt = $conn->prepare("SELECT * FROM inscricoes WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $inscricao = $stmt->get_result()->fetch_assoc();
        } else {
            $mensagem = 'Erro ao atualizar inscrição: ' . $stmt->error;
            $tipo_mensagem = 'danger';
        }
        $stmt->close();
    }
}

// Dados para os selects
$tiposParticipantes = [
    'estudante' => 'Estudante',
    'profissional' => 'Profissional',
    'empreendedor' => 'Empreendedor',
    'entusiasta' => 'Entusiasta de Tecnologia'
];

$interesses_lista = [
    'web' => 'Desenvolvimento Web',
    'mobile' => 'Desenvolvimento Mobile',
    'design' => 'Design UX/UI',
    'dados' => 'Ciência de Dados',
    'cloud' => 'Cloud Computing',
    'ia' => 'Inteligência Artificial'
];

$cidades = [
    'sao-paulo' => 'São Paulo',
    'rio-janeiro' => 'Rio de Janeiro',
    'minas-gerais' => 'Minas Gerais',
    'brasilia' => 'Brasília',
    'salvador' => 'Salvador',
    'recife' => 'Recife',
    'outro' => 'Outra cidade'
];

$interesses_selecionados = json_decode($inscricao['interesses'] ?? '[]', true) ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Inscrição - TechConf 2026 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-warning text-dark py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0 h4">
                        <i class="bi bi-pencil"></i> Editar Inscrição
                    </h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="inscricoes.php" class="btn btn-secondary btn-sm me-2">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                    <a href="logout.php" class="btn btn-outline-dark btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-4">
        <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
            <?php echo $mensagem; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Editar Inscrição #<?php echo $inscricao['id']; ?></h5>
                        <small class="text-muted">Inscrito em: <?php echo date('d/m/Y H:i', strtotime($inscricao['data_inscricao'])); ?></small>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <!-- Nome -->
                            <div class="mb-3">
                                <label for="nome" class="form-label fw-semibold">Nome Completo *</label>
                                <input type="text" class="form-control" id="nome" name="nome"
                                       value="<?php echo htmlspecialchars($inscricao['nome']); ?>" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">E-mail *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?php echo htmlspecialchars($inscricao['email']); ?>" required>
                            </div>

                            <!-- Telefone -->
                            <div class="mb-3">
                                <label for="telefone" class="form-label fw-semibold">Telefone *</label>
                                <input type="tel" class="form-control" id="telefone" name="telefone"
                                       value="<?php echo htmlspecialchars($inscricao['telefone']); ?>" required>
                            </div>

                            <!-- Cidade -->
                            <div class="mb-3">
                                <label for="cidade" class="form-label fw-semibold">Cidade *</label>
                                <select class="form-select" id="cidade" name="cidade" required>
                                    <option value="">Selecione uma cidade</option>
                                    <?php foreach ($cidades as $key => $cidade): ?>
                                        <option value="<?php echo $key; ?>" <?php echo $inscricao['cidade'] === $key ? 'selected' : ''; ?>>
                                            <?php echo $cidade; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Tipo de Participante -->
                            <div class="mb-3">
                                <label for="tipo_participante" class="form-label fw-semibold">Tipo de Participante *</label>
                                <select class="form-select" id="tipo_participante" name="tipo_participante" required>
                                    <option value="">Selecione um tipo</option>
                                    <?php foreach ($tiposParticipantes as $key => $tipo): ?>
                                        <option value="<?php echo $key; ?>" <?php echo $inscricao['tipo_participante'] === $key ? 'selected' : ''; ?>>
                                            <?php echo $tipo; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Interesses -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Interesses</label>
                                <div class="row g-2">
                                    <?php foreach ($interesses_lista as $key => $interesse): ?>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="interesse_<?php echo $key; ?>" name="interesses[]" value="<?php echo $key; ?>"
                                                       <?php echo in_array($key, $interesses_selecionados) ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="interesse_<?php echo $key; ?>">
                                                    <?php echo $interesse; ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Mensagem -->
                            <div class="mb-4">
                                <label for="mensagem" class="form-label fw-semibold">Mensagem/Observações</label>
                                <textarea class="form-control" id="mensagem" name="mensagem" rows="4"><?php echo htmlspecialchars($inscricao['mensagem'] ?? ''); ?></textarea>
                            </div>

                            <!-- Botões -->
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-lg"></i> Salvar Alterações
                                </button>
                                <a href="ver-inscricao.php?id=<?php echo $inscricao['id']; ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-eye"></i> Ver Detalhes
                                </a>
                                <a href="inscricoes.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Voltar à Lista
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>