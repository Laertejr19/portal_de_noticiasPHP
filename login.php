<?php 
require 'conexao.php'; 
require 'funcoes.php'; 

if ($_POST) {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];
    
    if ($email && $senha) {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && $senha === $usuario['senha']) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];
            redireciona('dashboard.php');
        } else {
            $erro = "Email ou senha incorretos.";
        }
    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ECOFOCO</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h1 class="auth-title">Bem-vindo de Volta</h1>
                <p class="auth-subtitle">Faça login para acessar sua conta</p>
            </div>

            <?php if (isset($erro)): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-custom" role="alert">
                    <?= $erro ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['cadastro']) && $_GET['cadastro'] == 'sucesso'): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-custom" role="alert">
                    Cadastro realizado com sucesso! Faça login.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fas fa-sign-in-alt me-2"></i>Entrar
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted mb-2">Não tem uma conta?</p>
                <a href="cadastro.php" class="btn btn-outline-primary w-100">
                    <i class="fas fa-user-plus me-2"></i>Cadastre-se
                </a>
            </div>

            <div class="text-center mt-3">
                <a href="index.php" class="text-muted text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i>Voltar ao site
                </a>
            </div>
        </div>
    </div>

    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>