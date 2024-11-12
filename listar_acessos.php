<?php
// Conexão ao banco de dados usando PDO
$pdo = new PDO("mysql:host=127.0.0.1;dbname=uniban", "root", "");

// Consultas para recuperar entradas, saídas e calcular presentes para o dia atual
$query = "
    SELECT 
        (SELECT COUNT(*) FROM acessos WHERE tipo_acesso = 'entrada' AND DATE(data_hora) = CURDATE()) AS total_entradas,
        (SELECT COUNT(*) FROM acessos WHERE tipo_acesso = 'saida' AND DATE(data_hora) = CURDATE()) AS total_saidas,
        (SELECT COUNT(DISTINCT matricula) FROM acessos 
         WHERE tipo_acesso = 'entrada' AND DATE(data_hora) = CURDATE()
         AND matricula NOT IN (
             SELECT matricula FROM acessos 
             WHERE tipo_acesso = 'saida' AND DATE(data_hora) = CURDATE()
         )) AS total_presentes
";
$stmt = $pdo->query($query);
$estatisticas = $stmt->fetch(PDO::FETCH_ASSOC);

if ($estatisticas) {
    echo json_encode([
        'entradas' => $estatisticas['total_entradas'],
        'saidas' => $estatisticas['total_saidas'],
        'presentes' => $estatisticas['total_presentes']
    ]);
} else {
    echo json_encode([
        'entradas' => 0,
        'saidas' => 0,
        'presentes' => 0
    ]);
}
?>
