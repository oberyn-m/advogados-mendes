<?php
session_start();
require_once 'config.php';
checkAuth();

function readArticles() {
    $file = '../js/articles-data.js';
    
    if (!file_exists($file)) {
        return [];
    }
    
    $content = file_get_contents($file);
    
    if (preg_match('/const\s+articlesData\s+=\s+(\[.*?\]);/s', $content, $matches)) {
        $jsonStr = $matches[1];
        $jsonStr = preg_replace('/(\w+):/', '"$1":', $jsonStr);
        $articles = json_decode($jsonStr, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $articles = parseArticlesManually($content);
        }
        
        return $articles;
    }
    
    return [];
}

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

function saveArticles($articles) {
    $jsContent = "const articlesData = [\n";
    
    foreach ($articles as $article) {
        if (empty($article['title']) || empty($article['description'])) {
            continue;
        }
        
        if (empty($article['date'])) {
            $article['date'] = date('d-m-Y');
        }
        
        $title = addslashes($article['title']);
        $image = addslashes($article['image']);
        $description = addslashes($article['description']);
        
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
    
    $jsContent = rtrim($jsContent, ",\n") . "\n];\n";
    
    file_put_contents('../js/articles-data.js', $jsContent);
}

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

$articles = readArticles();
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
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Conteúdo</label>
                            <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                            <div id="contentHelp" class="form-text">
                                Você pode usar HTML para formatar o conteúdo: &lt;b&gt;, &lt;i&gt;, &lt;p&gt;, etc.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="image" class="form-label">Imagem</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div id="imagePreview" class="mt-2 d-none">
                                <label class="form-label">Imagem atual:</label>
                                <img src="" alt="Preview" style="max-width: 200px; max-height: 200px;" class="d-block">
                            </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const articleModal = document.getElementById('articleModal');
            if (articleModal) {
                articleModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const isEdit = button.classList.contains('edit-article');
                    
                    const titleInput = document.getElementById('title');
                    const descriptionInput = document.getElementById('description');
                    const contentInput = document.getElementById('content');
                    const actionInput = document.querySelector('input[name="action"]');
                    const indexInput = document.querySelector('input[name="index"]');
                    const currentImageInput = document.querySelector('input[name="current_image"]');
                    const imagePreview = document.getElementById('imagePreview');
                    const modalTitle = this.querySelector('.modal-title');
                    
                    if (isEdit) {
                        const index = button.getAttribute('data-index');
                        const title = button.getAttribute('data-title');
                        const description = button.getAttribute('data-description');
                        const content = button.getAttribute('data-content');
                        const image = button.getAttribute('data-image');
                        
                        actionInput.value = 'edit';
                        indexInput.value = index;
                        titleInput.value = title;
                        descriptionInput.value = description;
                        contentInput.value = content;
                        currentImageInput.value = image;
                        modalTitle.textContent = 'Editar Artigo';
                        
                        if (image) {
                            imagePreview.classList.remove('d-none');
                            const img = imagePreview.querySelector('img');
                            img.src = '../' + image;
                        } else {
                            imagePreview.classList.add('d-none');
                        }
                    } else {
                        actionInput.value = 'add';
                        indexInput.value = '';
                        titleInput.value = '';
                        descriptionInput.value = '';
                        contentInput.value = '';
                        currentImageInput.value = '';
                        modalTitle.textContent = 'Novo Artigo';
                        imagePreview.classList.add('d-none');
                    }
                });
            }
        });
    </script>
</body>
</html>