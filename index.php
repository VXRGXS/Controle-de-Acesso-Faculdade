<?php
// Conexão ao banco de dados usando PDO
$pdo = new PDO("mysql:host=127.0.0.1;dbname=uniban", "root", "");

// Verifique se os dados necessários foram enviados via POST
if (!isset($_POST['matricula']) || !isset($_POST['tipo_acesso'])) {
    echo json_encode(["status" => "erro", "mensagem" => "Dados incompletos."]);
    exit;
}

$matricula = $_POST['matricula'];
$tipo_acesso = $_POST['tipo_acesso'];

// Verifique se o tipo de acesso é "saida"
if ($tipo_acesso == 'saida') {
    // Verifique no banco de dados se a matrícula tem uma entrada registrada
    $query = "
        SELECT COUNT(*) 
        FROM acessos 
        WHERE matricula = :matricula 
        AND tipo_acesso = 'entrada' 
        AND DATE(data_hora) = CURDATE()
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':matricula', $matricula);
    $stmt->execute();
    $entrada_count = $stmt->fetchColumn();

    // Se não houver entrada registrada, bloqueia a saída
    if ($entrada_count == 0) {
        echo json_encode(["status" => "erro", "mensagem" => "O aluno não registrou entrada ainda."]);
        exit;
    }
}

// Agora, insira o acesso no banco (entrada ou saída)
$query = "INSERT INTO acessos (matricula, tipo_acesso, data_hora) VALUES (:matricula, :tipo_acesso, NOW())";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':matricula', $matricula);
$stmt->bindParam(':tipo_acesso', $tipo_acesso);

if ($stmt->execute()) {
    // Personaliza a mensagem dependendo do tipo de acesso
    $mensagem = ($tipo_acesso == 'entrada') ? "Entrada registrada com sucesso!" : "Saída registrada com sucesso!";
    echo json_encode(["status" => "sucesso", "mensagem" => $mensagem]);
} else {
    echo json_encode(["status" => "erro", "mensagem" => "Erro ao registrar o acesso."]);
}

?>
