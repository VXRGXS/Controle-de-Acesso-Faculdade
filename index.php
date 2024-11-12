<?php
// Conexão ao banco de dados usando PDO
$pdo = new PDO("mysql:host=127.0.0.1;dbname=uniban", "root", "");

// Verifique se os dados necessários foram enviados via POST
if (!isset($_POST['matricula']) || !isset($_POST['tipo_acesso'])) {
    // Responde com erro se os dados estiverem incompletos
    echo json_encode(["status" => "erro", "mensagem" => "Dados incompletos."]);
    exit; // Interrompe a execução do script
}

$matricula = $_POST['matricula']; // Captura a matrícula do aluno
$tipo_acesso = $_POST['tipo_acesso']; // Captura o tipo de acesso (entrada ou saída)

// Insira o acesso no banco de dados
$query = "INSERT INTO acessos (matricula, tipo_acesso, data_hora) VALUES (:matricula, :tipo_acesso, NOW())"; // Consulta SQL
$stmt = $pdo->prepare($query); // Prepara a consulta
$stmt->bindParam(':matricula', $matricula); // Vincula a matrícula ao parâmetro
$stmt->bindParam(':tipo_acesso', $tipo_acesso); // Vincula o tipo de acesso ao parâmetro

if ($stmt->execute()) {
    // Responde com sucesso se a inserção for realizada
    echo json_encode(["status" => "sucesso", "mensagem" => "Acesso registrado com sucesso!"]);
} else {
    // Responde com erro se a inserção falhar
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao registrar o acesso."]);
}
?>