<?php
require 'conexao.php';
require 'funcoes.php';
protegePagina();

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['usuario_id']]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_POST) {
    $nome = trim($_POST['nome']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (!$nome || !$email) {
        $erro = "Preencha nome e email.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
        $stmt->execute([$email, $_SESSION['usuario_id']]);
        
        if ($stmt->rowCount() > 0) {
            $erro = "Este email já está em uso por outro usuário.";
        } else {
            $sql = "UPDATE usuarios SET nome = ?, email = ? WHERE id = ?";
            $params = [$nome, $email, $_SESSION['usuario_id']];

            if ($senha) {
                if (strlen($senha) < 6) {
                    $erro = "A senha deve ter no mínimo 6 caracteres.";
                } else {
                    $sql = "UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?";
                    $params = [$nome, $email, $senha, $_SESSION['usuario_id']];
                }
            }

            if (!isset($erro)) {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $_SESSION['usuario_nome'] = $nome;
                $sucesso = "Perfil atualizado com sucesso!";
                $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
                $stmt->execute([$_SESSION['usuario_id']]);
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - ECOFOCO</title>
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
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i><span>Sair</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="dashboard-header">
        <div class="container">
            <h1 class="user-welcome">Editar Perfil</h1>
            <p class="user-role">Atualize suas informações pessoais</p>
        </div>
    </section>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="news-card">
                    <div class="news-card-body">
                        <?php if (isset($erro)): ?>
                            <div class="alert alert-danger alert-dismissible fade show rounded-custom" role="alert">
                                <?= $erro ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (isset($sucesso)): ?>
                            <div class="alert alert-success alert-dismissible fade show rounded-custom" role="alert">
                                <?= $sucesso ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group">
                                <label class="form-label">Nome Completo *</label>
                                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Nova Senha (opcional)</label>
                                <input type="password" name="senha" class="form-control" placeholder="Deixe em branco para manter a senha atual">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Mínimo 6 caracteres
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 rounded-custom">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fas fa-info-circle me-2 text-primary-custom"></i>
                                                Informações da Conta
                                            </h6>
                                            <p class="card-text small mb-1">
                                                <strong>Tipo:</strong> 
                                                <span class="badge bg-<?= $usuario['tipo'] == 'admin' ? 'warning' : 'secondary' ?>">
                                                    <?= ucfirst($usuario['tipo']) ?>
                                                </span>
                                            </p>
                                            <p class="card-text small mb-0">
                                                <strong>Membro desde:</strong> 
                                                <?= date('d/m/Y', strtotime($usuario['criado_em'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <a href="dashboard.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Voltar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Salvar Alterações
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