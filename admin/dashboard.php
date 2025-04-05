<?php
session_start();
require_once 'config.php';
checkAuth();

// Nova função para ler os artigos do arquivo JavaScript
function readArticles() {
    $file = '../js/articles-data.js';
    
    if (!file_exists($file)) {
        return [];
    }
    
    $content = file_get_contents($file);
    
    // Extrai o array JSON do arquivo JavaScript
    if (preg_match('/const\s+articlesData\s+=\s+(\[.*?\]);/s', $content, $matches)) {
        $jsonStr = $matches[1];
        // Converte as propriedades JavaScript para formato JSON válido
        $jsonStr = preg_replace('/(\w+):/', '"$1":', $jsonStr);
        $articles = json_decode($jsonStr, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Se houver erro no parsing, analisa manualmente
            $articles = parseArticlesManually($content);
        }
        
        return $articles;
    }
    
    return [];
}

// Função para análise manual caso o JSON não possa ser parseado diretamente
function parseArticlesManually($content) {
    $articles = [];
    $pattern = '/{\s*title:\s*"(.*?)",\s*image:\s*"(.*?)",\s*date:\s*"(.*?)",\s*description:\s*"(.*?)",\s*content:\s*"(.*?)"\s*}/s';
    
    if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $articles[] = [
                'title' => stripcslashes($match[1]),
                'image' => stripcslashes($match[2]),
                'date' => stripcslashes($match[3]),
                'description' => stripcslashes($match[4]),
                'content' => stripcslashes($match[5])
            ];
        }
    }
    
    return $articles;
}

// Nova função para salvar os artigos no arquivo JavaScript
function saveArticles($articles) {
    $jsContent = "/**\n * Dados dos artigos em formato JavaScript\n * Esta abordagem elimina os problemas de carregamento do arquivo markdown\n */\n\nconst articlesData = [\n";
    
    foreach ($articles as $article) {
        if (empty($article['title']) || empty($article['description'])) {
            continue;
        }
        
        // Formata a data no padrão brasileiro
        if (empty($article['date'])) {
            $article['date'] = date('d-m-Y');
        }
        
        // Escapa as aspas e outros caracteres especiais
        $title = addslashes($article['title']);
        $image = addslashes($article['image']);
        $description = addslashes($article['description']);
        
        // Preparar o conteúdo HTML para JavaScript
        // Substitui quebras de linha por \n e escapa aspas
        $content = addslashes($article['content']);
        $content = str_replace(["\r\n", "\n"], "\\n", $content);
        
        $jsContent .= "  {\n";
        $jsContent .= "    title: \"{$title}\",\n";
        $jsContent .= "    image: \"{$image}\",\n";
        $jsContent .= "    date: \"{$article['date']}\",\n";
        $jsContent .= "    description: \"{$description}\",\n";
        $jsContent .= "    content: \"{$content}\"\n";
        $jsContent .= "  },\n";
    }
    
    // Remove a última vírgula e fecha o array
    $jsContent = rtrim($jsContent, ",\n") . "\n];\n";
    
    // Salva o arquivo
    file_put_contents('../js/articles-data.js', $jsContent);
}

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $articles = readArticles();
                $newArticle = [
                    'title' => $_POST['title'],
                    'image' => '',
                    'description' => $_POST['description'],
                    'content' => $_POST['content']
                ];
                
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload = handleImageUpload($_FILES['image']);
                    if ($upload['success']) {
                        $newArticle['image'] = 'uploads/' . $upload['filename'];
                    }
                }
                
                $articles[] = $newArticle;
                saveArticles($articles);
                break;
                
            case 'edit':
                $articles = readArticles();
                $index = $_POST['index'];
                $articles[$index] = [
                    'title' => $_POST['title'],
                    'image' => $_POST['current_image'],
                    'description' => $_POST['description'],
                    'content' => $_POST['content']
                ];
                
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $upload = handleImageUpload($_FILES['image']);
                    if ($upload['success']) {
                        $articles[$index]['image'] = 'uploads/' . $upload['filename'];
                    }
                }
                
                saveArticles($articles);
                break;
                
            case 'delete':
                $articles = readArticles();
                unset($articles[$_POST['index']]);
                saveArticles(array_values($articles));
                break;
        }
        
        header('Location: dashboard.php');
        exit;
    }
}

$articles = readArticles(); // Use a nova função para ler os artigos
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Artigos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Painel Administrativo</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message']); endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Gerenciar Artigos</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#articleModal">
                Novo Artigo
            </button>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $index => $article): ?>
                        <?php if (!empty($article['title']) && !empty($article['description'])): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($article['title']); ?></td>
                            <td>
                                <?php 
                                // Limita a descrição a 50 caracteres para não quebrar o layout
                                $description = htmlspecialchars($article['description']);
                                echo strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description; 
                                ?>
                            </td>
                            <td>
                                <?php echo !empty($article['date']) ? htmlspecialchars($article['date']) : ''; ?>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary edit-article" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#articleModal"
                                        data-index="<?php echo $index; ?>"
                                        data-title="<?php echo htmlspecialchars($article['title']); ?>"
                                        data-description="<?php echo htmlspecialchars($article['description']); ?>"
                                        data-content="<?php echo htmlspecialchars($article['content']); ?>"
                                        data-image="<?php echo htmlspecialchars($article['image']); ?>">
                                    Editar
                                </button>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Artigo -->
    <div class="modal fade" id="articleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Artigo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="index" value="">
                        <input type="hidden" name="current_image" value="">
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Título</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Imagem</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div id="currentImage" class="mt-2"></div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Conteúdo</label>
                            <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Editar artigo
            $('.edit-article').click(function() {
                const form = $('#articleModal form');
                form.find('[name="action"]').val('edit');
                form.find('[name="index"]').val($(this).data('index'));
                form.find('[name="current_image"]').val($(this).data('image'));
                form.find('#title').val($(this).data('title'));
                form.find('#description').val($(this).data('description'));
                form.find('#content').val($(this).data('content'));
                
                const currentImage = $('#currentImage');
                const imagePath = $(this).data('image');
                if (imagePath) {
                    // Verifica se a imagem é um link externo (http/https) ou um caminho local
                    if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
                        currentImage.html(`<img src="${imagePath}" class="img-thumbnail" style="max-height: 100px">`);
                    } else {
                        currentImage.html(`<img src="../${imagePath}" class="img-thumbnail" style="max-height: 100px">`);
                    }
                } else {
                    currentImage.empty();
                }
            });

            // Novo artigo
            $('#articleModal').on('hidden.bs.modal', function() {
                const form = $(this).find('form');
                form.find('[name="action"]').val('add');
                form.find('[name="index"]').val('');
                form.find('[name="current_image"]').val('');
                form.find('#title').val('');
                form.find('#description').val('');
                form.find('#content').val('');
                $('#currentImage').empty();
            });
        });
    </script>
</body>
</html>