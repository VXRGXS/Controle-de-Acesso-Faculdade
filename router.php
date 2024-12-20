<?php

// Função que verifica se o usuário está logado
function checkLogin() {
    if (!isset($_SESSION['user'])) {
        header("Location: /auth.html"); // Redireciona para a página de login
        exit;
    }
}

// Roteador para as URLs
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
        // Acesse a página inicial (página de login) se não estiver logado
        checkLogin(); // Verifica se o usuário está logado antes de redirecionar para qualquer página protegida
        require __DIR__ . '/home.html'; // Página inicial (home) após verificação
        break;

    case '/cadastro':
        // Rota para cadastro, não precisa de verificação de login
        require __DIR__ . '/cadastro.html';
        break;
    
    case '/index.php':
        // Rota protegida para index.php
        checkLogin(); // Verifica login antes de acessar
        require __DIR__ . '/index.php';
        break;
        
    case '/home':
        // Rota protegida para home.html
        checkLogin(); // Verifica login antes de acessar
        require __DIR__ . '/home.html';
        break;
        
    // Rota para listagem de alunos
    case '/listar':
        // Rota aberta, sem verificação de login
        require __DIR__ . '/listar_alunos.php';
        break;
        
    default:
        // Para arquivos estáticos (CSS, JS, etc)
        $file = __DIR__ . $uri;
        if (file_exists($file)) {
            // Define o tipo de conteúdo correto para CSS e JS
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            switch ($ext) {
                case 'css':
                    header('Content-Type: text/css');
                    break;
                case 'js':
                    header('Content-Type: application/javascript');
                    break;
            }
            require $file;
            break;
        }
        
        http_response_code(404);
        echo "Página não encontrada";
        break;
}
?>
