<?php
// Conexão com banco de dados
require_once 'config.php';

// Variáveis para mensagens
$mensagem = '';
$tipo_mensagem = '';

// Processar formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['phone'] ?? '');
    $cidade = trim($_POST['city'] ?? '');
    $tipo_participante = trim($_POST['participantType'] ?? '');
    $interesses = isset($_POST['interesses']) ? json_encode($_POST['interesses']) : '[]';
    $mensagem_texto = trim($_POST['message'] ?? '');
    
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
                $mensagem = '✓ Inscrição realizada com sucesso! Obrigado por se inscrever na TechConf 2026!';
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
    <title>TechConf 2026 - Inscrição</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container-fluid min-vh-100 d-flex flex-column">
        <!-- Header -->
        <header class="bg-gradient py-5 text-white">
            <div class="container">
                <h1 class="display-4 fw-bold mb-2">TechConf 2026</h1>
                <p class="lead mb-0">A Maior Conferência de Tecnologia do Brasil</p>
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
                                <p class="card-text mb-3">
                                    Bem-vindo à TechConf 2026! Uma conferência inovadora reunindo os melhores profissionais, 
                                    empreendedores e entusiastas de tecnologia do Brasil.
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

                                <form id="registrationForm" method="POST">
                                    <!-- Full Name -->
                                    <div class="mb-3">
                                        <label for="fullName" class="form-label fw-semibold">Nome Completo *</label>
                                        <input type="text" class="form-control" id="fullName" name="fullName" placeholder="Digite seu nome completo" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe seu nome completo.
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">E-mail *</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="seu.email@exemplo.com" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe um e-mail válido.
                                        </div>
                                    </div>

                                    <!-- Phone -->
                                    <div class="mb-3">
                                        <label for="phone" class="form-label fw-semibold">Telefone *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="(11) 99999-9999" required>
                                        <div class="invalid-feedback">
                                            Por favor, informe um telefone válido.
                                        </div>
                                    </div>

                                    <!-- City -->
                                    <div class="mb-3">
                                        <label for="city" class="form-label fw-semibold">Cidade *</label>
                                        <select class="form-select" id="city" name="city" required>
                                            <option value="">Selecione uma cidade</option>
                                            <?php foreach ($cidades as $key => $cidade): ?>
                                                <option value="<?php echo $key; ?>"><?php echo $cidade; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, selecione uma cidade.
                                        </div>
                                    </div>

                                    <!-- Participant Type -->
                                    <div class="mb-3">
                                        <label for="participantType" class="form-label fw-semibold">Tipo de Participante *</label>
                                        <select class="form-select" id="participantType" name="participantType" required>
                                            <option value="">Selecione um tipo</option>
                                            <?php foreach ($tiposParticipantes as $key => $tipo): ?>
                                                <option value="<?php echo $key; ?>"><?php echo $tipo; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Por favor, selecione um tipo de participante.
                                        </div>
                                    </div>

                                    <!-- Extra Fields for Entrepreneurs -->
                                    <div id="entrepreneurFields" class="mb-3" style="display: none;">
                                        <div class="card bg-light border-info">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3 text-info">
                                                    <i class="bi bi-briefcase"></i> Informações Adicionais - Empreendedor
                                                </h5>
                                                
                                                <div class="mb-3">
                                                    <label for="companyName" class="form-label fw-semibold">Nome da Empresa</label>
                                                    <input type="text" class="form-control" id="companyName" name="companyName" placeholder="Sua empresa">
                                                </div>

                                                <div class="mb-0">
                                                    <label for="businessArea" class="form-label fw-semibold">Área de Atuação</label>
                                                    <input type="text" class="form-control" id="businessArea" name="businessArea" placeholder="Ex: SaaS, E-commerce, etc.">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Interests -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold mb-3">
                                            Interesses no Evento (máximo 3 *) 
                                            <span class="badge bg-info ms-2" id="interestCount">0/3</span>
                                        </label>
                                        <div class="row g-2">
                                            <?php foreach ($interesses as $key => $interesse): ?>
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input interest-checkbox" type="checkbox" id="interesse_<?php echo $key; ?>" name="interesses" value="<?php echo $key; ?>">
                                                        <label class="form-check-label" for="interesse_<?php echo $key; ?>">
                                                            <?php echo $interesse; ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <div class="invalid-feedback d-block" id="interestError" style="display: none;">
                                            Selecione no máximo 3 interesses.
                                        </div>
                                    </div>

                                    <!-- Message/Observations -->
                                    <div class="mb-3">
                                        <label for="message" class="form-label fw-semibold">Mensagem/Observações</label>
                                        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Conte-nos sobre você, suas expectativas..."></textarea>
                                        <div class="form-text mt-2">
                                            <small id="charCount">0</small> / <small>500</small> caracteres
                                        </div>
                                    </div>

                                    <!-- Terms Accept -->
                                    <div class="mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                            <label class="form-check-label" for="terms">
                                                Aceito os <a href="#" class="text-decoration-none">termos e condições</a> do evento *
                                            </label>
                                            <div class="invalid-feedback">
                                                Você deve aceitar os termos e condições.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="d-grid gap-2 d-md-flex">
                                        <button type="button" id="summaryBtn" class="btn btn-outline-primary btn-lg">
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
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; 2026 TechConf. Todos os direitos reservados.</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="admin-login.php" class="text-light text-decoration-none">
                            <i class="bi bi-shield-lock"></i> Painel Administrativo
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Summary Modal -->
    <div class="modal fade" id="summaryModal" tabindex="-1" aria-labelledby="summaryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="summaryModalLabel">
                        <i class="bi bi-check-circle"></i> Resumo da Sua Inscrição
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="summaryContent">
                    <!-- Filled by JavaScript -->
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
