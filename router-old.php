<?php
// router.php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($uri) {
    case '/':
    case '/cadastro':
        require __DIR__ . '/cadastro.html';
        break;
    
    case '/index.php':
        require __DIR__ . '/index.php';
        break;
        
    case '/home':
        require __DIR__ . '/home.html';
        break;
        
    // Adicione outras rotas conforme necessário
    case '/listar':
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