<?php 
require 'conexao.php'; 
require 'funcoes.php'; 

if ($_POST) {
    $nome = trim($_POST['nome']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];
    $confirma = $_POST['confirma_senha'];
    
    if ($nome && $email && $senha && $confirma && $senha === $confirma && strlen($senha) >= 6) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() == 0) {
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'user')");
            $stmt->execute([$nome, $email, $senha]);
            redireciona('login.php?cadastro=sucesso');
        } else {
            $erro = "Email já cadastrado.";
        }
    } else {
        $erro = "Preencha todos os campos. Senha: mínimo 6 caracteres.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro - ECOFOCO</title>
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
            <div class="ms-auto">
                <a href="login.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-in-alt me-1"></i><span>Login</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="auth-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="auth-title">Crie Sua Conta</h1>
                <p class="auth-subtitle">Junte-se à comunidade ambiental</p>
            </div>

            <?php if (isset($erro)): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-custom" role="alert">
                    <?= $erro ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label class="form-label">Nome Completo</label>
                    <input type="text" name="nome" class="form-control" placeholder="Seu nome completo" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="seu@email.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" placeholder="Mínimo 6 caracteres" minlength="6" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Confirmar Senha</label>
                    <input type="password" name="confirma_senha" class="form-control" placeholder="Digite a senha novamente" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">
                    <i class="fas fa-user-plus me-2"></i>Criar Conta
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted mb-2">Já tem uma conta?</p>
                <a href="login.php" class="btn btn-outline-primary w-100">
                    <i class="fas fa-sign-in-alt me-2"></i>Fazer Login
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