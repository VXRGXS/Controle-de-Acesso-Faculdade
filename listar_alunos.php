<?php
// Conexão com o banco de dados utilizando PDO
$pdo = new PDO("mysql:host=127.0.0.1;dbname=uniban", "root", "");

// Consulta para obter todos os alunos
$query = "SELECT matricula, nome, curso, semestre FROM alunos"; // SQL para selecionar dados
$stmt = $pdo->prepare($query); // Prepara a consulta
$stmt->execute(); // Executa a consulta

// Recupera todos os alunos como um array associativo
$alunos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retorna os alunos em formato JSON
echo json_encode($alunos); // Converte o array para JSON e imprime
?>
