<?php
require_once '../config.php';
require_once 'auth.php';

verificarNivel('editor');

// Buscar estatísticas
$total_inscricoes = $conn->query("SELECT COUNT(*) as total FROM inscricoes")->fetch_assoc()['total'];
$inscricoes_hoje = $conn->query("SELECT COUNT(*) as total FROM inscricoes WHERE DATE(data_inscricao) = CURDATE()")->fetch_assoc()['total'];

// Buscar inscrições recentes
$inscricoes_recentes = $conn->query("SELECT * FROM inscricoes ORDER BY data_inscricao DESC LIMIT 5");

// Contar por tipo de participante
$tipos = $conn->query("SELECT tipo_participante, COUNT(*) as total FROM inscricoes GROUP BY tipo_participante");
$estatisticas_tipos = [];
while ($row = $tipos->fetch_assoc()) {
    $estatisticas_tipos[$row['tipo_participante']] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TechConf 2026 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .stat-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="dashboard-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </h1>
                    <p class="mb-0">TechConf 2026 - Painel Administrativo</p>
                </div>
                <div class="col-md-6 text-end">
                    <span class="me-3">Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                    <a href="logout.php" class="btn btn-light">
                        <i class="bi bi-box-arrow-right"></i> Sair
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Estatísticas -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card stat-card border-success">
                    <div class="card-body text-center">
                        <div class="stat-number text-success"><?php echo $total_inscricoes; ?></div>
                        <h5 class="card-title">Total de Inscrições</h5>
                        <i class="bi bi-people-fill text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card border-info">
                    <div class="card-body text-center">
                        <div class="stat-number text-info"><?php echo $inscricoes_hoje; ?></div>
                        <h5 class="card-title">Inscrições Hoje</h5>
                        <i class="bi bi-calendar-check text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card border-warning">
                    <div class="card-body text-center">
                        <div class="stat-number text-warning"><?php echo count($estatisticas_tipos); ?></div>
                        <h5 class="card-title">Tipos de Participantes</h5>
                        <i class="bi bi-person-badge text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu de Ações -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-gear"></i> Ações Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <a href="inscricoes.php" class="btn btn-primary w-100">
                                    <i class="bi bi-list-ul"></i><br>
                                    Ver Todas Inscrições
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="relatorios.php" class="btn btn-success w-100">
                                    <i class="bi bi-bar-chart"></i><br>
                                    Relatórios
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="exportar.php" class="btn btn-info w-100">
                                    <i class="bi bi-download"></i><br>
                                    Exportar Dados
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="usuarios.php" class="btn btn-warning w-100">
                                    <i class="bi bi-people"></i><br>
                                    Gerenciar Usuários
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inscrições Recentes -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clock-history"></i> Inscrições Recentes
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($inscricoes_recentes->num_rows > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Tipo</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($inscricao = $inscricoes_recentes->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($inscricao['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($inscricao['email']); ?></td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?php echo htmlspecialchars($inscricao['tipo_participante']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($inscricao['data_inscricao'])); ?></td>
                                        <td>
                                            <a href="ver-inscricao.php?id=<?php echo $inscricao['id']; ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="editar-inscricao.php?id=<?php echo $inscricao['id']; ?>" class="btn btn-sm btn-outline-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <p class="text-muted text-center">Nenhuma inscrição encontrada ainda.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Estatísticas por Tipo -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-pie-chart"></i> Por Tipo de Participante
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (count($estatisticas_tipos) > 0): ?>
                        <?php foreach ($estatisticas_tipos as $tipo => $total): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><?php echo htmlspecialchars($tipo); ?></span>
                            <span class="badge bg-secondary"><?php echo $total; ?></span>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <p class="text-muted text-center">Nenhum dado disponível.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>