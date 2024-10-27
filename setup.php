<?php
$host = "127.0.0.1";
$dbName = "uniban";
$user = "root";
$password = "";

// Cria uma conexão com o servidor MySQL
$pdo = new PDO("mysql:host=$host", $user, $password);

// Criação da base de dados se não existir
$pdo->exec("CREATE DATABASE IF NOT EXISTS $dbName");

// Seleciona a base de dados
$pdo->exec("USE $dbName");

// Criação da tabela de alunos
$queryAlunos = "
CREATE TABLE IF NOT EXISTS alunos (
    matricula INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    curso VARCHAR(100) NOT NULL,
    semestre INT NOT NULL
)";

$stmtAlunos = $pdo->prepare($queryAlunos);
$stmtAlunos->execute();

// Criação da tabela de acessos
$queryAcessos = "
CREATE TABLE IF NOT EXISTS acessos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    matricula INT NOT NULL,
    tipo_acesso ENUM('entrada', 'saida') NOT NULL,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (matricula) REFERENCES alunos(matricula) ON DELETE CASCADE
)";

$stmtAcessos = $pdo->prepare($queryAcessos);
$stmtAcessos->execute();

echo "Base de dados e tabelas criadas com sucesso!";
?>