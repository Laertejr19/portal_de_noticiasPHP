<?php 
require 'conexao.php'; 
require 'funcoes.php'; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ECOFOCO - Portal de Notícias Ambientais</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="imagens/coruja.PNG" alt="Logo ECOFOCO" style="height: 45px; width: auto;" class="me-2">
                <span>ECOFOCO</span>
            </a>
            <div class="ms-auto d-flex align-items-center gap-2">
                <?php if (estaLogado()): ?>
                    <?php if (ehAdmin()): ?>
                        <a href="dashboard.php" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-tachometer-alt me-1"></i><span>Painel</span>
                        </a>
                        <a href="nova_noticia.php" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i><span>Nova Notícia</span>
                        </a>
                    <?php else: ?>
                        <a href="dashboard.php" class="btn btn-outline-light btn-sm">
                            <i class="fas fa-user me-1"></i><span>Meu Perfil</span>
                        </a>
                    <?php endif; ?>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i><span>Sair</span>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-in-alt me-1"></i><span>Login</span>
                    </a>
                    <a href="cadastro.php" class="btn btn-light btn-sm">
                        <i class="fas fa-user-plus me-1"></i><span>Cadastre-se</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Portal de Notícias Ambientais</h1>
            <p class="hero-subtitle">Fique por dentro do que importa para o planeta</p>
        </div>
    </section>

    <!-- Notícias -->
    <div class="container">
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-info alert-dismissible fade show rounded-custom shadow-custom" role="alert">
                <?= htmlspecialchars($_GET['msg']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="news-grid">
            <?php
            $stmt = $pdo->query("SELECT n.*, u.nome AS autor_nome FROM noticias n JOIN usuarios u ON n.autor = u.id ORDER BY n.data DESC");
            while ($noticia = $stmt->fetch(PDO::FETCH_ASSOC)):
            ?>
                <div class="news-card">
                    <?php if ($noticia['imagem'] && file_exists($noticia['imagem'])): ?>
                        <img src="<?= htmlspecialchars($noticia['imagem']) ?>" class="news-image" alt="<?= htmlspecialchars($noticia['titulo']) ?>">
                    <?php else: ?>
                        <div class="news-image bg-light d-flex align-items-center justify-content-center text-muted">
                            <div class="text-center">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <p>Sem imagem</p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="news-card-body">
                        <h3 class="news-title"><?= htmlspecialchars($noticia['titulo']) ?></h3>
                        <p class="news-excerpt"><?= htmlspecialchars(resumir($noticia['noticia'], 120)) ?></p>
                        
                        <div class="news-meta">
                            <span class="news-author">
                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($noticia['autor_nome']) ?>
                            </span>
                            <span class="news-date">
                                <i class="fas fa-calendar me-1"></i><?= date('d/m/Y', strtotime($noticia['data'])) ?>
                            </span>
                        </div>
                        
                        <a href="noticia.php?id=<?= $noticia['id'] ?>" class="btn btn-primary w-100">
                            <i class="fas fa-book-open me-2"></i>Ler Mais
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> ECOFOCO - Portal de Notícias Ambientais. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>