<?php 
require 'conexao.php'; 
require 'funcoes.php'; 

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) redireciona('index.php');

$stmt = $pdo->prepare("SELECT n.*, u.nome AS autor_nome FROM noticias n JOIN usuarios u ON n.autor = u.id WHERE n.id = ?");
$stmt->execute([$id]);
$noticia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$noticia) redireciona('index.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($noticia['titulo']) ?> - ECOFOCO</title>
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
                <?php if (estaLogado()): ?>
                    <a href="dashboard.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-tachometer-alt me-1"></i><span>Painel</span>
                    </a>
                    <?php if (ehAdmin()): ?>
                        <a href="nova_noticia.php" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i><span>Nova Notícia</span>
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

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <article class="news-card">
                    <?php if ($noticia['imagem'] && file_exists($noticia['imagem'])): ?>
                        <img src="<?= htmlspecialchars($noticia['imagem']) ?>" 
                             class="news-image w-100" 
                             style="max-height: 500px; object-fit: cover;" 
                             alt="<?= htmlspecialchars($noticia['titulo']) ?>">
                    <?php endif; ?>

                    <div class="news-card-body">
                        <h1 class="news-title display-6"><?= htmlspecialchars($noticia['titulo']) ?></h1>
                        
                        <div class="news-meta border-bottom pb-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <span class="news-author">
                                    <i class="fas fa-user me-2"></i>Por <strong><?= htmlspecialchars($noticia['autor_nome']) ?></strong>
                                </span>
                                <span class="news-date">
                                    <i class="fas fa-calendar me-2"></i><?= date('d/m/Y \à\s H:i', strtotime($noticia['data'])) ?>
                                </span>
                            </div>
                        </div>

                        <div class="news-content" style="line-height: 1.8; font-size: 1.1rem; color: #333;">
                            <?= nl2br(htmlspecialchars($noticia['noticia'])) ?>
                        </div>

                        <div class="mt-5 pt-4 border-top">
                            <a href="index.php" class="btn btn-primary">
                                <i class="fas fa-arrow-left me-2"></i>Voltar às Notícias
                            </a>
                        </div>
                    </div>
                </article>
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