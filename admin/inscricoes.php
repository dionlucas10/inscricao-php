<?php
require_once '../config.php';
require_once 'auth.php';

verificarNivel('editor');

// Filtros e paginação
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$limite = 20;
$offset = ($pagina - 1) * $limite;

$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
$filtro_cidade = isset($_GET['cidade']) ? $_GET['cidade'] : '';
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

// Construir query
$query = "SELECT * FROM inscricoes WHERE 1=1";
$count_query = "SELECT COUNT(*) as total FROM inscricoes WHERE 1=1";

$params = [];
$types = '';

if ($filtro_tipo) {
    $query .= " AND tipo_participante = ?";
    $count_query .= " AND tipo_participante = ?";
    $params[] = $filtro_tipo;
    $types .= 's';
}

if ($filtro_cidade) {
    $query .= " AND cidade = ?";
    $count_query .= " AND cidade = ?";
    $params[] = $filtro_cidade;
    $types .= 's';
}

if ($busca) {
    $query .= " AND (nome LIKE ? OR email LIKE ?)";
    $count_query .= " AND (nome LIKE ? OR email LIKE ?)";
    $params[] = "%$busca%";
    $params[] = "%$busca%";
    $types .= 'ss';
}

$query .= " ORDER BY data_inscricao DESC LIMIT ? OFFSET ?";
$params[] = $limite;
$params[] = $offset;
$types .= 'ii';

// Executar queries
$stmt = $conn->prepare($count_query);
if (!empty($params) && strlen($types) > 2) { // > 2 porque adicionamos limite e offset
    $temp_params = array_slice($params, 0, -2);
    $temp_types = substr($types, 0, -2);
    if (!empty($temp_params)) {
        $stmt->bind_param($temp_types, ...$temp_params);
    }
}
$stmt->execute();
$total_result = $stmt->get_result()->fetch_assoc()['total'];
$total_paginas = ceil($total_result / $limite);

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Buscar opções para filtros
$tipos_participantes = $conn->query("SELECT DISTINCT tipo_participante FROM inscricoes ORDER BY tipo_participante");
$cidades = $conn->query("SELECT DISTINCT cidade FROM inscricoes ORDER BY cidade");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrições - TechConf 2026 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-primary text-white py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0 h4">
                        <i class="bi bi-list-ul"></i> Gerenciar Inscrições
                    </h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="dashboard.php" class="btn btn-light btn-sm me-2">
                        <i class="bi bi-house"></i> Dashboard
                    </a>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-4">
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-funnel"></i> Filtros e Busca
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="busca" placeholder="Buscar por nome ou email"
                               value="<?php echo htmlspecialchars($busca); ?>">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="tipo">
                            <option value="">Todos os tipos</option>
                            <?php while ($tipo = $tipos_participantes->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($tipo['tipo_participante']); ?>"
                                    <?php echo $filtro_tipo === $tipo['tipo_participante'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tipo['tipo_participante']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="cidade">
                            <option value="">Todas as cidades</option>
                            <?php while ($cidade = $cidades->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($cidade['cidade']); ?>"
                                    <?php echo $filtro_cidade === $cidade['cidade'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cidade['cidade']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <a href="inscricoes.php" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Estatísticas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info">
                    <strong><?php echo $total_result; ?> inscrições encontradas</strong>
                    <?php if ($filtro_tipo || $filtro_cidade || $busca): ?>
                    (filtradas)
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tabela de Inscrições -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Lista de Inscrições</h5>
                <a href="exportar.php<?php echo $filtro_tipo ? '?tipo='.urlencode($filtro_tipo) : ''; ?>" class="btn btn-success btn-sm">
                    <i class="bi bi-download"></i> Exportar
                </a>
            </div>
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Telefone</th>
                                <th>Tipo</th>
                                <th>Cidade</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($inscricao = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $inscricao['id']; ?></td>
                                <td><?php echo htmlspecialchars($inscricao['nome']); ?></td>
                                <td><?php echo htmlspecialchars($inscricao['email']); ?></td>
                                <td><?php echo htmlspecialchars($inscricao['telefone']); ?></td>
                                <td>
                                    <span class="badge bg-primary">
                                        <?php echo htmlspecialchars($inscricao['tipo_participante']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($inscricao['cidade']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($inscricao['data_inscricao'])); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="ver-inscricao.php?id=<?php echo $inscricao['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="editar-inscricao.php?id=<?php echo $inscricao['id']; ?>" class="btn btn-sm btn-outline-warning" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Excluir"
                                                onclick="confirmarExclusao(<?php echo $inscricao['id']; ?>, '<?php echo htmlspecialchars($inscricao['nome']); ?>')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Paginação -->
                <?php if ($total_paginas > 1): ?>
                <nav aria-label="Paginação">
                    <ul class="pagination justify-content-center mt-4">
                        <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo $i === $pagina ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?><?php echo $filtro_tipo ? '&tipo='.urlencode($filtro_tipo) : ''; ?><?php echo $filtro_cidade ? '&cidade='.urlencode($filtro_cidade) : ''; ?><?php echo $busca ? '&busca='.urlencode($busca) : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Nenhuma inscrição encontrada</h5>
                    <p class="text-muted">Tente ajustar os filtros ou volte mais tarde.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="confirmarExclusaoModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmar Exclusão</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir a inscrição de <strong id="nomeExclusao"></strong>?</p>
                    <p class="text-danger">Esta ação não pode ser desfeita!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a id="btnConfirmarExclusao" href="#" class="btn btn-danger">Excluir</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmarExclusao(id, nome) {
            document.getElementById('nomeExclusao').textContent = nome;
            document.getElementById('btnConfirmarExclusao').href = 'excluir-inscricao.php?id=' + id;
            new bootstrap.Modal(document.getElementById('confirmarExclusaoModal')).show();
        }
    </script>
</body>
</html>