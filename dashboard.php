<?php
require 'conexao.php';
require 'funcoes.php';
protegePagina();

if (ehAdmin()) {
    $stmt = $pdo->prepare("SELECT n.*, u.nome AS autor_nome FROM noticias n JOIN usuarios u ON n.autor = u.id ORDER BY n.data DESC");
    $stmt->execute();
    $titulo_painel = "Painel Administrativo";
} else {
    $stmt = $pdo->prepare("SELECT * FROM noticias WHERE autor = ? ORDER BY data DESC");
    $stmt->execute([$_SESSION['usuario_id']]);
    $titulo_painel = "Meu Painel";
}
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - ECOFOCO</title>
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
                <?php if (ehAdmin()): ?>
                    <a href="nova_noticia.php" class="btn btn-light btn-sm">
                        <i class="fas fa-plus me-1"></i><span>Nova Notícia</span>
                    </a>
                    <a href="gerenciar_usuarios.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-users me-1"></i><span>Usuários</span>
                    </a>
                <?php endif; ?>
                <a href="editar_usuario.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-user-edit me-1"></i><span>Perfil</span>
                </a>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i><span>Sair</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Dashboard Header -->
    <section class="dashboard-header">
        <div class="container">
            <h1 class="user-welcome">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!</h1>
            <p class="user-role">
                <?= $titulo_painel ?>
                <?php if (ehAdmin()): ?>
                    <span class="admin-badge">Administrador</span>
                <?php else: ?>
                    <span class="admin-badge">Usuário</span>
                <?php endif; ?>
            </p>
        </div>
    </section>

    <div class="container">
        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-custom shadow-custom" role="alert">
                <?= htmlspecialchars($_GET['msg']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Ações Rápidas -->
        <div class="dashboard-actions">
            <?php if (ehAdmin()): ?>
                <a href="nova_noticia.php" class="btn btn-primary action-btn">
                    <i class="fas fa-plus-circle me-2"></i>Nova Notícia
                </a>
                <a href="gerenciar_usuarios.php" class="btn btn-outline-primary action-btn">
                    <i class="fas fa-users me-2"></i>Gerenciar Usuários
                </a>
            <?php endif; ?>
            <a href="editar_usuario.php" class="btn btn-outline-primary action-btn">
                <i class="fas fa-user-edit me-2"></i>Editar Perfil
            </a>
            <a href="excluir_usuario.php" class="btn btn-outline-danger action-btn">
                <i class="fas fa-user-times me-2"></i>Excluir Conta
            </a>
        </div>

        <!-- Lista de Notícias -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary-custom">
                <i class="fas fa-newspaper me-2"></i>
                <?= ehAdmin() ? 'Todas as Notícias' : 'Minhas Notícias' ?>
            </h2>
            <span class="badge bg-primary-custom fs-6"><?= count($noticias) ?> notícias</span>
        </div>

        <?php if (empty($noticias)): ?>
            <div class="text-center py-5">
                <div class="news-card p-5">
                    <i class="fas fa-newspaper fa-4x text-muted mb-3"></i>
                    <h3 class="text-muted">Nenhuma notícia publicada</h3>
                    <p class="text-muted mb-4">Comece a compartilhar conteúdo ambiental.</p>
                    <?php if (ehAdmin()): ?>
                        <a href="nova_noticia.php" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Publicar Primeira Notícia
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="news-grid">
                <?php foreach ($noticias as $n): ?>
                    <div class="news-card">
                        <?php if ($n['imagem'] && file_exists($n['imagem'])): ?>
                            <img src="<?= htmlspecialchars($n['imagem']) ?>" class="news-image" alt="<?= htmlspecialchars($n['titulo']) ?>">
                        <?php else: ?>
                            <div class="news-image bg-light d-flex align-items-center justify-content-center text-muted">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                        <?php endif; ?>
                        
                        <div class="news-card-body">
                            <h3 class="news-title"><?= htmlspecialchars($n['titulo']) ?></h3>
                            
                            <?php if (ehAdmin()): ?>
                                <div class="news-meta">
                                    <span class="news-author">
                                        <i class="fas fa-user me-1"></i><?= htmlspecialchars($n['autor_nome']) ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="news-meta">
                                <span class="news-date">
                                    <i class="fas fa-calendar me-1"></i><?= date('d/m/Y H:i', strtotime($n['data'])) ?>
                                </span>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <a href="noticia.php?id=<?= $n['id'] ?>" class="btn btn-outline-primary btn-sm flex-fill">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </a>
                                <?php if (ehAdmin() || $n['autor'] == $_SESSION['usuario_id']): ?>
                                    <a href="editar_noticia.php?id=<?= $n['id'] ?>" class="btn btn-outline-warning btn-sm flex-fill">
                                        <i class="fas fa-edit me-1"></i>Editar
                                    </a>
                                    <a href="excluir_noticia.php?id=<?= $n['id'] ?>" class="btn btn-outline-danger btn-sm flex-fill" 
                                       onclick="return confirm('Tem certeza que deseja excluir esta notícia?')">
                                        <i class="fas fa-trash me-1"></i>Excluir
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; <?= date('Y') ?> ECOFOCO - Portal de Notícias Ambientais</p>
        </div>
    </footer>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"></script>
</body>
</html>