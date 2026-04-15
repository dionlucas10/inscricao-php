<?php
// Configurações essenciais para estabelecer uma conexão segura com o banco de dados MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tela_login"; // Por favor, ajuste este valor para corresponder ao nome do seu banco de dados específico

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
