<?php
require 'conexao.php';
require 'funcoes.php';
protegePagina();

if ($_POST['confirmar'] ?? '' === 'sim') {
    if (ehAdmin()) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE tipo = 'admin'");
        $stmt->execute();
        $adminCount = $stmt->fetchColumn();
        
        if ($adminCount <= 1) {
            $erro = "Não é possível excluir a conta. Você é o único administrador do sistema.";
        } else {
            $stmt = $pdo->prepare("DELETE FROM noticias WHERE autor = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
            $stmt->execute([$_SESSION['usuario_id']]);
            
            logout();
        }
    } else {
        $stmt = $pdo->prepare("DELETE FROM noticias WHERE autor = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$_SESSION['usuario_id']]);
        
        logout();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Conta - ECOFOCO</title>
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
                <a href="editar_usuario.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-user-edit me-1"></i><span>Perfil</span>
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
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                        
                        <h2 class="auth-title text-danger">Excluir Conta</h2>
                        <p class="auth-subtitle">Esta ação é irreversível</p>

                        <?php if (isset($erro)): ?>
                            <div class="alert alert-danger rounded-custom mt-4">
                                <i class="fas fa-ban me-2"></i>
                                <?= $erro ?>
                            </div>
                            <a href="dashboard.php" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-left me-2"></i>Voltar ao Painel
                            </a>
                        <?php else: ?>
                            <div class="alert alert-warning rounded-custom mt-4 text-start">
                                <h5 class="alert-heading">
                                    <i class="fas fa-warning me-2"></i>Atenção!
                                </h5>
                                <p class="mb-2"><strong>Tem certeza que deseja excluir sua conta?</strong></p>
                                <p class="mb-0 small">
                                    <?php if (ehAdmin()): ?>
                                        • Todas as suas notícias serão apagadas<br>
                                        • Você perderá o acesso administrativo<br>
                                        • Esta ação não pode ser desfeita
                                    <?php else: ?>
                                        • Todas as suas notícias serão apagadas<br>
                                        • Seus dados serão removidos permanentemente<br>
                                        • Esta ação não pode ser desfeita
                                    <?php endif; ?>
                                </p>
                            </div>

                            <form method="POST" class="d-inline">
                                <input type="hidden" name="confirmar" value="sim">
                                <button type="submit" class="btn btn-danger btn-lg mt-3">
                                    <i class="fas fa-trash me-2"></i>Sim, Excluir Minha Conta
                                </button>
                                <a href="dashboard.php" class="btn btn-outline-secondary btn-lg mt-3 ms-2">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                            </form>
                        <?php endif; ?>
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