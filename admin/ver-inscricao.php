<?php
require_once '../config.php';
require_once 'auth.php';

verificarNivel('editor');

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

// Mapas para exibição
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
    <title>Ver Inscrição - TechConf 2026 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-info text-white py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0 h4">
                        <i class="bi bi-eye"></i> Detalhes da Inscrição
                    </h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="inscricoes.php" class="btn btn-light btn-sm me-2">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                    <a href="editar-inscricao.php?id=<?php echo $inscricao['id']; ?>" class="btn btn-warning btn-sm me-2">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Inscrição #<?php echo $inscricao['id']; ?></h5>
                        <small>Data da inscrição: <?php echo date('d/m/Y H:i:s', strtotime($inscricao['data_inscricao'])); ?></small>
                    </div>
                    <div class="card-body">
                        <!-- Dados Pessoais -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-person-fill"></i> Dados Pessoais
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Nome:</strong><br>
                                    <?php echo htmlspecialchars($inscricao['nome']); ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>E-mail:</strong><br>
                                    <?php echo htmlspecialchars($inscricao['email']); ?>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <strong>Telefone:</strong><br>
                                    <?php echo htmlspecialchars($inscricao['telefone']); ?>
                                </div>
                                <div class="col-md-6">
                                    <strong>Cidade:</strong><br>
                                    <?php echo htmlspecialchars($cidades[$inscricao['cidade']] ?? $inscricao['cidade']); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Tipo de Participante -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-briefcase-fill"></i> Tipo de Participante
                            </h6>
                            <span class="badge bg-primary fs-6">
                                <?php echo htmlspecialchars($tiposParticipantes[$inscricao['tipo_participante']] ?? $inscricao['tipo_participante']); ?>
                            </span>
                        </div>

                        <!-- Interesses -->
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-star-fill"></i> Interesses
                            </h6>
                            <?php if (!empty($interesses_selecionados)): ?>
                                <div class="d-flex flex-wrap gap-2">
                                    <?php foreach ($interesses_selecionados as $interesse_key): ?>
                                        <span class="badge bg-success">
                                            <?php echo htmlspecialchars($interesses_lista[$interesse_key] ?? $interesse_key); ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted">Nenhum interesse selecionado</span>
                            <?php endif; ?>
                        </div>

                        <!-- Mensagem -->
                        <?php if (!empty($inscricao['mensagem'])): ?>
                        <div class="mb-4">
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-chat-left-text-fill"></i> Mensagem/Observações
                            </h6>
                            <div class="bg-light p-3 rounded">
                                <?php echo nl2br(htmlspecialchars($inscricao['mensagem'])); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Ações -->
                        <div class="border-top pt-3">
                            <div class="d-grid gap-2 d-md-flex">
                                <a href="editar-inscricao.php?id=<?php echo $inscricao['id']; ?>" class="btn btn-warning">
                                    <i class="bi bi-pencil"></i> Editar Inscrição
                                </a>
                                <a href="inscricoes.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Voltar à Lista
                                </a>
                                <?php if ($_SESSION['usuario_nivel'] === 'admin'): ?>
                                <button type="button" class="btn btn-danger" onclick="confirmarExclusao()">
                                    <i class="bi bi-trash"></i> Excluir Inscrição
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="confirmarExclusaoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir a inscrição de <strong><?php echo htmlspecialchars($inscricao['nome']); ?></strong>?</p>
                    <p class="text-danger">Esta ação não pode ser desfeita!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="excluir-inscricao.php?id=<?php echo $inscricao['id']; ?>" class="btn btn-danger">Excluir</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarExclusao() {
            new bootstrap.Modal(document.getElementById('confirmarExclusaoModal')).show();
        }
    </script>
</body>
</html>