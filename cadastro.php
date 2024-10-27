<?php
// Conexão com o banco de dados utilizando PDO
$pdo = new PDO("mysql:host=127.0.0.1;dbname=uniban", "root", "");

// Recebe os dados JSON enviados pelo JavaScript
$data = json_decode(file_get_contents("php://input"), true); // Converte o JSON em um array associativo

// Verifica se os dados necessários foram enviados
if (!isset($data['nome']) || !isset($data['curso']) || !isset($data['semestre'])) {
    // Resposta em caso de dados incompletos
    echo json_encode(["status" => "erro", "mensagem" => "Dados incompletos."]);
    exit; // Encerra a execução do script
}

// Captura os dados do aluno
$matricula = $data['matricula']; // Matrícula (pode ser gerada automaticamente)
$nome = $data['nome']; // Nome do aluno
$curso = $data['curso']; // Curso do aluno
$semestre = $data['semestre']; // Semestre do aluno

// Insere o novo aluno no banco de dados
$query = "INSERT INTO alunos (matricula, nome, curso, semestre) VALUES (:matricula, :nome, :curso, :semestre)";
$stmt = $pdo->prepare($query); // Prepara a consulta SQL
$stmt->bindParam(':matricula', $matricula); // Liga a matrícula ao parâmetro
$stmt->bindParam(':nome', $nome); // Liga o nome ao parâmetro
$stmt->bindParam(':curso', $curso); // Liga o curso ao parâmetro
$stmt->bindParam(':semestre', $semestre); // Liga o semestre ao parâmetro

// Executa a consulta e verifica o resultado
if ($stmt->execute()) {
    $matriculaGerada = $pdo->lastInsertId(); // Recupera a matrícula gerada pelo banco
    // Resposta em caso de sucesso
    echo json_encode(["status" => "sucesso", "mensagem" => "Aluno cadastrado com sucesso!", "matricula" => $matriculaGerada]);
} else {
    // Resposta em caso de erro
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao cadastrar o aluno."]);
}
?>
