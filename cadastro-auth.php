<?php
// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=uniban", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Receber os dados do formulário
    $nome = $_POST['registerName'];
    $email = $_POST['registerEmail'];
    $senha = password_hash($_POST['registerPassword'], PASSWORD_DEFAULT);
    $nivel_acesso = $_POST['userRole'];

    // Preparar e executar a consulta SQL
    $stmt = $pdo->prepare("INSERT INTO auth (nome, email, senha, nivel_acesso) VALUES (:nome, :email, :senha, :nivel_acesso)");
    $stmt->bindParam(':nome', $nome, PDO::PARAM_STR); // Especificar o tipo de dado
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':senha', $senha, PDO::PARAM_STR);
    $stmt->bindParam(':nivel_acesso', $nivel_acesso, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
    } else {
        // Handle errors, e.g., duplicate email, database errors
        echo "Erro ao cadastrar usuário. Por favor, tente novamente.";
        // Registrar o erro em um log para análise posterior
        error_log($stmt->errorInfo()[2], 3, "error.log");
    }
} catch (PDOException $e) {
    echo "Erro de conexão com o banco de dados: " . $e->getMessage();
    error_log($e->getMessage(), 3, "error.log");
}