<?php
require 'conexao.php';
require 'funcoes.php';
protegePagina();

$id = $_GET['id'] ?? 0;

if (ehAdmin()) {
    $stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = ?");
    $stmt->execute([$id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = ? AND autor = ?");
    $stmt->execute([$id, $_SESSION['usuario_id']]);
}

$noticia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$noticia) {
    redireciona('dashboard.php?msg=Notícia não encontrada ou sem permissão');
}

if ($_POST) {
    $titulo = trim($_POST['titulo']);
    $noticia_texto = trim($_POST['noticia']);
    $imagem_atual = $noticia['imagem'];

    $nova_imagem = $imagem_atual;
    if (!empty($_FILES['imagem']['name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $novo = 'imagens/' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $novo)) {
            if ($imagem_atual && file_exists($imagem_atual)) {
                unlink($imagem_atual);
            }
            $nova_imagem = $novo;
        }
    }

    $stmt = $pdo->prepare("UPDATE noticias SET titulo = ?, noticia = ?, imagem = ? WHERE id = ?");
    $stmt->execute([$titulo, $noticia_texto, $nova_imagem, $id]);
    redireciona('dashboard.php?msg=Notícia atualizada!');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Notícia - ECOFOCO</title>
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
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i><span>Sair</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="dashboard-header">
        <div class="container">
            <h1 class="user-welcome">Editar Notícia</h1>
            <p class="user-role">Atualize o conteúdo da notícia</p>
        </div>
    </section>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="news-card">
                    <div class="news-card-body">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="form-label">Título da Notícia *</label>
                                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Conteúdo da Notícia *</label>
                                <textarea name="noticia" rows="10" class="form-control" required><?= htmlspecialchars($noticia['noticia']) ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Imagem Atual</label>
                                <?php if ($noticia['imagem'] && file_exists($noticia['imagem'])): ?>
                                    <div class="mb-3">
                                        <img src="<?= htmlspecialchars($noticia['imagem']) ?>" class="img-fluid rounded-custom shadow-custom" style="max-height: 200px;">
                                        <p class="text-muted small mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Deixe em branco para manter a imagem atual
                                        </p>
                                    </div>
                                <?php else: ?>
                                    <p class="text-muted">
                                        <i class="fas fa-image me-1"></i>
                                        Nenhuma imagem associada
                                    </p>
                                <?php endif; ?>
                                <input type="file" name="imagem" class="form-control" accept="image/*">
                            </div>
                            
                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <a href="dashboard.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Atualizar Notícia
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