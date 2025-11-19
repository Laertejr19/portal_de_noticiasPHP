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
    redireciona('dashboard.php?msg=Notícia não encontrada');
}

if ($_POST['confirmar'] ?? '' === 'sim') {
    if ($noticia['imagem'] && file_exists($noticia['imagem'])) {
        unlink($noticia['imagem']);
    }
    $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt->execute([$id]);
    redireciona('dashboard.php?msg=Notícia excluída!');
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Notícia - ECOFOCO</title>
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
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i><span>Sair</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="news-card">
                    <div class="news-card-body text-center">
                        <div class="auth-icon text-danger mb-3">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        
                        <h2 class="auth-title text-danger">Excluir Notícia</h2>
                        <p class="auth-subtitle">Esta ação não pode ser desfeita</p>

                        <div class="alert alert-warning rounded-custom mt-4">
                            <h5 class="mb-3">"<?= htmlspecialchars($noticia['titulo']) ?>"</h5>
                            <p class="mb-0">Tem certeza que deseja excluir permanentemente esta notícia?</p>
                        </div>

                        <form method="POST" class="d-inline">
                            <input type="hidden" name="confirmar" value="sim">
                            <button type="submit" class="btn btn-danger btn-lg mt-3">
                                <i class="fas fa-trash me-2"></i>Sim, Excluir Notícia
                            </button>
                            <a href="dashboard.php" class="btn btn-outline-secondary btn-lg mt-3 ms-2">
                                <i class="fas fa-times me-2"></i>Cancelar
                            </a>
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