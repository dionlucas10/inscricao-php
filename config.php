<?php
// Configurações de conexão com o banco de dados
$host = 'localhost';
$user = 'root';
$password = ''; // Por padrão, XAMPP deixa vazio
$database = 'techconf_2026';

try {
    // Conecta sem banco de dados primeiro
    $conn = new mysqli($host, $user, $password);
    
    // Verifica se houve erro na conexão
    if ($conn->connect_error) {
        die("Erro ao conectar: " . $conn->connect_error);
    }
    
    // Cria o banco de dados se não existir
    $sql_db = "CREATE DATABASE IF NOT EXISTS $database";
    if (!$conn->query($sql_db)) {
        die("Erro ao criar banco: " . $conn->error);
    }
    
    // Seleciona o banco de dados
    $conn->select_db($database);
    
    // Define o charset para UTF-8
    $conn->set_charset("utf8");
    
    // Cria a tabela de inscrições se não existir
    $sql_table = "CREATE TABLE IF NOT EXISTS inscricoes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        telefone VARCHAR(20) NOT NULL,
        cidade VARCHAR(50) NOT NULL,
        tipo_participante VARCHAR(50) NOT NULL,
        interesses JSON,
        mensagem TEXT,
        data_inscricao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$conn->query($sql_table)) {
        die("Erro ao criar tabela: " . $conn->error);
    }

    // Cria a tabela de usuários administradores se não existir
    $sql_users = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(50) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        nome VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        nivel ENUM('admin', 'editor') DEFAULT 'editor',
        ativo BOOLEAN DEFAULT TRUE,
        data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    if (!$conn->query($sql_users)) {
        die("Erro ao criar tabela usuários: " . $conn->error);
    }

    // Garante índice único no campo usuario caso a tabela exista sem restrição
    $index_check = $conn->query("SHOW INDEX FROM usuarios WHERE Key_name = 'usuario_unico'");
    if ($index_check && $index_check->num_rows === 0) {
        $conn->query("ALTER TABLE usuarios ADD UNIQUE INDEX usuario_unico (usuario)");
    }

    // Insere usuário padrão se não existir
    $check_user = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE usuario = 'admin'");
    $row = $check_user->fetch_assoc();
    if ($row['total'] == 0) {
        $senha_hash = password_hash('admin123', PASSWORD_DEFAULT);
        $sql_insert_user = "INSERT INTO usuarios (usuario, senha, nome, email, nivel) VALUES ('admin', ?, 'Administrador', 'admin@techconf.com', 'admin')";
        $stmt = $conn->prepare($sql_insert_user);
        $stmt->bind_param('s', $senha_hash);
        $stmt->execute();
        $stmt->close();
    }
    
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>
