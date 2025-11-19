<nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="index.php">
            <img src="imagens/coruja.PNG" alt="Logo ECOFOCO" style="height: 45px; width: auto;" class="me-2">
            <span class="fs-4 text-white">ECOFOCO</span>
        </a>
        <div class="ms-auto d-flex align-items-center gap-2">
            <?php if (estaLogado()): ?>
                <a href="dashboard.php" class="btn btn-outline-light btn-sm">Painel</a>
                <?php if (ehAdmin()): ?>
                    <a href="nova_noticia.php" class="btn btn-light btn-sm">+ Nova Notícia</a>
                    <a href="gerenciar_usuarios.php" class="btn btn-outline-light btn-sm">Usuários</a>
                <?php endif; ?>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Sair</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline-light btn-sm">Login</a>
                <a href="cadastro.php" class="btn btn-light btn-sm">Cadastre-se</a>
            <?php endif; ?>
        </div>
    </div>
</nav>