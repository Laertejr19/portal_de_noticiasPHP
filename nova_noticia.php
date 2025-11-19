<?php
require 'conexao.php';
require 'funcoes.php';
protegeAdmin();

if ($_POST) {
    $titulo = trim($_POST['titulo']);
    $noticia = trim($_POST['noticia']);
    $imagem = '';

    if (!empty($_FILES['imagem']['name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = 'imagens/' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $nome_arquivo)) {
            $imagem = $nome_arquivo;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO noticias (titulo, noticia, autor, imagem) VALUES (?, ?, ?, ?)");
    $stmt->execute([$titulo, $noticia, $_SESSION['usuario_id'], $imagem]);
    redireciona('dashboard.php?msg=Notícia publicada com sucesso!');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Nova Notícia - ECOFOCO</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="imagens/coruja.PNG" alt="Logo ECOFOCO" style="height: 45px; width: auto;" class="me-2">
                <span>ECOFOCO</span>
            </a>
            <div class="ms-auto d-flex align-items-center gap-2">
                <a href="dashboard.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-tachometer-alt me-1"></i><span>Painel</span>
                </a>
                <a href="nova_noticia.php" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i><span>Nova Notícia</span>
                </a>
                <a href="gerenciar_usuarios.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-users me-1"></i><span>Usuários</span>
                </a>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i><span>Sair</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="dashboard-header">
        <div class="container">
            <h1 class="user-welcome">Publicar Nova Notícia</h1>
            <p class="user-role">Compartilhe conteúdo ambiental com a comunidade</p>
        </div>
    </section>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="news-card">
                    <div class="news-card-body">
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="form-label">Título da Notícia *</label>
                                <input type="text" name="titulo" class="form-control" placeholder="Digite o título da notícia" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Conteúdo da Notícia *</label>
                                <textarea name="noticia" rows="10" class="form-control" placeholder="Escreva o conteúdo da notícia aqui..." required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Imagem da Notícia</label>
                                <input type="file" name="imagem" class="form-control" accept="image/*">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Formatos suportados: JPG, PNG, GIF. Tamanho máximo: 5MB
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <a href="dashboard.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Publicar Notícia
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> ECOFOCO - Portal de Notícias Ambientais</p>
        </div>
    </footer>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>