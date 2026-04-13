<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<div style='background:#90EE90; padding:20px; border:2px solid green; margin:20px'>";
    echo "<h2>✓ SUCESSO! Dados recebidos e salvos!</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if ($nome && $email) {
        $sql = "INSERT INTO inscricoes (nome, email, telefone, cidade, tipo_participante) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssss', $nome, $email, $email, $email, $email);
        
        if ($stmt->execute()) {
            echo "<h3 style='color:green'>✓ Inserido no banco com ID: " . $stmt->insert_id . "</h3>";
        } else {
            echo "<h3 style='color:red'>❌ Erro: " . $stmt->error . "</h3>";
        }
        $stmt->close();
    }
    echo "</div>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teste Simples</title>
</head>
<body>
    <h1>Formulário de Teste Simples</h1>
    <form method="POST">
        <input type="text" name="nome" placeholder="Nome" required><br><br>
        <input type="email" name="email" placeholder="Email" required><br><br>
        <button type="submit">Enviar</button>
    </form>
    
    <hr>
    <h2>Inscrições no banco:</h2>
    <?php
    $result = $conn->query("SELECT * FROM inscricoes ORDER BY data_inscricao DESC LIMIT 10");
    if ($result && $result->num_rows > 0) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr style='background:#333;color:white'><th>ID</th><th>Nome</th><th>Email</th><th>Data</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>{$row['id']}</td><td>{$row['nome']}</td><td>{$row['email']}</td><td>{$row['data_inscricao']}</td></tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color:red'>Nenhuma inscrição encontrada</p>";
    }
    ?>
</body>
</html>
