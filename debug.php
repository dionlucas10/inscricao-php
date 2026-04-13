<?php
// Habilita exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

echo "<h1>🔍 Debug - Verificar Dados</h1>";
echo "<hr>";

// Verifica se há POST
echo "<h2>1️⃣ Verificação de REQUEST</h2>";
echo "Método: " . $_SERVER['REQUEST_METHOD'] . "<br>";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2 style='color:green'>✓ POST Recebido!</h2>";
    echo "<h3>Dados recebidos:</h3>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    // Tenta inserir manualmente
    echo "<h3>Tentando inserir no banco:</h3>";
    $nome = trim($_POST['fullName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['phone'] ?? '');
    $cidade = trim($_POST['city'] ?? '');
    $tipo_participante = trim($_POST['participantType'] ?? '');
    $interesses = isset($_POST['interesses']) ? json_encode($_POST['interesses']) : '[]';
    $mensagem_texto = trim($_POST['message'] ?? '');
    
    echo "Nome: $nome<br>";
    echo "Email: $email<br>";
    echo "Telefone: $telefone<br>";
    echo "Cidade: $cidade<br>";
    echo "Tipo: $tipo_participante<br>";
    echo "Interesses: $interesses<br>";
    
    if (empty($nome) || empty($email) || empty($telefone) || empty($cidade) || empty($tipo_participante)) {
        echo "<div style='color:red'><strong>❌ Campos obrigatórios faltando!</strong></div>";
    } else {
        $sql = "INSERT INTO inscricoes (nome, email, telefone, cidade, tipo_participante, interesses, mensagem) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param('sssssss', $nome, $email, $telefone, $cidade, $tipo_participante, $interesses, $mensagem_texto);
            
            if ($stmt->execute()) {
                echo "<div style='color:green'><strong>✓ Dados inseridos com sucesso!</strong></div>";
            } else {
                echo "<div style='color:red'><strong>❌ Erro ao inserir: " . $stmt->error . "</strong></div>";
            }
            $stmt->close();
        } else {
            echo "<div style='color:red'><strong>❌ Erro na preparação: " . $conn->error . "</strong></div>";
        }
    }
} else {
    echo "<div style='color:orange'><strong>⚠️ Nenhum POST recebido - Preencha e envie o formulário primeiro!</strong></div>";
}

echo "<hr>";

// Testa conexão com banco
echo "<h2>2️⃣ Teste de Conexão</h2>";
echo "Host: localhost<br>";
echo "Banco: techconf_2026<br>";
echo "Status: ";
if ($conn->connect_error) {
    echo "<span style='color:red'>❌ " . $conn->connect_error . "</span>";
} else {
    echo "<span style='color:green'>✓ Conectado</span>";
}
echo "<br>";

$result = $conn->query("SELECT COUNT(*) as total FROM inscricoes");
if ($result) {
    $row = $result->fetch_assoc();
    echo "<strong>Total de inscrições no banco: " . $row['total'] . "</strong><br>";
} else {
    echo "<span style='color:red'>❌ Erro: " . $conn->error . "</span>";
}

// Mostra todas as inscrições
echo "<hr>";
echo "<h2>3️⃣ Inscrições Salvas no Banco</h2>";
$result = $conn->query("SELECT * FROM inscricoes ORDER BY data_inscricao DESC");
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr style='background:#333;color:white'><th>ID</th><th>Nome</th><th>Email</th><th>Telefone</th><th>Tipo</th><th>Data</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['nome'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['telefone'] . "</td>";
        echo "<td>" . $row['tipo_participante'] . "</td>";
        echo "<td>" . $row['data_inscricao'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<span style='color:orange'>⚠️ Nenhuma inscrição encontrada ainda.</span>";
}

echo "<hr>";
echo "<h3><a href='index.php'>← Voltar para formulário</a></h3>";
?>
