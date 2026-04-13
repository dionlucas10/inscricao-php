<?php
session_start();

// Verificar se usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../admin-login.php');
    exit;
}

// Verificar nível de acesso (opcional)
function verificarNivel($nivel_requerido = 'editor') {
    if (!isset($_SESSION['usuario_nivel'])) {
        header('Location: ../admin-login.php');
        exit;
    }

    $niveis = ['editor' => 1, 'admin' => 2];
    $usuario_nivel = $_SESSION['usuario_nivel'];

    if ($niveis[$usuario_nivel] < $niveis[$nivel_requerido]) {
        die('Acesso negado! Você não tem permissão para acessar esta página.');
    }
}
?>