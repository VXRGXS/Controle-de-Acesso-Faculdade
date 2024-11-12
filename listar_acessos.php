<?php
// Conexão ao banco de dados usando PDO
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=uniban", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
    exit; // Termina o script em caso de erro
}

// Consulta para total de entradas, saídas e presentes do dia
$query = "
    SELECT
        (SELECT COUNT(*) FROM acessos WHERE tipo_acesso = 'entrada' AND DATE(data_hora) = CURDATE()) AS total_entradas,
        (SELECT COUNT(*) FROM acessos WHERE tipo_acesso = 'saida' AND DATE(data_hora) = CURDATE()) AS total_saidas,
        (
            SELECT COUNT(DISTINCT matricula)
            FROM acessos
            WHERE tipo_acesso = 'entrada' AND DATE(data_hora) = CURDATE()
            AND matricula NOT IN (
                SELECT matricula FROM acessos WHERE tipo_acesso = 'saida' AND DATE(data_hora) = CURDATE()
            )
        ) AS total_presentes
";
try {
    $stmt = $pdo->query($query); // Executa a consulta
    $estatisticas = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém os resultados

    if ($estatisticas) {
        echo json_encode([
            'entradas' => $estatisticas['total_entradas'],
            'saidas' => $estatisticas['total_saidas'],
            'presentes' => $estatisticas['total_presentes']
        ]);
    } else {
        echo json_encode(['entradas' => 0, 'saidas' => 0, 'presentes' => 0]);
    }
} catch (PDOException $e) {
    echo "Erro ao executar a consulta: " . $e->getMessage(); // Exibe erro se a consulta falhar
}
?>
