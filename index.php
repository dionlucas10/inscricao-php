<?php
// Conexão com banco de dados
require_once __DIR__ . '/config.php';

// Variáveis para mensagens
$mensagem = '';
$tipo_mensagem = '';

// Processar formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nomeCompleto'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $cidade = trim($_POST['cidade'] ?? '');
    $tipo_participante = trim($_POST['tipoParticipante'] ?? '');
    $interessesData = $_POST['interesses'] ?? [];
    if (!is_array($interessesData)) {
        $interessesData = [$interessesData];
    }
    $interesses = json_encode(array_values($interessesData));
    $mensagem_texto = trim($_POST['mensagem'] ?? '');

    // Validação básica
    if (empty($nome) || empty($email) || empty($telefone) || empty($cidade) || empty($tipo_participante)) {
        $mensagem = 'Por favor, preencha todos os campos obrigatórios!';
        $tipo_mensagem = 'danger';
    } else {
        // Preparar inscrição
        $sql = "INSERT INTO inscricoes (nome, email, telefone, cidade, tipo_participante, interesses, mensagem) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param('sssssss', $nome, $email, $telefone, $cidade, $tipo_participante, $interesses, $mensagem_texto);

            if ($stmt->execute()) {
                $mensagem = '✓ Inscrição realizada com sucesso! Obrigado por se inscrever na FutureTech 2026!';
                $tipo_mensagem = 'success';
            } else {
                $mensagem = 'Erro ao inscrever. Tente novamente mais tarde.';
                $tipo_mensagem = 'danger';
            }
            $stmt->close();
        } else {
            $mensagem = 'Erro na conexão com o banco de dados.';
            $tipo_mensagem = 'danger';
        }
    }
}

// Dados para renderizar com foreach
$tiposParticipantes = [
    'estudante' => 'Estudante',
    'profissional' => 'Profissional',
    'empreendedor' => 'Empreendedor',
    'entusiasta' => 'Entusiasta de Tecnologia'
];

$interesses = [
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
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FutureTech 2026 - Inscrição</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex flex-column">
        <!-- Header -->
        <header class="bg-gradient py-4 text-white">
            <div class="container text-center">
                <img src="./assets/img/logo1.png" alt="Logo" class="logo-cabecalho mb-3">
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow-1 py-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <!-- Event Presentation -->
                        <section class="card mb-5 shadow-sm border-0">
                            <div class="card-body p-4">
                                <h2 class="card-title mb-3 text-primary">
                                    <i class="bi bi-info-circle"></i> Sobre o Evento
                                </h2>
                                <h2 class="empresa-titulo">FutureTech 2026</h2>
                                <p class="card-text mb-3">
                                    Um evento que conecta inovação, tecnologia e futuro. 
                                    Reunindo especialistas, empresas e entusiastas do Brasil inteiro, 
                                    a conferência traz palestras, networking e experiências imersivas sobre as 
                                    tendências que estão transformando o mundo.
                                </p>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                                <i class="bi bi-calendar-event"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="mb-1">Datas</h5>
                                                <p class="mb-0 small text-muted">25-27 de Junho de 2026</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                                <i class="bi bi-geo-alt"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="mb-1">Local</h5>
                                                <p class="mb-0 small text-muted">Centro de Convenções - São Paulo</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                                <i class="bi bi-people"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="mb-1">Público</h5>
                                                <p class="mb-0 small text-muted">+5.000 participantes esperados</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                                <i class="bi bi-star"></i>
                                            </div>
                                            <div class="ms-3">
                                                <h5 class="mb-1">Highlights</h5>
                                                <p class="mb-0 small text-muted">Palestras, workshops e networking</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Mensagem de Resposta -->
                        <?php if ($mensagem): ?>
                            <div class="alert alert-<?php echo $tipo_mensagem; ?> alert-dismissible fade show" role="alert">
                                <?php echo $mensagem; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Registration Form -->
                        <section class="card shadow-sm border-0">
                            <div class="card-body p-4">
                                <h2 class="card-title mb-4 text-primary">
                                    <i class="bi bi-clipboard-check"></i> Formulário de Inscrição
                                </h2>

                                <form id="formularioInscricao" method="POST">
                                    <!-- Nome Completo -->
                                    <div class="mb-3">
                                        <label for="nomeCompleto" class="form-label fw-semibold">Nome Completo *</label>
                                        <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" placeholder="Digite seu nome completo" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe seu nome completo.
                                        </div>
                                    </div>

                                    <!-- E-mail -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">E-mail *</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="seu.email@exemplo.com" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe um e-mail válido.
                                        </div>
                                    </div>

                                    <!-- Telefone -->
                                    <div class="mb-3">
                                        <label for="telefone" class="form-label fw-semibold">Telefone *</label>
                                        <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(11) 99999-9999" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe um telefone válido.
                                        </div>
                                    </div>

                                    <!-- Cidade -->
                                    <div class="mb-3">
                                        <label for="cidade" class="form-label fw-semibold">Cidade *</label>
                                        <select class="form-select" id="cidade" name="cidade" required>
                                            <option value="">Selecione uma cidade</option>
                                            <?php foreach ($cidades as $key => $cidade): ?>
                                                <option value="<?php echo $key; ?>"><?php echo $cidade; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, selecione uma cidade.
                                        </div>
                                    </div>

                                    <!-- Tipo de Participante -->
                                    <div class="mb-3">
                                        <label for="tipoParticipante" class="form-label fw-semibold">Tipo de Participante *</label>
                                        <select class="form-select" id="tipoParticipante" name="tipoParticipante" required>
                                            <option value="">Selecione um tipo</option>
                                            <?php foreach ($tiposParticipantes as $key => $tipo): ?>
                                                <option value="<?php echo $key; ?>"><?php echo $tipo; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, selecione um tipo de participante.
                                        </div>
                                    </div>

                                    <!-- Campos para Empreendedor -->
                                    <div id="camposEmpreendedor" class="mb-3" style="display: none;">
                                        <div class="card bg-light border-info">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3 text-info">
                                                    <i class="bi bi-briefcase"></i> Informações Adicionais - Empreendedor
                                                </h5>

                                                <div class="mb-3">
                                                    <label for="nomeEmpresa" class="form-label fw-semibold">Nome da Empresa</label>
                                                    <input type="text" class="form-control" id="nomeEmpresa" name="nomeEmpresa" placeholder="Sua empresa">
                                                </div>

                                                <div class="mb-0">
                                                    <label for="areaAtuacao" class="form-label fw-semibold">Área de Atuação</label>
                                                    <input type="text" class="form-control" id="areaAtuacao" name="areaAtuacao" placeholder="Ex: SaaS, E-commerce, etc.">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Interesses -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold mb-3">
                                            Interesses no Evento (máximo 3 *)
                                            <span class="badge bg-info ms-2" id="contagemInteresse">0/3</span>
                                        </label>
                                        <div class="row g-2">
                                            <?php foreach ($interesses as $key => $interesse): ?>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input interesse-item" type="checkbox" id="interesse_<?php echo $key; ?>" name="interesses[]" value="<?php echo $key; ?>">
                                                        <label class="form-check-label" for="interesse_<?php echo $key; ?>">
                                                            <?php echo $interesse; ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="invalid-feedback d-block" id="erroInteresse" style="display: none;">
                                            Selecione no máximo 3 interesses.
                                        </div>
                                    </div>

                                    <!-- Mensagem -->
                                    <div class="mb-3">
                                        <label for="mensagem" class="form-label fw-semibold">Mensagem/Observações</label>
                                        <textarea class="form-control" id="mensagem" name="mensagem" rows="4" placeholder="Conte-nos sobre você, suas expectativas..."></textarea>
                                        <div class="form-text mt-2">
                                            <small id="contagemCaracteres">0</small> / <small>500</small> caracteres
                                        </div>
                                    </div>

                                    <!-- Termos -->
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="termos" name="termos" required>
                                            <label class="form-check-label" for="termos">
                                                Aceito os <a href="#" class="text-decoration-none">termos e condições</a> do evento *
                                            </label>
                                            <div class="invalid-feedback">
                                                Você deve aceitar os termos e condições.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Botões -->
                                    <div class="d-grid gap-2 d-md-flex">
                                        <button type="button" id="botaoResumo" class="btn btn-outline-primary btn-lg">
                                            <i class="bi bi-eye"></i> Visualizar Resumo
                                        </button>
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="bi bi-check-lg"></i> Enviar Inscrição
                                        </button>
                                        <button type="button" class="btn btn-danger btn-lg" onclick="resetForm()">
                                            <i class="bi bi-arrow-clockwise"></i> Limpar Tudo
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-white mt-5 py-4">
            <div class="container text-center">
                <p class="mb-0">&copy; 2026 TechConf. Todos os direitos reservados.</p>
            </div>
        </footer>
    </div>

    <!-- Modal de Resumo -->
    <div class="modal fade" id="modalResumo" tabindex="-1" aria-labelledby="rotuloModalResumo" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="rotuloModalResumo">
                        <i class="bi bi-check-circle"></i> Resumo da Sua Inscrição
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="conteudoResumo">
                    <!-- Preenchido pelo JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Editar Inscrição</button>
                    <button type="button" class="btn btn-success">
                        <i class="bi bi-download"></i> Baixar Resumo (em breve)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>

</html>