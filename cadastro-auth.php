<?php
session_start();

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=127.0.0.1;dbname=uniban", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}

// Verifica qual ação foi solicitada
$action = $_POST['action'] ?? '';

if ($action === 'register') {
    // Processar Cadastro
    if (!empty($_POST['registerName']) && !empty($_POST['registerEmail']) && !empty($_POST['registerPassword']) && !empty($_POST['userRole'])) {
        $nome = $_POST['registerName'];
        $email = $_POST['registerEmail'];
        $senha = password_hash($_POST['registerPassword'], PASSWORD_DEFAULT);
        $nivel_acesso = $_POST['userRole'];

        $query = "INSERT INTO auth (nome, email, senha, nivel_acesso) VALUES (:nome, :email, :senha, :nivel_acesso)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->bindParam(':nivel_acesso', $nivel_acesso);

        if ($stmt->execute()) {
            // Cadastro bem-sucedido, redirecionar para a página de login
            header("Location: auth.html?cadastro=sucesso"); // Passando uma query string indicando sucesso
            exit;
        } else {
            echo "Erro ao cadastrar usuário.";
            error_log($stmt->errorInfo()[2], 3, "error.log");
        }
    } else {
        echo "Por favor, preencha todos os campos do cadastro.";
    }
} elseif ($action === 'login') {
    // Processar Login
    if (!empty($_POST['loginEmail']) && !empty($_POST['loginPassword'])) {
        $email = $_POST['loginEmail'];
        $senha = $_POST['loginPassword'];

        $query = "SELECT * FROM auth WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user'] = $user;

            if ($user['nivel_acesso'] === 'admin') {
                header("Location: cadastro.html");
                exit;
            } else {
                header("Location: home.html");
                exit;
            }
        } else {
            echo "Credenciais inválidas. Tente novamente.";
        }
    } else {
        echo "Por favor, preencha todos os campos do login.";
    }
} else {
    echo "Ação inválida.";
}
?>
