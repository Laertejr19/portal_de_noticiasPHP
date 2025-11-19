<?php
require 'conexao.php';
require 'funcoes.php';
protegeAdmin();

// Processar ações
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = intval($_GET['id']);
    
    if ($id == $_SESSION['usuario_id']) {
        $erro = 'Você não pode modificar seu próprio usuário.';
    } else {
        try {
            if ($action === 'delete') {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM noticias WHERE autor = ?");
                $stmt->execute([$id]);
                $hasNews = $stmt->fetchColumn();
                
                if ($hasNews > 0) {
                    $erro = 'Não é possível excluir usuário que possui notícias.';
                } else {
                    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
                    $stmt->execute([$id]);
                    $sucesso = 'Usuário excluído com sucesso!';
                }
            } elseif ($action === 'toggle_admin') {
                $stmt = $pdo->prepare("UPDATE usuarios SET tipo = IF(tipo = 'admin', 'user', 'admin') WHERE id = ?");
                $stmt->execute([$id]);
                $sucesso = 'Tipo de usuário atualizado!';
            }
        } catch (PDOException $e) {
            $erro = 'Erro ao processar ação.';
        }
    }
}

// Buscar todos os usuários
$stmt = $pdo->prepare("SELECT * FROM usuarios ORDER BY criado_em DESC");
$stmt->execute();
$usuarios = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Usuários - ECOFOCO</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="imagens/corujalogo.png" alt="Logo ECOFOCO" style="height: 45px; width: auto;" class="me-2">
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
            <h1 class="user-welcome">Gerenciar Usuários</h1>
            <p class="user-role">Administre os usuários do sistema</p>
        </div>
    </section>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary-custom">
                <i class="fas fa-users me-2"></i>
                Todos os Usuários
            </h2>
            <a href="cadastro.php" class="btn btn-primary">
                <i class="fas fa-user-plus me-2"></i>Novo Usuário
            </a>
        </div>

        <?php if (isset($sucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-custom shadow-custom" role="alert">
                <?= $sucesso ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-custom shadow-custom" role="alert">
                <?= $erro ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="news-card">
            <div class="news-card-body">
                <?php if ($usuarios): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Tipo</th>
                                    <th>Data de Cadastro</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($usuario['nome']) ?></strong>
                                            <?php if ($usuario['id'] == $_SESSION['usuario_id']): ?>
                                                <br><small class="text-primary-custom">(Você)</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                                        <td>
                                            <span class="badge bg-<?= $usuario['tipo'] == 'admin' ? 'warning' : 'secondary' ?>">
                                                <?= ucfirst($usuario['tipo']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y H:i', strtotime($usuario['criado_em'])) ?></td>
                                        <td>
                                            <?php if ($usuario['id'] != $_SESSION['usuario_id']): ?>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="?action=toggle_admin&id=<?= $usuario['id'] ?>" 
                                                       class="btn <?= $usuario['tipo'] == 'admin' ? 'btn-warning' : 'btn-outline-warning' ?>">
                                                        <?= $usuario['tipo'] == 'admin' ? 'Remover Admin' : 'Tornar Admin' ?>
                                                    </a>
                                                    <a href="?action=delete&id=<?= $usuario['id'] ?>" 
                                                       class="btn btn-outline-danger"
                                                       onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <span class="text-muted">Ações não disponíveis</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h3 class="text-muted">Nenhum usuário cadastrado</h3>
                        <p class="text-muted mb-4">Comece cadastrando o primeiro usuário.</p>
                        <a href="cadastro.php" class="btn btn-primary">
                            <i class="fas fa-user-plus me-2"></i>Cadastrar Primeiro Usuário
                        </a>
                    </div>
                <?php endif; ?>
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