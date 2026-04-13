<?php
require_once '../config.php';
require_once 'auth.php';

verificarNivel('admin'); // Só admin pode excluir

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$id) {
    header('Location: inscricoes.php');
    exit;
}

// Buscar inscrição para confirmar
$sql = "SELECT nome FROM inscricoes WHERE id = ?";
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

// Processar exclusão
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql_delete = "DELETE FROM inscricoes WHERE id = ?";
    $stmt = $conn->prepare($sql_delete);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        $stmt->close();
        header('Location: inscricoes.php?excluido=1');
        exit;
    } else {
        $erro = 'Erro ao excluir inscrição: ' . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Inscrição - TechConf 2026 Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Confirmar Exclusão
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <?php if (isset($erro)): ?>
                        <div class="alert alert-danger">
                            <?php echo $erro; ?>
                        </div>
                        <?php endif; ?>

                        <div class="alert alert-warning">
                            <h6>Tem certeza que deseja excluir esta inscrição?</h6>
                            <p class="mb-1"><strong>Nome:</strong> <?php echo htmlspecialchars($inscricao['nome']); ?></p>
                            <p class="mb-1"><strong>ID:</strong> <?php echo $id; ?></p>
                            <p class="text-danger mb-0"><strong>Esta ação não pode ser desfeita!</strong></p>
                        </div>

                        <form method="POST">
                            <div class="d-grid gap-2 d-md-flex justify-content-center">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash"></i> Sim, Excluir
                                </button>
                                <a href="inscricoes.php" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Cancelar
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